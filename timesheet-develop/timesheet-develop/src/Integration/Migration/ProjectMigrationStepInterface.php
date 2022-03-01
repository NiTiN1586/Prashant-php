<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Integration\Migration;

use Jagaad\WitcherApi\Entity\WitcherProject;
use Jagaad\WitcherApi\Integration\Application\IssueTracker\Jira\DTO\ProjectMigrationStepStorage;
use Jagaad\WitcherApi\Integration\Domain\IssueTracker\Project as ProjectRestResponse;

interface ProjectMigrationStepInterface
{
    public static function getPriority(): int;

    public function process(ProjectMigrationStepStorage $migrationStepStorage, WitcherProject $witcherProject, ProjectRestResponse $response): void;
}
