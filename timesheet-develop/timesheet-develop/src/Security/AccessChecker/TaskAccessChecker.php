<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Security\AccessChecker;

use Jagaad\WitcherApi\Entity\Task;
use Jagaad\WitcherApi\Entity\WitcherProject;
use Jagaad\WitcherApi\Entity\WitcherUser;
use Jagaad\WitcherApi\Repository\TaskRepository;
use Jagaad\WitcherApi\Repository\WitcherProjectRepository;
use Jagaad\WitcherApi\Security\Enum\Permission\Project;
use Jagaad\WitcherApi\Security\Enum\Permission\Task as EnumTask;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;

final class TaskAccessChecker extends AbstractAccessChecker
{
    private TaskRepository $taskRepository;
    private WitcherProjectRepository $witcherProjectRepository;

    public function __construct(Security $security, TaskRepository $taskRepository, WitcherProjectRepository $witcherProjectRepository)
    {
        parent::__construct($security);
        $this->taskRepository = $taskRepository;
        $this->witcherProjectRepository = $witcherProjectRepository;
    }

    public function supports(object|string $resource): bool
    {
        return $resource instanceof Task || Task::class === $resource;
    }

    /**
     * {@inheritDoc}
     */
    protected function supportsOperation(string $operation, object|string $resource, array $originalData = []): bool
    {
        return match ($operation) {
            self::CREATE,
            Request::METHOD_POST => $resource instanceof Task && $this->supportsCreateOperation($resource),

            self::UPDATE,
            Request::METHOD_PATCH => (
                \in_array(Project::UPDATE_ALL_PROJECTS->value, $this->permissions, true)
                && \in_array(EnumTask::UPDATE_ALL_TASKS->value, $this->permissions, true)
            ) || ($resource instanceof Task && $this->supportsUpdateOperation($resource, $originalData)),

            self::COLLECTION_QUERY,
            self::ITEM_QUERY,
            Request::METHOD_GET => \in_array(EnumTask::VIEW_ALL_TASKS->value, $this->permissions, true)
                || ($resource instanceof Task && $this->supportsViewOperation($resource))
                || (!$resource instanceof Task && \in_array(EnumTask::VIEW_ASSIGNED_TASKS->value, $this->permissions, true)),

            Request::METHOD_DELETE => (
                    \in_array(Project::DELETE_ALL_PROJECTS->value, $this->permissions, true)
                    && \in_array(EnumTask::DELETE_ALL_TASKS->value, $this->permissions, true)
                ) || ($resource instanceof Task && $this->supportsDeleteOperation($resource)),

            default => false
        };
    }

    private function supportsCreateOperation(Task $task): bool
    {
        return \in_array(EnumTask::CREATE_TASKS->value, $this->permissions, true)
            && $this->isOperationAvailableForUser($task->getWitcherProject());
    }

    /**
     * @param array<mixed> $originalData
     */
    private function supportsUpdateOperation(Task $task, array $originalData): bool
    {
        $witcherProject = $originalData['witcherProject'] ?? null;
        $createdBy = $originalData['createdBy'] ?? null;

        if (!$createdBy instanceof WitcherUser || !$witcherProject instanceof WitcherProject) {
            return false;
        }

        if (\in_array(Project::UPDATE_ASSIGNED_PROJECTS->value, $this->permissions, true)
            && \in_array(EnumTask::UPDATE_ALL_TASKS->value, $this->permissions, true)
        ) {
            return $this->isOperationAvailableForUser($witcherProject);
        }

        if (\in_array(EnumTask::UPDATE_ASSIGNED_TASKS->value, $this->permissions, true)) {
            return $task->isAvailableFor($this->user->getId());
        }

        return false;
    }

    private function supportsViewOperation(Task $task): bool
    {
        if (\in_array(Project::VIEW_ALL_PROJECTS->value, $this->permissions, true)
            && \in_array(EnumTask::VIEW_ALL_TASKS->value, $this->permissions, true)
        ) {
            return $this->isOperationAvailableForUser($task->getWitcherProject());
        }

        if (\in_array(EnumTask::VIEW_ASSIGNED_TASKS->value, $this->permissions, true)) {
            return $task->isAvailableFor($this->user->getId());
        }

        return false;
    }

    private function supportsDeleteOperation(Task $task): bool
    {
        if (\in_array(Project::DELETE_ASSIGNED_PROJECTS->value, $this->permissions, true)
            && \in_array(EnumTask::DELETE_ALL_TASKS->value, $this->permissions, true)
        ) {
            return $this->isOperationAvailableForUser($task->getWitcherProject());
        }

        if (\in_array(EnumTask::DELETE_ASSIGNED_TASKS->value, $this->permissions, true)) {
            return $task->isAvailableFor($this->user->getId());
        }

        return false;
    }

    private function isOperationAvailableForUser(WitcherProject $witcherProject): bool
    {
        return $witcherProject->getCreatedByUserId() === $this->user->getId()
            || $this->taskRepository->hasAssignedTasks($witcherProject->getId(), $this->user->getId(), false)
            || $this->witcherProjectRepository->isAssignedToWitcherProjectTeam($witcherProject->getId(), $this->user->getId());
    }
}
