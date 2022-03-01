<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\UserCreator\Questionnaire;

use Doctrine\ORM\EntityManagerInterface;
use Jagaad\UserProviderBundle\Manager\UserManager;
use Jagaad\UserProviderBundle\Security\Model\User;
use Jagaad\WitcherApi\Cache\CacheKeys;
use Jagaad\WitcherApi\Entity\WitcherUser;
use Jagaad\WitcherApi\Integration\Domain\IssueTracker\User as JiraUser;
use Jagaad\WitcherApi\Integration\Repository\IssueTracker\UserReadApiInterface;
use Jagaad\WitcherApi\Questionnaire\Interfaces\QuestionnaireHelperInterface;
use Jagaad\WitcherApi\UserCreator\Questionnaire\Cache\CacheKeys as QuestionnaireCacheKeys;
use Jagaad\WitcherApi\UserCreator\Questionnaire\Exception\QuestionnaireException;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

final class JiraAccount extends AbstractUserQuestionnaire
{
    private const JIRA_USER_REGEX = '/^[a-z0-9]+$/';
    protected const QUESTION_VALIDATION_ERROR = 'Jira account should not be blank';
    protected const PRIORITY = 90;

    private UserReadApiInterface $userReadApiRepository;
    private UserManager $userManager;
    private CacheInterface $cache;

    public function __construct(
        EntityManagerInterface $entityManager,
        ValidatorInterface $validator,
        UserReadApiInterface $userReadApiRepository,
        UserManager $userManager,
        CacheInterface $cache
    ) {
        parent::__construct($entityManager, $validator);

        $this->userReadApiRepository = $userReadApiRepository;
        $this->userManager = $userManager;
        $this->cache = $cache;
    }

    public function process(QuestionnaireHelperInterface $questionnaireHelper, WitcherUser $witcherUser): void
    {
        $callback = $this->getValidationCallback($witcherUser);

        $answer = $this->processQuestion(
            $questionnaireHelper,
            [new Regex(self::JIRA_USER_REGEX), new Callback($callback)]
        );

        $witcherUser->setJiraAccount($answer);
    }

    private function getValidationCallback(WitcherUser $witcherUser): \Closure
    {
        return function (string $jiraAccount) use ($witcherUser): void {
            try {
                $jiraAccount = \trim($jiraAccount);

                /** @var JiraUser $jiraUser */
                $jiraUser = $this->cache->get(
                    \sprintf(QuestionnaireCacheKeys::JIRA_USER_DATA_KEY, $jiraAccount),
                    function (ItemInterface $item) use ($jiraAccount): JiraUser {
                        $item->expiresAfter(QuestionnaireCacheKeys::USER_DATA_KEY_EXPIRE);

                        $user = $this->userReadApiRepository->getUserByAccountId($jiraAccount);

                        if (!$user instanceof JiraUser) {
                            throw new \LogicException('Incorrect type returned for jira user');
                        }

                        return $user;
                    }
                );
            } catch (\Throwable $exception) {
                throw QuestionnaireException::create('User with such jira account doesn\'t exist in jira', 0, $exception);
            }

            /** @var User $user */
            $user = $this->cache->get(
                \sprintf(CacheKeys::USER_DATA_KEY, $witcherUser->getUserId()),
                function (ItemInterface $item) use ($witcherUser): User {
                    $user = $this->userManager->getUserById($witcherUser->getUserId());

                    if (null === $user) {
                        throw new \LogicException('User doesn\'t exist in jagaad-user-api service');
                    }

                    $item->expiresAfter(CacheKeys::USER_DATA_KEY_EXPIRE_1_HOUR);

                    return $user;
                }
            );

            if ($jiraUser->getEmailAddress() !== $user->getInvitationEmail()) {
                throw QuestionnaireException::create('Jira account email doesn\'t match with user email');
            }
        };
    }
}
