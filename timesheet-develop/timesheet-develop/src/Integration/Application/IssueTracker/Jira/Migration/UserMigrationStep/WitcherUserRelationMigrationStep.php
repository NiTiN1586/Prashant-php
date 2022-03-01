<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Integration\Application\IssueTracker\Jira\Migration\UserMigrationStep;

use Jagaad\WitcherApi\Entity\Role;
use Jagaad\WitcherApi\Entity\WitcherUser;
use Jagaad\WitcherApi\Integration\Application\IssueTracker\Jira\DTO\UserMigrationStepStorage;
use Jagaad\WitcherApi\Integration\Domain\IssueTracker\User;
use Jagaad\WitcherApi\Integration\Migration\UserMigrationStepInterface;
use Jagaad\WitcherApi\Manager\UserManagerApiInterface;

final class WitcherUserRelationMigrationStep implements UserMigrationStepInterface
{
    private UserManagerApiInterface $userManagerApi;

    public function __construct(UserManagerApiInterface $userManagerApi)
    {
        $this->userManagerApi = $userManagerApi;
    }

    public static function getPriority(): int
    {
        return 1;
    }

    public function process(UserMigrationStepStorage $migrationStepStorage, WitcherUser $witcherUser, User $jiraUser): void
    {
        if (!$jiraUser->isActive()) {
            $witcherUser->setDeletedAt(new \DateTime());
        }

        $user = $migrationStepStorage->getUser();
        $data = ['invitationEmail' => $jiraUser->getEmailAddress(), 'active' => $jiraUser->isActive()];
        $witcherUser->setJiraAccount($jiraUser->getAccountId());
        /** @var string|null $userId */
        $userId = $user['id'] ?? null;

        if (!$witcherUser->isRoleAssigned()) {
            $role = $migrationStepStorage->getRole(Role::DEVELOPER);

            if (null === $role) {
                throw new \LogicException('Role doesn\'t exist');
            }

            $witcherUser->setRole($role);
        }

        if (null === $userId) {
            $witcherUser->setUserId($this->userManagerApi->createUser($data));

            return;
        }

        $witcherUser->setUserId((int) $userId);

        $isActive = $user['active'] ?? null;
        $email = $user['invitationEmail'] ?? null;

        if ($jiraUser->isActive() !== $isActive || $jiraUser->getEmailAddress() !== $email) {
            $this->userManagerApi->updateUser((int) $userId, $data);
        }
    }
}
