<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\UserCreator\Questionnaire;

use Doctrine\ORM\EntityManagerInterface;
use Jagaad\UserProviderBundle\Manager\UserManager;
use Jagaad\UserProviderBundle\Security\Model\User;
use Jagaad\WitcherApi\Entity\WitcherUser;
use Jagaad\WitcherApi\Exception\WitcherUser\WitherUserExistsException;
use Jagaad\WitcherApi\Questionnaire\Interfaces\QuestionnaireHelperInterface;
use Jagaad\WitcherApi\UserCreator\Questionnaire\Cache\CacheKeys;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

final class UserEmail extends AbstractUserQuestionnaire
{
    protected const QUESTION_VALIDATION_ERROR = 'Email is not valid and should be in jagaad.it domain';
    protected const PRIORITY = 100;

    private UserManager $userManager;
    private CacheInterface $cache;

    public function __construct(
        UserManager $userManager,
        CacheInterface $cache,
        EntityManagerInterface $entityManager,
        ValidatorInterface $validator
    ) {
        parent::__construct($entityManager, $validator);

        $this->userManager = $userManager;
        $this->cache = $cache;
    }

    /**
     * @throws WitherUserExistsException
     */
    public function process(QuestionnaireHelperInterface $questionnaireHelper, WitcherUser $witcherUser): void
    {
        $answer = $this->processQuestion(
            $questionnaireHelper,
            [
                new Email(),
                new NotBlank(),
            ]
        );

        $user = $this->getOrCreateUserByEmail(\trim($answer));

        $existingUser = $this->entityManager
            ->getRepository(WitcherUser::class)
            ->findOneBy(['userId' => $user->getId()]);

        if (null !== $existingUser) {
            throw WitherUserExistsException::create();
        }

        $witcherUser->setUserId($user->getId());
    }

    private function getOrCreateUserByEmail(string $email): User
    {
        $userCacheKey = \explode('@', $email);

        return $this->cache->get(
            \sprintf(CacheKeys::USER_DATA_KEY, \current($userCacheKey)),
            function (ItemInterface $item) use ($email): User {
                $item->expiresAfter(CacheKeys::USER_DATA_KEY_EXPIRE);

                return $this->userManager->getOrCreateUserByEmail($email, []);
            }
        );
    }
}
