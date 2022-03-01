<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Repository;

use Doctrine\Persistence\ManagerRegistry;
use Jagaad\WitcherApi\Entity\GitBranch;

/**
 * @template-extends AbstractRepository<GitBranch>
 */
final class GitBranchRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, GitBranch::class);
    }

    /**
     * @param array<string> $gitBranchNames
     *
     * @return array<string, GitBranch>
     */
    public function findByHandles(array $gitBranchNames, bool $active = true): array
    {
        if (0 === \count($gitBranchNames)) {
            return [];
        }

        $results = [];

        $this->softDeleteFilterAction($active);

        $gitBranches = $this->createQueryBuilder('gb')
            ->andWhere('gb.branchName IN(:gitBranchHandles)')
            ->setParameter('gitBranchHandles', $gitBranchNames)
            ->getQuery()
            ->getResult();

        /** @var GitBranch[] $gitBranches */
        foreach ($gitBranches as $gitBranch) {
            $results[$gitBranch->getBranchName()] = $gitBranch;
        }

        $this->softDeleteFilterAction();

        return $results;
    }

    public function findOneByBranchAndProject(string $branch, int $project, bool $active = true): ?GitBranch
    {
        if (0 === $project || '' === \trim($branch)) {
            return null;
        }

        $this->softDeleteFilterAction($active);

        $gitBranch = $this->createQueryBuilder('gb')
            ->innerJoin('gb.project', 'gp')
            ->andWhere('gb.branchName = :branchName')
            ->andWhere('gp.gitLabProjectId = :projectId')
            ->setParameters([
                'branchName' => $branch,
                'projectId' => (string) $project,
            ])
            ->getQuery()
            ->getOneOrNullResult();

        $this->softDeleteFilterAction();

        return $gitBranch;
    }
}
