<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Integration\Application\IssueTracker\Jira\Migration\IssueMigrationStep;

use Doctrine\Common\Collections\Collection;
use Jagaad\WitcherApi\Entity\Task;
use Jagaad\WitcherApi\Entity\TrackerTaskType;
use Jagaad\WitcherApi\Entity\WitcherProjectTrackerTaskType;
use Jagaad\WitcherApi\Integration\Application\IssueTracker\Jira\DTO\IssueMigrationStepStorage;
use Jagaad\WitcherApi\Integration\Domain\IssueTracker\Issue;
use Jagaad\WitcherApi\Integration\Domain\IssueTracker\IssueField;
use Jagaad\WitcherApi\Integration\Migration\Interfaces\IssueMigrationStepInterface;

final class TaskIssueTypePopulationStep implements IssueMigrationStepInterface
{
    public static function getPriority(): int
    {
        return \count(self::ISSUE_STEP_PRIORITY) - \array_search(self::STEP_TASK_ISSUE_TYPE, self::ISSUE_STEP_PRIORITY, true);
    }

    public function process(IssueMigrationStepStorage $migrationDTO, Issue $trackerIssue, Task $task): void
    {
        /** @var IssueField $responseFields */
        $responseFields = $trackerIssue->getFields();
        $taskIssueType = $responseFields->getIssueType()->getName();

        $issueType = $migrationDTO->getIssueTypes()[$taskIssueType] ?? null;

        if (null === $issueType) {
            throw new \LogicException(\sprintf('IssueType \'%s\' doesn\'t exist.', $taskIssueType));
        }

        if (!$this->trackerTaskTypeAllowed($task->getWitcherProject()->getWitcherProjectTrackerTaskTypes(), $issueType)) {
            throw new \LogicException(\sprintf('IssueType \'%s\' was not assigned to task project.', $taskIssueType));
        }

        $task->setTrackerTaskType($issueType);
    }

    /**
     * @param Collection<int, WitcherProjectTrackerTaskType> $witcherProjectTrackerTaskTypes
     */
    private function trackerTaskTypeAllowed(Collection $witcherProjectTrackerTaskTypes, TrackerTaskType $trackerTaskType): bool
    {
        /** @var WitcherProjectTrackerTaskType $witcherProjectTrackerTaskType */
        foreach ($witcherProjectTrackerTaskTypes as $witcherProjectTrackerTaskType) {
            if ($witcherProjectTrackerTaskType->getTrackerTaskType()->getFriendlyName() === $trackerTaskType->getFriendlyName()) {
                return !$witcherProjectTrackerTaskType->isDeleted();
            }
        }

        return false;
    }
}
