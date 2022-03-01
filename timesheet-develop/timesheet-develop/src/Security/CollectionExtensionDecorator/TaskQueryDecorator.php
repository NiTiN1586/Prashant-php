<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Security\CollectionExtensionDecorator;

use Doctrine\ORM\QueryBuilder;
use Jagaad\WitcherApi\Entity\Task;
use Jagaad\WitcherApi\Repository\TaskRepository;
use Jagaad\WitcherApi\Repository\WitcherProjectRepository;
use Jagaad\WitcherApi\Repository\WitcherUserRepository;
use Jagaad\WitcherApi\Security\Enum\Permission\Project as EnumProject;
use Jagaad\WitcherApi\Security\Enum\Permission\Task as EnumTask;
use Symfony\Component\Security\Core\Security;

final class TaskQueryDecorator extends AbstractQueryDecorator
{
    public function __construct(
        Security $security,
        private WitcherUserRepository $witcherUserRepository,
        private WitcherProjectRepository $witcherProjectRepository,
        private TaskRepository $taskRepository
    ) {
        parent::__construct($security);
    }

    protected function decorate(QueryBuilder $queryBuilder, array $permissions, string $resource): void
    {
        if (\in_array(EnumTask::VIEW_ALL_TASKS->value, $permissions, true)
            && \in_array(EnumProject::VIEW_ALL_PROJECTS->value, $permissions, true)
        ) {
            return;
        }

        if (\in_array(EnumProject::VIEW_ASSIGNED_PROJECTS->value, $permissions, true)
            && \in_array(EnumTask::VIEW_ALL_TASKS->value, $permissions, true)
        ) {
            $this->whereAllowedAssignedTasks($queryBuilder);

            return;
        }

        $alias = $queryBuilder->getRootAliases()[0];
        $exp = $queryBuilder->expr();

        $queryBuilder->andWhere(
            $exp->orX(
                \sprintf('%s.createdBy = :witcherUserId', $alias),
                \sprintf('%s.reporter = :witcherUserId', $alias),
                \sprintf('%s.assignee = :witcherUserId', $alias)
            )
        )
            ->setParameter(
                'witcherUserId',
                $this->witcherUserRepository->findIdByUser($this->user)
            );
    }

    public function supports(string $resource): bool
    {
        return Task::class === $resource;
    }

    private function whereAllowedAssignedTasks(QueryBuilder $queryBuilder): void
    {
        $alias = $queryBuilder->getRootAliases()[0];
        $exp = $queryBuilder->expr();

        $projectIds = $this->getAssignedProjectIds();
        $queryBuilder->innerJoin(\sprintf('%s.witcherProject', $alias), 'wp');

        if (\count($projectIds) > 0) {
            $queryBuilder->andWhere(
                $exp->orX(
                    'wp.createdBy = :witcherUserId',
                    \sprintf('%s.witcherProject IN(:projectIds)', $alias)
                )
            )
                ->setParameters([
                    'projectIds' => $projectIds,
                    'witcherUserId' => $this->witcherUserRepository->findIdByUser($this->user),
                ]);
        } else {
            $queryBuilder->andWhere('wp.createdBy = :witcherUserId')
                ->setParameter('witcherUserId', $this->witcherUserRepository->findIdByUser($this->user));
        }
    }

    /**
     * @return int[]
     */
    private function getAssignedProjectIds(): array
    {
        $userId = $this->user->getId();

        $projectIds = \array_unique(
            [
                ...$this->taskRepository->findAllAssignedToUserWitcherProjectIds($userId, false),
                ...$this->witcherProjectRepository->findAllAssignedToTeamProjectIds($userId, false),
            ]
        );

        \sort($projectIds);

        return $projectIds;
    }
}
