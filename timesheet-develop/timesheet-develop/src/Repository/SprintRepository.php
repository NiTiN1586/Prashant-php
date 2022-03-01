<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Repository;

use Doctrine\Persistence\ManagerRegistry;
use Jagaad\WitcherApi\Entity\Sprint;

/**
 * @template-extends AbstractRepository<Sprint>
 */
final class SprintRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, Sprint::class);
    }

    /**
     * @param int[] $externalIds
     *
     * @return array<int, Sprint>
     */
    public function findByExternalIds(array $externalIds, bool $active = true): array
    {
        if (0 === \count($externalIds)) {
            return [];
        }

        $this->softDeleteFilterAction($active);

        $sprints = [];

        /** @var Sprint[] $response */
        $response = $this->createQueryBuilder('s')
            ->andWhere('s.externalId IN (:externalIds)')
            ->setParameter('externalIds', $externalIds)
            ->getQuery()
            ->getResult();

        foreach ($response as $sprint) {
            $sprints[$sprint->getExternalId()] = $sprint;
        }

        $this->softDeleteFilterAction();

        return $sprints;
    }

    /**
     * @param int $externalId
     *
     * @return Sprint|null
     */
    public function findOnyByExternalId(int $externalId, bool $active = true): ?Sprint
    {
        if ($externalId <= 0) {
            return null;
        }

        $this->softDeleteFilterAction($active);

        /** @var Sprint|null $sprint */
        $sprint = $this->createQueryBuilder('s')
            ->andWhere('s.externalId = :externalId')
            ->setParameter('externalId', $externalId)
            ->getQuery()
            ->getOneOrNullResult();

        $this->softDeleteFilterAction();

        return $sprint;
    }
}
