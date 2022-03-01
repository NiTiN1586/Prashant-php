<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Repository;

use Doctrine\Persistence\ManagerRegistry;
use Jagaad\WitcherApi\Entity\GitProject;

/**
 * @template-extends AbstractRepository<GitProject>
 */
final class GitProjectRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, GitProject::class);
    }

    /**
     * @return int[]
     */
    public function findWitcherProjectIdsAssignedToGitlab(bool $active = true): array
    {
        $this->softDeleteFilterAction($active);

        $result = $this->createQueryBuilder('gl')
            ->select('DISTINCT wp.id')
            ->innerJoin('gl.witcherProject', 'wp')
            ->groupBy('wp.id')
            ->getQuery()
            ->getResult();

        $this->softDeleteFilterAction();

        return $result;
    }
}
