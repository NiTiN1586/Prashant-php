<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\UserCreator\Questionnaire;

use Doctrine\ORM\EntityManagerInterface;
use Jagaad\WitcherApi\Entity\WitcherUser;
use Jagaad\WitcherApi\Integration\Domain\GitManagement\GitlabUser as GitlabUserDTO;
use Jagaad\WitcherApi\Integration\Repository\GitManagement\UserReadApiInterface;
use Jagaad\WitcherApi\Questionnaire\Interfaces\QuestionnaireHelperInterface;
use Jagaad\WitcherApi\UserCreator\Questionnaire\Cache\CacheKeys;
use Jagaad\WitcherApi\UserCreator\Questionnaire\Exception\QuestionnaireException;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

final class GitlabUser extends AbstractUserQuestionnaire
{
    private const GITLAB_USER_REGEX = '/^[0-9]+$|^$/';
    protected const QUESTION_VALIDATION_ERROR = 'Gitlab user account is required should contain only digits.';
    protected const PRIORITY = 80;

    private UserReadApiInterface $userReadApiRepository;
    private CacheInterface $cache;

    public function __construct(
        EntityManagerInterface $entityManager,
        ValidatorInterface $validator,
        UserReadApiInterface $userReadApiRepository,
        CacheInterface $cache
    ) {
        parent::__construct($entityManager, $validator);

        $this->userReadApiRepository = $userReadApiRepository;
        $this->cache = $cache;
    }

    /**
     * @throws \Throwable
     */
    public function process(QuestionnaireHelperInterface $questionnaireHelper, WitcherUser $witcherUser): void
    {
        $callback = $this->getValidationCallback();

        $answer = (int) $this->processQuestion($questionnaireHelper,
            [
                new Regex(self::GITLAB_USER_REGEX),
                new Callback($callback),
            ],
            false,
            false,
            false
        );

        if ($answer > 0) {
            $witcherUser->setGitLabUserId($answer);
        }
    }

    private function getValidationCallback(): \Closure
    {
        return function (string $gitlabUserId): void {
            try {
                $gitlabUserId = \trim($gitlabUserId);

                if ('' === $gitlabUserId) {
                    return;
                }

                /** @var GitlabUserDTO|null $gitlabUser */
                $gitlabUser = $this->cache->get(
                    \sprintf(CacheKeys::GITLAB_USER_DATA_KEY, $gitlabUserId),
                    function (ItemInterface $item) use ($gitlabUserId): ?object {
                        $item->expiresAfter(CacheKeys::USER_DATA_KEY_EXPIRE);

                        return $this->userReadApiRepository->getUserById((int) $gitlabUserId);
                    }
                );

                if (null !== $gitlabUser && $gitlabUser->getId() !== (int) $gitlabUserId) {
                    throw QuestionnaireException::create();
                }
            } catch (\Throwable $exception) {
                throw QuestionnaireException::create('User with such gitlab user id doesn\'t exist in gitlab', 0, $exception);
            }
        };
    }
}
