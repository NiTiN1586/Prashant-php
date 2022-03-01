<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Integration\Migration\Interfaces;

use Jagaad\WitcherApi\Entity\Task;
use Jagaad\WitcherApi\Integration\Application\IssueTracker\Jira\DTO\IssueMigrationStepStorage;
use Jagaad\WitcherApi\Integration\Domain\IssueTracker\Issue;

interface IssueMigrationStepInterface
{
    public const STEP_HISTORY_POPULATION = 'HISTORY_POPULATION';
    public const STEP_TASK_ENTITY_POPULATION = 'TASK_ENTITY_POPULATION';
    public const STEP_TASK_ESTIMATION = 'TASK_ESTIMATION';
    public const STEP_TASK_ISSUE_TYPE = 'TASK_ISSUE_TYPE';
    public const STEP_TASK_LABEL = 'TASK_LABEL';
    public const STEP_TRACKER_USER = 'TRACKER_USER';
    public const STEP_SPRINT = 'STEP_SPRINT';

    public const ISSUE_STEP_PRIORITY = [
        self::STEP_TASK_ENTITY_POPULATION,
        self::STEP_TASK_ESTIMATION,
        self::STEP_TASK_ISSUE_TYPE,
        self::STEP_TASK_LABEL,
        self::STEP_TRACKER_USER,
        self::STEP_HISTORY_POPULATION,
        self::STEP_SPRINT,
    ];

    public static function getPriority(): int;

    public function process(IssueMigrationStepStorage $migrationDTO, Issue $trackerIssue, Task $task): void;
}
