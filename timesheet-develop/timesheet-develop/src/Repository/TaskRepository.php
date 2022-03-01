<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Repository;

use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;
use Jagaad\WitcherApi\Entity\GitProject;
use Jagaad\WitcherApi\Entity\Label;
use Jagaad\WitcherApi\Entity\Task;
use Jagaad\WitcherApi\Entity\WitcherUser;

/**
 * @template-extends AbstractRepository<Task>
 */
final class TaskRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, Task::class);
    }

    /**
     * @param int[] $ids
     *
     * @return Task[]
     */
    public function findExternalTasksByIds(array $ids): array
    {
        return $this->createQueryBuilder('t')
            ->addSelect('gl, wp')
            ->innerJoin('t.witcherProject', 'wp')
            ->innerJoin('wp.gitProjects', 'gl')
            ->andWhere('t.externalKey IS NOT NULL')
            ->andWhere('t.id IN(:ids)')
            ->orderBy('wp.name')
            ->addOrderBy('t.externalKey')
            ->setParameter('ids', $ids)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return int[]
     */
    public function findTaskIdsForGitlabProjects(bool $active = true): array
    {
        $this->softDeleteFilterAction($active);

        $taskIds = $this->createQueryBuilder('t')
            ->select('DISTINCT t.id')
            ->innerJoin(
                GitProject::class,
                'gl',
                Join::WITH,
                'gl.witcherProject = t.witcherProject'
            )
            ->addOrderBy('t.externalKey')
            ->getQuery()
            ->getResult();

        $this->softDeleteFilterAction();

        return \array_column($taskIds, 'id');
    }

    /**
     * @param string[] $externalKeys
     *
     * @return array<string, Task>
     */
    public function findByExternalKeys(array $externalKeys, bool $active = false): array
    {
        $this->softDeleteFilterAction($active);

        $results = [];

        if (0 === \count($externalKeys)) {
            return $results;
        }

        /** @var Task[] $tasks */
        $tasks = $this->createQueryBuilder('t')
            ->andWhere('t.externalKey IN(:taskKeys)')
            ->setParameter('taskKeys', $externalKeys)
            ->getQuery()
            ->getResult();

        foreach ($tasks as $task) {
            if (null !== $task->getExternalKey()) {
                $results[$task->getExternalKey()] = $task;
            }
        }

        $this->softDeleteFilterAction();

        return $results;
    }

    /**
     * @return Task[]
     */
    public function findActiveExternalTasks(int $offsetStart, int $maxResults): array
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.externalKey IS NOT NULL')
            ->setFirstResult($offsetStart)
            ->setMaxResults($maxResults)
            ->orderBy('t.id')
            ->getQuery()
            ->getResult();
    }

    public function findOneByExternalKey(string $externalKey, bool $active = true): ?Task
    {
        $this->softDeleteFilterAction($active);

        $task = $this->createQueryBuilder('t')
            ->andWhere('t.externalKey = :externalKey')
            ->setParameter('externalKey', $externalKey)
            ->getQuery()
            ->getOneOrNullResult();

        $this->softDeleteFilterAction();

        return $task;
    }

    /**
     * @return Label[]
     */
    public function findAssignedLabelsByTaskId(int $taskId, bool $active = true): array
    {
        $this->softDeleteFilterAction($active);

        /** @var Task|null $task */
        $task = $this->createQueryBuilder('t')
            ->addSelect('l')
            ->innerJoin('t.labels', 'l')
            ->andWhere('t.id = :taskId')
            ->setParameter('taskId', $taskId)
            ->getQuery()
            ->getOneOrNullResult();

        $this->softDeleteFilterAction();

        if (null === $task) {
            return [];
        }

        return $task->getLabels()->toArray();
    }

    public function findLastId(bool $active = true): int
    {
        $this->softDeleteFilterAction($active);

        $lastId = (int) $this->createQueryBuilder('t')
            ->select('t.id')
            ->orderBy('t.id', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleScalarResult();

        $this->softDeleteFilterAction();

        return $lastId;
    }

    public function hasAssignedTasks(int $witcherProjectId, int $userId, bool $active = true): bool
    {
        $this->softDeleteFilterAction($active);
        $this->disableSoftDeleteableFor(WitcherUser::class);

        $qb = $this->createQueryBuilder('t');
        $exp = $qb->expr();

        $hasAssignedTasks = (bool) $qb->select('1')
            ->innerJoin(WitcherUser::class, 'wu', Join::WITH, 'wu.userId = :userId')
            ->andWhere(
                't.witcherProject = :witcherProjectId',
                $exp->orX(
                    't.assignee = wu.id',
                    't.createdBy = wu.id',
                    't.reporter = wu.id'
                )
            )
            ->setMaxResults(1)
            ->setParameters([
                'userId' => $userId,
                'witcherProjectId' => $witcherProjectId,
            ])
            ->getQuery()
            ->getOneOrNullResult();

        $this->softDeleteFilterAction();

        return $hasAssignedTasks;
    }

    /**
     * @return int[]
     */
    public function findAllAssignedToUserWitcherProjectIds(int $userId, bool $active = true): array
    {
        $this->softDeleteFilterAction($active);
        $this->disableSoftDeleteableFor(WitcherUser::class);

        $projectIds = $this->createQueryBuilder('t')
            ->select('IDENTITY(t.witcherProject) AS id')
            ->innerJoin(WitcherUser::class, 'wu', Join::WITH, 'wu.userId = :userId')
            ->orWhere('t.createdBy = wu.id')
            ->orWhere('t.assignee = wu.id')
            ->orWhere('t.reporter = wu.id')
            ->addGroupBy('t.witcherProject')
            ->setParameter('userId', $userId)
            ->getQuery()
            ->getScalarResult();

        $this->softDeleteFilterAction();

        return \array_column($projectIds, 'id');
    }

    /**
     * @return array<string, mixed>
     */
    public function getUserTaskCalculation(string $slug, int $userId): array
    {
        $result = [];

        $taskActivityCalculation = $this->createQueryBuilder('t')
            ->select(
                'DATE(a.activityAt) AS activityAt',
                'SUM(a.estimationTime) AS activityEstimationTime',
                'SUM(a.estimationSp) AS activityEstimationSp',
                't.slug'
            )
            ->innerJoin('t.activities', 'a')
            ->innerJoin('a.createdBy', 'cb')
            ->andWhere('cb.userId = :userId')
            ->andWhere('t.slug = :slug')
            ->addGroupBy('activityAt')
            ->addOrderBy('a.activityAt')
            ->setParameters([
                'userId' => $userId,
                'slug' => $slug,
            ])
            ->getQuery()
            ->getScalarResult();

        foreach ($taskActivityCalculation as $item) {
            $result[$item['activityAt']] = [
                'activityEstimationTime' => $item['activityEstimationTime'],
                'activityEstimationSp' => $item['activityEstimationSp'],
            ];
        }

        return $result;
    }
}
