<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Repository;

use Assert\Assertion;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;
use Jagaad\WitcherApi\Entity\WitcherProject;
use Jagaad\WitcherApi\Entity\WitcherUser;

/**
 * @template-extends AbstractRepository<WitcherProject>
 */
final class WitcherProjectRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, WitcherProject::class);
    }

    /**
     * @param array<int, string> $externalKeys
     *
     * @return array<string, WitcherProject>
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \InvalidArgumentException
     */
    public function findProjectsByExternalKey(array $externalKeys, bool $active = true): array
    {
        $this->softDeleteFilterAction($active);

        $results = [];

        Assertion::allString(
            $externalKeys,
            'Incorrect array type. Values should be a string'
        );

        $witcherProjects = $this->createQueryBuilder('wp')
            ->addSelect('ptt')
            ->leftJoin('wp.witcherProjectTrackerTaskTypes', 'ptt')
            ->andWhere('wp.externalKey IN(:externalKeys)')
            ->setParameter('externalKeys', $externalKeys)
            ->getQuery()
            ->getResult();

        /** @var WitcherProject $project */
        foreach ($witcherProjects as $project) {
            $results[$project->getExternalKey()] = $project;
        }

        $this->softDeleteFilterAction();

        return $results;
    }

    /**
     * @return array<string, WitcherProject>
     */
    public function getExternalProjectsPaginated(int $offsetStart, int $maxResults): array
    {
        $results = [];
        $projects = $this->createQueryBuilder('wp')
            ->andWhere('wp.externalKey IS NOT NULL')
            ->setFirstResult($offsetStart)
            ->setMaxResults($maxResults)
            ->orderBy('wp.slug')
            ->getQuery()
            ->getResult();

        /** @var WitcherProject[] $projects */
        foreach ($projects as $project) {
            $results[$project->getExternalKey()] = $project;
        }

        return $results;
    }

    public function findOneByExternalKey(string $externalKey, bool $active = true): ?WitcherProject
    {
        $this->softDeleteFilterAction($active);

        $project = $this->createQueryBuilder('wp')
            ->andWhere('wp.externalKey = :externalKey')
            ->setParameter('externalKey', $externalKey)
            ->getQuery()
            ->getOneOrNullResult();

        $this->softDeleteFilterAction();

        return $project;
    }

    /**
     * @return int[]
     */
    public function findAllAssignedToTeamProjectIds(int $userId, bool $active = true): array
    {
        $this->softDeleteFilterAction($active);
        $this->disableSoftDeleteableFor(WitcherUser::class);

        $projectIds = $this->createQueryBuilder('wp')
            ->select('wp.id')
            ->innerJoin(WitcherUser::class, 'wu', Join::WITH, 'wu.userId = :userId')
            ->innerJoin('wp.teams', 't')
            ->leftJoin('t.teamMembers', 'tm', Join::WITH, 'tm.userId = :userId')
            ->orWhere(
                't.teamLeader = wu.id',
                'tm IS NOT NULL'
            )
            ->addGroupBy('wp.id')
            ->setParameter('userId', $userId)
            ->getQuery()
            ->getScalarResult();

        $this->softDeleteFilterAction();

        return \array_column($projectIds, 'id');
    }

    public function isAssignedToWitcherProjectTeam(int $witcherProjectId, int $userId, bool $active = true): bool
    {
        $this->softDeleteFilterAction($active);
        $this->disableSoftDeleteableFor(WitcherUser::class);

        $qb = $this->createQueryBuilder('wp');
        $exp = $qb->expr();

        $isProjectTeamMember = (bool) $qb->select('1')
            ->innerJoin(WitcherUser::class, 'wu', Join::WITH, 'wu.userId = :userId')
            ->innerJoin('wp.teams', 't')
            ->leftJoin('t.teamMembers', 'tm', Join::WITH, 'tm.userId = :userId')
            ->andWhere('wp.id = :witcherProjectId')
            ->andWhere(
                $exp->orX(
                   't.teamLeader = wu.id',
                    $exp->isNotNull('tm')
                )
            )
            ->setParameters([
                'userId' => $userId,
                'witcherProjectId' => $witcherProjectId,
            ])
            ->getQuery()
            ->getScalarResult();

        $this->softDeleteFilterAction();

        return $isProjectTeamMember;
    }
}
