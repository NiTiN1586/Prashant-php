<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Repository;

use Doctrine\Persistence\ManagerRegistry;
use Jagaad\WitcherApi\Entity\TrackerTaskType;
use Jagaad\WitcherApi\Integration\Infrastructure\Cache\CacheKeys;

/**
 * @template-extends AbstractRepository<TrackerTaskType>
 */
final class TrackerTaskTypeRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, TrackerTaskType::class);
    }

    /**
     * @return array<string, TrackerTaskType>
     */
    public function findAll(
        bool $active = true,
        bool $useCache = false,
        int $expirationTime = CacheKeys::INTEGRATION_DATA_KEY_EXPIRE_1_HOUR
    ): array {
        $this->softDeleteFilterAction($active);

        $results = [];

        $query = $this->createQueryBuilder('tt')
            ->getQuery();

        if ($useCache) {
            $query = $query->enableResultCache($expirationTime);
        }

        $trackerTaskTypes = $query->getResult();

        /** @var TrackerTaskType[] $trackerTaskTypes */
        foreach ($trackerTaskTypes as $trackerTaskType) {
            $results[$trackerTaskType->getFriendlyName()] = $trackerTaskType;
        }

        $this->softDeleteFilterAction();

        return $results;
    }
}
