<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Integration\Application\IssueTracker\Jira\Migration\Handler;

use Jagaad\WitcherApi\Entity\WitcherUser;
use Jagaad\WitcherApi\Integration\Application\Exception\ValidationException;
use Jagaad\WitcherApi\Integration\Application\IssueTracker\Jira\DTO\UserMigrationStepStorage;
use Jagaad\WitcherApi\Integration\Domain\IssueTracker\User;
use Jagaad\WitcherApi\Integration\Migration\UserMigrationFlowHandlerInterface;
use Jagaad\WitcherApi\Integration\Migration\UserMigrationStepInterface;
use Jagaad\WitcherApi\Repository\WitcherUserRepository;
use Jagaad\WitcherApi\Utils\ValidationConstraintListConvertUtils;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class UserMigrationHandler implements UserMigrationFlowHandlerInterface
{
    private ValidatorInterface $validator;

    /** @var iterable<int, UserMigrationStepInterface> */
    private iterable $userMigrationSteps;
    private WitcherUserRepository $witcherUserRepository;

    /**
     * @param iterable<int, UserMigrationStepInterface> $userMigrationSteps
     */
    public function __construct(iterable $userMigrationSteps, ValidatorInterface $validator, WitcherUserRepository $witcherUserRepository)
    {
        $this->validator = $validator;
        $this->userMigrationSteps = $userMigrationSteps;
        $this->witcherUserRepository = $witcherUserRepository;
    }

    public function process(UserMigrationStepStorage $migrationStepStorage, WitcherUser $witcherUser, User $jiraUser): void
    {
        $errors = $this->validator->validate($migrationStepStorage);

        if ($errors->count() > 0) {
            throw ValidationException::create(ValidationConstraintListConvertUtils::convertConstraintListToString($errors));
        }

        foreach ($this->userMigrationSteps as $migrationStep) {
            $migrationStep->process($migrationStepStorage, $witcherUser, $jiraUser);
        }

        $errors = $this->validator->validate($witcherUser);

        if ($errors->count() > 0) {
            throw ValidationException::create(ValidationConstraintListConvertUtils::convertConstraintListToString($errors));
        }

        $this->witcherUserRepository->save($witcherUser, false);
        $migrationStepStorage->increase();

        // To prevent DoS of user-api with many requests
        \sleep(1);
    }
}
