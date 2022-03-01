<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Repository;

use Doctrine\Persistence\ManagerRegistry;
use Jagaad\WitcherApi\Entity\CompanyPosition;

/**
 * @template-extends AbstractRepository<CompanyPosition>
 */
final class CompanyPositionRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, CompanyPosition::class);
    }

    public function findAll(bool $active = true): array
    {
        $results = [];
        $this->softDeleteFilterAction($active);

        /** @var CompanyPosition[] $companyPositions */
        $companyPositions = $this->createQueryBuilder('cp')
            ->getQuery()
            ->getResult();

        foreach ($companyPositions as $companyPosition) {
            $results[$companyPosition->getHandle()] = $companyPosition;
        }

        $this->softDeleteFilterAction();

        return $results;
    }
}
