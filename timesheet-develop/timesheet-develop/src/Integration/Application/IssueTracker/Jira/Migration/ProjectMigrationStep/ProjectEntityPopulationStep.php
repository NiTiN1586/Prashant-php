<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Integration\Application\IssueTracker\Jira\Migration\ProjectMigrationStep;

use Jagaad\WitcherApi\Entity\WitcherProject;
use Jagaad\WitcherApi\Integration\Application\Exception\IntegrationException;
use Jagaad\WitcherApi\Integration\Application\IssueTracker\Jira\DTO\ProjectMigrationStepStorage;
use Jagaad\WitcherApi\Integration\Domain\IssueTracker\Project as ProjectRestResponse;
use Jagaad\WitcherApi\Integration\Migration\ProjectMigrationStepInterface;

final class ProjectEntityPopulationStep implements ProjectMigrationStepInterface
{
    private string $issueTrackerHost;

    public function __construct(string $issueTrackerHost)
    {
        $this->issueTrackerHost = $issueTrackerHost;
    }

    public static function getPriority(): int
    {
        return 1;
    }

    public function process(ProjectMigrationStepStorage $migrationStepStorage, WitcherProject $witcherProject, ProjectRestResponse $response): void
    {
        if (null === $response->getLead() || '' === $response->getLead()->getAccountId()) {
            throw IntegrationException::create('Jira project owner account is required');
        }

        $owner = $migrationStepStorage->getOwner($response->getLead()->getAccountId());

        if (null === $owner) {
            throw new \LogicException('Project owner is required');
        }

        $externalKey = \trim($response->getKey());
        $witcherProject->setName(\trim($response->getName()));
        $witcherProject->setExternalKey($externalKey);
        $witcherProject->setCreatedBy($owner);

        $witcherProject->setSlug($externalKey);
        $witcherProject->setDescription($response->getDescription());
        $witcherProject->setExternalTrackerLink(
            \sprintf(
                '%s/browse/%s',
                \rtrim($this->issueTrackerHost, '/'),
                $response->getKey()
            )
        );
    }
}
