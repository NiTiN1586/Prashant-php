<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Repository;

use Doctrine\Persistence\ManagerRegistry;
use Jagaad\WitcherApi\Entity\Status;
use Jagaad\WitcherApi\Integration\Infrastructure\Cache\CacheKeys;

/**
 * @template-extends AbstractRepository<Status>
 */
final class StatusRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, Status::class);
    }

    /**
     * @param string[] $names
     *
     * @return Status[]
     */
    public function findByNames(array $names, bool $active = true): array
    {
        $this->softDeleteFilterAction($active);

        $results = $this->createQueryBuilder('s')
            ->andWhere('s.friendlyName IN(:names)')
            ->setParameter('names', $names)
            ->getQuery()
            ->getResult();

        $this->softDeleteFilterAction();

        return $results;
    }

    /**
     * @return array<string, Status>
     */
    public function findAll(
        bool $active = true,
        bool $useCache = false,
        int $expirationTime = CacheKeys::INTEGRATION_DATA_KEY_EXPIRE_1_HOUR
    ): array {
        $this->softDeleteFilterAction($active);

        $results = [];

        $query = $this->createQueryBuilder('s')
            ->getQuery();

        if ($useCache) {
            $query = $query->enableResultCache($expirationTime);
        }

        /** @var Status[] $statuses */
        $statuses = $query->getResult();

        foreach ($statuses as $status) {
            $results[$status->getFriendlyName()] = $status;
        }

        $this->softDeleteFilterAction();

        return $results;
    }
}
