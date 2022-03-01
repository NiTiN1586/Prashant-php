<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Integration\Migration\Interfaces;

use Jagaad\WitcherApi\Entity\Task;
use Jagaad\WitcherApi\Integration\Application\IssueTracker\Jira\DTO\IssueMigrationStepStorage;
use Jagaad\WitcherApi\Integration\Domain\IssueTracker\Issue;

interface IssueMigrationFlowHandlerInterface
{
    public function process(IssueMigrationStepStorage $issueMigrationStepStorage, Task $task, Issue $response): void;
}
