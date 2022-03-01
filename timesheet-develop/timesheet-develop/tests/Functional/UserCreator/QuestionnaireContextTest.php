<?php

declare(strict_types=1);

namespace App\Tests\Functional\UserCreator;

use Doctrine\ORM\EntityManagerInterface;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;
use Jagaad\UserProviderBundle\Manager\UserManager;
use Jagaad\UserProviderBundle\Security\Model\GoogleAccount;
use Jagaad\UserProviderBundle\Security\Model\User;
use Jagaad\WitcherApi\Entity\WitcherUser;
use Jagaad\WitcherApi\Integration\Domain\GitManagement\GitlabUser as GitLabUserDTO;
use Jagaad\WitcherApi\Integration\Domain\IssueTracker\User as JiraUser;
use Jagaad\WitcherApi\Integration\Repository\GitManagement\UserReadApiInterface;
use Jagaad\WitcherApi\Integration\Repository\IssueTracker\UserReadApiInterface as IssueTrackerUserReadApiInterface;
use Jagaad\WitcherApi\Questionnaire\Interfaces\QuestionnaireHelperInterface;
use Jagaad\WitcherApi\Repository\WitcherUserRepository;
use Jagaad\WitcherApi\UserCreator\Questionnaire\AbstractUserQuestionnaire;
use Jagaad\WitcherApi\UserCreator\Questionnaire\CompanyPosition;
use Jagaad\WitcherApi\UserCreator\Questionnaire\Department;
use Jagaad\WitcherApi\UserCreator\Questionnaire\GitlabUser;
use Jagaad\WitcherApi\UserCreator\Questionnaire\JiraAccount;
use Jagaad\WitcherApi\UserCreator\Questionnaire\UserEmail;
use Jagaad\WitcherApi\UserCreator\Questionnaire\WitcherRoles;
use Jagaad\WitcherApi\UserCreator\QuestionnaireContext;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\Cache\CacheInterface;

class QuestionnaireContextTest extends KernelTestCase
{
    use ReloadDatabaseTrait;

    private ValidatorInterface $validator;

    private EntityManagerInterface $entityManager;

    /**
     * @var AbstractUserQuestionnaire[]
     */
    private iterable $questionnaire;
    private QuestionnaireHelperInterface|MockObject $questionnaireHelper;

    private WitcherUserRepository $witcherUserRepository;

    protected function setUp(): void
    {
        self::bootKernel(['environment' => 'test']);

        $container = static::getContainer();

        $this->witcherUserRepository = $container->get(WitcherUserRepository::class);
        $this->validator = $container->get(ValidatorInterface::class);
        $this->entityManager = $container->get(EntityManagerInterface::class);

        $userReadApi = $this->callUserReadApiMockForGitLab();
        $cacheMock = $this->callCacheMock();

        $gitlabUser = new GitlabUser($this->entityManager, $this->validator, $userReadApi, $cacheMock);

        $userReadApiForJiraAccount = $this->callUserReadApiMockForJiraAccount();
        $userManagerForJiraAccount = $this->callUserManagerMockForJiraAccount();

        $jiraAccount = new JiraAccount($this->entityManager, $this->validator, $userReadApiForJiraAccount, $userManagerForJiraAccount, $cacheMock);

        $department = $container->get(Department::class);

        $userManagerForUserEmail = $this->callUserManagerMockForUserEmail();

        $userEmail = new UserEmail($userManagerForUserEmail, $cacheMock, $this->entityManager, $this->validator);

        $witcherRoles = $container->get(WitcherRoles::class);

        $companyPosition = $container->get(CompanyPosition::class);

        $this->questionnaire = [$userEmail, $witcherRoles, $companyPosition, $department, $jiraAccount, $gitlabUser];

        $this->questionnaireHelper = $this->createMock(QuestionnaireHelperInterface::class);
    }

    public function testUserCreationCommand(): void
    {
        $userData['userEmail'] = 'test@jagaad.it';
        $userData['witcherRoles'] = 1;
        $userData['companyPosition'] = 'CTO';
        $userData['department'] = 1;
        $userData['jiraAccount'] = 'jiratest123';
        $userData['gitlabUser'] = '12345';

        $this->callMockQuestions($userData);

        $this->callQuestionnaireContext();

        /** @var WitcherUser $witcherUser */
        $witcherUser = $this->witcherUserRepository->findOneBy(['jiraAccount' => $userData['jiraAccount']]);

        $this->assertSame($userData['jiraAccount'], $witcherUser->getJiraAccount());
    }

    public function testUserCreationWithWrongGitlabId(): void
    {
        $this->expectExceptionMessage('Gitlab user account is required should contain only digits.');
        $this->expectExceptionMessage('User with such gitlab user id doesn\'t exist in gitlab');

        $userData['userEmail'] = 'test@jagaad.it';
        $userData['witcherRoles'] = 1;
        $userData['companyPosition'] = 'CTO';
        $userData['department'] = 1;
        $userData['jiraAccount'] = 'jiratest123';
        $userData['gitlabUser'] = 'test';

        $this->callMockQuestions($userData);

        $this->callQuestionnaireContext();
    }

    public function testUserCreationWithWrongJiraAccount(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Jira account should not be blank');

        $userData['userEmail'] = 'test@jagaad.it';
        $userData['witcherRoles'] = 1;
        $userData['companyPosition'] = 'CTO';
        $userData['department'] = 1;
        $userData['jiraAccount'] = 'TEST123'; // only allow lowercase and number
        $userData['gitlabUser'] = '12345';

        $this->callMockQuestions($userData);

        $this->callQuestionnaireContext();
    }

