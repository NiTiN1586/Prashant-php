<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Repository;

use Doctrine\Persistence\ManagerRegistry;
use Jagaad\WitcherApi\Entity\EstimationType;

/**
 * @template-extends AbstractRepository<EstimationType>
 */
final class EstimationTypeRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, EstimationType::class);
    }

    /**
     * @return EstimationType[]
     */
    public function findAll(bool $active = true): array
    {
        $this->softDeleteFilterAction($active);

        $results = [];

        /** @var EstimationType[] $estimationTypes */
        $estimationTypes = $this->createQueryBuilder('et')
            ->getQuery()
            ->getResult();

        foreach ($estimationTypes as $estimationType) {
            $results[$estimationType->getHandle()] = $estimationType;
        }

        $this->softDeleteFilterAction();

        return $results;
    }
}
