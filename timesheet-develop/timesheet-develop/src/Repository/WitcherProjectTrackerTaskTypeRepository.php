<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Repository;

use Doctrine\Persistence\ManagerRegistry;
use Jagaad\WitcherApi\Entity\WitcherProjectTrackerTaskType;

/**
 * @template-extends AbstractRepository<WitcherProjectTrackerTaskType>
 */
final class WitcherProjectTrackerTaskTypeRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, WitcherProjectTrackerTaskType::class);
    }

    /**
     * @param int $projectId
     * @param bool $active
     *
     * @return array<string, WitcherProjectTrackerTaskType>
     */
    public function getWitcherProjectActivitiesByProjectIdKeyedByActivityName(int $projectId, bool $active = true): array
    {
        $this->softDeleteFilterAction($active);

        $results = [];

        $witcherProjectTrackerTaskTypes = $this->createQueryBuilder('wpat')
            ->addSelect('at', 'wp')
            ->innerJoin('wpat.witcherProject', 'wp')
            ->innerJoin('wpat.trackerTaskType', 'at')
            ->andWhere('wpat.witcherProject = :projectId')
            ->setParameters([
                'projectId' => $projectId,
            ])
            ->getQuery()
            ->getResult();

        /** @var WitcherProjectTrackerTaskType[] $witcherProjectTrackerTaskTypes */
        foreach ($witcherProjectTrackerTaskTypes as $witcherTrackerTaskType) {
            $results[$witcherTrackerTaskType->getTrackerTaskType()->getFriendlyName()] = $witcherTrackerTaskType;
        }

        $this->softDeleteFilterAction();

        return $results;
    }
}