    private function callQuestionnaireContext(): void
    {
        $questionnaireContext = new QuestionnaireContext(
            $this->questionnaire,
            $this->validator,
            $this->entityManager
        );

        $questionnaireContext->start($this->questionnaireHelper);
    }

    private function callMockQuestions($userData): void
    {
        $this->mockQuestions($userData, function ($text, Question $question, array $userData) {
            // handle a questions with answers
            switch (true) {
                case false !== \strpos($text, 'user UserEmail'):
                    return $userData['userEmail'];
                case false !== \strpos($text, 'user WitcherRoles'):
                    return $userData['witcherRoles']; // [0] Developer
                case false !== \strpos($text, 'user CompanyPosition'):
                    return $userData['companyPosition']; // [3] CTO
                case false !== \strpos($text, 'user Department'):
                    return $userData['department']; // [0] Development
                case false !== \strpos($text, 'user JiraAccount'):
                    return $userData['jiraAccount']; // Jira AccountId
                case false !== \strpos($text, 'user GitlabUser'):
                    return $userData['gitlabUser']; // Gitlab userId
                default:
                    throw new \RuntimeException('Was asked for input on an unhandled question: '.$text);
            }
        });
    }

    private function mockQuestions(array $userData, callable $questions): void
    {
        $ask = function (Question $question) use ($questions, $userData) {
            $text = $question->getQuestion();

            $response = \call_user_func($questions, $text, $question, $userData);

            $interviewer = function () use ($response) {
                return $response;
            };

            return !$question->getValidator() ? $response : $question->getValidator()($interviewer());
        };

        $this->questionnaireHelper->expects($this->any())
            ->method('ask')
            ->willReturnCallback($ask);
    }

    /**
     * @return UserReadApiInterface|MockObject
     */
    private function callUserReadApiMockForGitLab()
    {
        $gitlabUser = new GitLabUserDTO();
        $gitlabUser->setId(12345);
        $gitlabUser->setName('sandiptest');
        $gitlabUser->setUsername('test@jagaad.it');
        $gitlabUser->setState('sandip');
        $gitlabUser->setWebUrl('https://test/');
        $gitlabUser->setAvatarUrl('https://avatartest/');

        $userReadApi = $this->createMock(UserReadApiInterface::class);
        $userReadApi->expects($this->any())
          ->method('getUserById')
          ->willReturn($gitlabUser);

        return $userReadApi;
    }

    /**
     * @return IssueTrackerUserReadApiInterface|MockObject
     */
    private function callUserReadApiMockForJiraAccount()
    {
        $userReadApiForJiraAccount = $this->createMock(IssueTrackerUserReadApiInterface::class);
        $userReadApiForJiraAccount->expects($this->any())
            ->method('getUserByAccountId')
            ->willReturn($this->createJiraUser());

        return $userReadApiForJiraAccount;
    }

    /**
     * @return UserManager|MockObject
     */
    private function callUserManagerMockForJiraAccount()
    {
        $userManagerForJiraAccount = $this->createMock(UserManager::class);
        $userManagerForJiraAccount->expects($this->any())
            ->method('getUserById')
            ->willReturn($this->createUser(['admin', 'developer']));

        return $userManagerForJiraAccount;
    }

    /**
     * @return UserManager|MockObject
     */
    private function callUserManagerMockForUserEmail()
    {
        $userManager = $this->createMock(UserManager::class);

        $userManager->expects($this->any())
            ->method('getOrCreateUserByEmail')
            ->willReturn($this->createUser(['admin', 'developer']));

        return $userManager;
    }

    /**
     * @return CacheInterface|MockObject
     */
    private function callCacheMock()
    {
        $user = $this->createUser(['admin', 'developer']);

        $cache = $this->createMock(CacheInterface::class);
        $cache->expects($this->any())
            ->method('get')
            ->willReturnOnConsecutiveCalls(
                $user,
                $this->createJiraUser(),
                $user,
                $this->createGitlabUser()
            );

        return $cache;
    }

    /**
     * @param string[] $roles
     */
    private function createUser(array $roles): User
    {
        $googleAccount = (new GoogleAccount())
            ->setFirstName('testfirstname')
            ->setLastName('testlastname')
            ->setEmail('test@jagaad.it')
            ->setGoogleAccountId('test123')
            ->setAvatarUrl('http://testavatar');

        return (new User())
            ->setActive(true)
            ->setCreatedAt(new \DateTime())
            ->setInvitationEmail('test@jagaad.it')
            ->setId(12)
            ->setGoogleAccounts([$googleAccount])
            ->setRoles($roles);
    }

    private function createGitlabUser(): GitLabUserDTO
    {
        return (new GitLabUserDTO())
            ->setId(12345)
            ->setName('sandiptest')
            ->setUsername('test@jagaad.it')
            ->setState('sandip')
            ->setWebUrl('https://test/')
            ->setAvatarUrl('https://avatartest/');
    }

    private function createJiraUser(): JiraUser
    {
        return (new JiraUser())
            ->setKey('test')
            ->setAccountId('jiratest123')
            ->setActive(true)
            ->setDisplayName('testdisplayname')
            ->setEmailAddress('test@jagaad.it')
            ->setExpand('test')
            ->setName('test test')
            ->setTimeZone('europe/rome');
    }
}
