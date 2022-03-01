<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Integration\Application\IssueTracker\Jira\Migration\ProjectMigrationStep;

use Jagaad\WitcherApi\Entity\TrackerTaskType;
use Jagaad\WitcherApi\Entity\WitcherProject;
use Jagaad\WitcherApi\Entity\WitcherProjectTrackerTaskType;
use Jagaad\WitcherApi\Integration\Application\IssueTracker\Jira\DTO\ProjectMigrationStepStorage;
use Jagaad\WitcherApi\Integration\Domain\IssueTracker\IssueType;
use Jagaad\WitcherApi\Integration\Domain\IssueTracker\Project as ProjectRestResponse;
use Jagaad\WitcherApi\Integration\Migration\ProjectMigrationStepInterface;
use Jagaad\WitcherApi\Repository\TrackerTaskTypeRepository;
use Jagaad\WitcherApi\Utils\StringUtils;

final class ProjectIssueTypePopulationStep implements ProjectMigrationStepInterface
{
    private TrackerTaskTypeRepository $trackerTaskTypeRepository;

    public function __construct(TrackerTaskTypeRepository $trackerTaskTypeRepository)
    {
        $this->trackerTaskTypeRepository = $trackerTaskTypeRepository;
    }

    public static function getPriority(): int
    {
        return 0;
    }

    public function process(ProjectMigrationStepStorage $migrationStepStorage, WitcherProject $witcherProject, ProjectRestResponse $response): void
    {
        $witcherProjectTrackerTypesToRemove = [];

        /** @var array<string, IssueType> $issueTypes */
        $issueTypes = \array_column(
            \array_map(
                static fn (IssueType $issueType): array => ['key' => $issueType->getName(), 'value' => $issueType],
                $response->getIssueTypes()
            ),
            'value',
            'key'
        );

        if ($witcherProject->getWitcherProjectTrackerTaskTypes()->count() > 0) {
            /** @var WitcherProjectTrackerTaskType $projectTrackerTaskType */
            foreach ($witcherProject->getWitcherProjectTrackerTaskTypes() as $projectTrackerTaskType) {
                $trackerIssueType = $projectTrackerTaskType->getTrackerTaskType();

                if (!isset($issueTypes[$trackerIssueType->getFriendlyName()])) {
                    $witcherProjectTrackerTypesToRemove[] = $projectTrackerTaskType;

                    continue;
                }

                unset($issueTypes[$trackerIssueType->getFriendlyName()]);
            }
        }

        foreach ($witcherProjectTrackerTypesToRemove as $trackerType) {
            $witcherProject->removeWitcherProjectTrackerTaskType($trackerType);
        }

        foreach ($issueTypes as $issueType) {
            $witcherProject->addWitcherProjectTrackerTaskType(
                WitcherProjectTrackerTaskType::create(
                    $witcherProject,
                    $this->getTrackerTaskTypeToAssign($issueType, $migrationStepStorage),
                    $witcherProject->getCreatedBy(),
                    $issueType->isSubtask()
                )
            );
        }
    }

    private function getTrackerTaskTypeToAssign(IssueType $issueType, ProjectMigrationStepStorage $migrationStepStorage): TrackerTaskType
    {
        $trackerTaskType = $migrationStepStorage->getTrackerTaskTypeByName($issueType->getName());

        if (null !== $trackerTaskType) {
            return $trackerTaskType;
        }

        $trackerTaskType = TrackerTaskType::create(
            StringUtils::convertNameToHandle($issueType->getName()),
            $issueType->getName()
        );

        $this->trackerTaskTypeRepository->save($trackerTaskType, false);
        $migrationStepStorage->appendTrackerTaskType($trackerTaskType);

        return $trackerTaskType;
    }
}
