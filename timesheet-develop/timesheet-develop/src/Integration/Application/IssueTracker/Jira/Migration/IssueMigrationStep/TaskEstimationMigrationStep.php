<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Integration\Application\IssueTracker\Jira\Migration\IssueMigrationStep;

use Jagaad\WitcherApi\Entity\Task;
use Jagaad\WitcherApi\Integration\Application\IssueTracker\Jira\DTO\IssueMigrationStepStorage;
use Jagaad\WitcherApi\Integration\Domain\IssueTracker\Issue;
use Jagaad\WitcherApi\Integration\Domain\IssueTracker\IssueField;
use Jagaad\WitcherApi\Integration\Migration\Interfaces\IssueMigrationStepInterface;

final class TaskEstimationMigrationStep implements IssueMigrationStepInterface
{
    public const SP_ESTIMATE = 'Story point estimate';

    public static function getPriority(): int
    {
        return \count(self::ISSUE_STEP_PRIORITY) - \array_search(self::STEP_TASK_ESTIMATION, self::ISSUE_STEP_PRIORITY, true);
    }

    public function process(
        IssueMigrationStepStorage $migrationDTO,
        Issue $trackerIssue,
        Task $task
    ): void {
        /** @var IssueField $responseFields */
        $responseFields = $trackerIssue->getFields();
        $storyPointField = $migrationDTO->getCustomFieldByName(self::SP_ESTIMATE);
        $originalEstimate = $responseFields->getTimeOriginalEstimate();
        $storyPointEstimation = null !== $storyPointField ? $responseFields->getCustomField($storyPointField) : null;

        $task->setEstimationTime((int) $originalEstimate);
        $task->setEstimationSp((int) $storyPointEstimation);
    }
}
