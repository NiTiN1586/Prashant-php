<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Integration\Migration;

use Jagaad\WitcherApi\Entity\WitcherUser;
use Jagaad\WitcherApi\Integration\Application\IssueTracker\Jira\DTO\UserMigrationStepStorage;
use Jagaad\WitcherApi\Integration\Domain\IssueTracker\User;

interface UserMigrationFlowHandlerInterface
{
    public function process(UserMigrationStepStorage $migrationStepStorage, WitcherUser $witcherUser, User $jiraUser): void;
}
