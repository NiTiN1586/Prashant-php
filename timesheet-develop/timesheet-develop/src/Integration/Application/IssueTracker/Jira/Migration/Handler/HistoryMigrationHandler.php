<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Integration\Application\IssueTracker\Jira\Migration\Handler;

use Jagaad\WitcherApi\Entity\Task;
use Jagaad\WitcherApi\Integration\Application\DTO\Request;
use Jagaad\WitcherApi\Integration\Migration\Interfaces\HistoryMigrationFlowHandlerInterface;
use Jagaad\WitcherApi\Integration\Migration\Interfaces\HistoryMigrationStepInterface;

final class HistoryMigrationHandler implements HistoryMigrationFlowHandlerInterface
{
    /** @var iterable<int, HistoryMigrationStepInterface> */
    private iterable $migrationSteps;

    /**
     * @param iterable<int, HistoryMigrationStepInterface> $migrationSteps
     */
    public function __construct(iterable $migrationSteps)
    {
        $this->migrationSteps = $migrationSteps;
    }

    public function process(Request $request, Task $task): void
    {
        foreach ($this->migrationSteps as $migrationStep) {
            $migrationStep->process($request, $task);
        }
    }
}
