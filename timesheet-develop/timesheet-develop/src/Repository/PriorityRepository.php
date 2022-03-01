<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Repository;

use Doctrine\Persistence\ManagerRegistry;
use Jagaad\WitcherApi\Entity\Priority;
use Jagaad\WitcherApi\Integration\Infrastructure\Cache\CacheKeys;

/**
 * @template-extends AbstractRepository<Priority>
 */
final class PriorityRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, Priority::class);
    }

    /**
     * @param string[] $names
     *
     * @return Priority[]
     */
    public function findByNames(array $names, bool $active = true): array
    {
        $this->softDeleteFilterAction($active);

        $results = $this->createQueryBuilder('p')
            ->andWhere('p.friendlyName IN(:names)')
            ->setParameter('names', $names)
            ->getQuery()
            ->getResult();

        $this->softDeleteFilterAction();

        return $results;
    }

    /**
     * @return array<string, Priority>
     */
    public function findAll(
        bool $active = true,
        bool $useCache = false,
        int $expirationTime = CacheKeys::INTEGRATION_DATA_KEY_EXPIRE_1_HOUR
    ): array {
        $this->softDeleteFilterAction($active);

        $results = [];

        $query = $this->createQueryBuilder('p')
            ->getQuery();

        if ($useCache) {
            $query = $query->enableResultCache($expirationTime);
        }

        /** @var Priority[] $priorities */
        $priorities = $query->getResult();

        foreach ($priorities as $priority) {
            $results[$priority->getFriendlyName()] = $priority;
        }

        $this->softDeleteFilterAction();

        return $results;
    }
}
