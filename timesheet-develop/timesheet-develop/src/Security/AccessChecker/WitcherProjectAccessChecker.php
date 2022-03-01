<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Security\AccessChecker;

use Jagaad\WitcherApi\Entity\GitProject;
use Jagaad\WitcherApi\Entity\Sprint;
use Jagaad\WitcherApi\Entity\WitcherProject;
use Jagaad\WitcherApi\Repository\TaskRepository;
use Jagaad\WitcherApi\Repository\WitcherProjectRepository;
use Jagaad\WitcherApi\Security\Enum\Permission\Project;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;

final class WitcherProjectAccessChecker extends AbstractAccessChecker
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
        return $resource instanceof WitcherProject
            || $resource instanceof GitProject
            || \in_array($resource, [WitcherProject::class, GitProject::class, Sprint::class], true);
    }

    /**
     * {@inheritDoc}
     */
    protected function supportsOperation(string $operation, object|string $resource, array $originalData = []): bool
    {
        $witcherProject = $resource;

        if ($resource instanceof GitProject || $resource instanceof Sprint) {
            $resource = $resource->getWitcherProject();
        }

        return match ($operation) {
            self::CREATE,
            Request::METHOD_POST => \in_array(Project::CREATE_PROJECTS->value, $this->permissions, true),

            self::UPDATE,
            Request::METHOD_PATCH => \in_array(Project::UPDATE_ALL_PROJECTS->value, $this->permissions, true)
                || ($resource instanceof $witcherProject && $this->supportsUpdateOperation($resource)),

            self::COLLECTION_QUERY,
            self::ITEM_QUERY,
            Request::METHOD_GET => \in_array(Project::VIEW_ALL_PROJECTS->value, $this->permissions, true)
                || (!$resource instanceof WitcherProject && \in_array(Project::VIEW_ASSIGNED_PROJECTS->value, $this->permissions, true))
                || ($resource instanceof WitcherProject && $this->supportsViewOperation($resource)),

            Request::METHOD_DELETE => \in_array(Project::DELETE_ALL_PROJECTS->value, $this->permissions, true)
                || ($resource instanceof $witcherProject && $this->supportsDeleteOperation($resource)),

            default => false
        };
    }

    private function supportsViewOperation(WitcherProject $witcherProject): bool
    {
        if (!\in_array(Project::VIEW_ASSIGNED_PROJECTS->value, $this->permissions, true)) {
            return false;
        }

        $hasAssignedTasks = $this->taskRepository->hasAssignedTasks($witcherProject->getId(), $this->user->getId(), false);
        $isAssignedToProjectTeam = $this->witcherProjectRepository->isAssignedToWitcherProjectTeam(
            $witcherProject->getId(),
            $this->user->getId()
        );

        return $witcherProject->getCreatedByUserId() === $this->user->getId()
            || $hasAssignedTasks
            || $isAssignedToProjectTeam;
    }

    private function supportsUpdateOperation(WitcherProject $witcherProject): bool
    {
        if (!\in_array(Project::UPDATE_ASSIGNED_PROJECTS->value, $this->permissions, true)) {
            return false;
        }

        return $witcherProject->getCreatedByUserId() === $this->user->getId();
    }

    private function supportsDeleteOperation(WitcherProject $witcherProject): bool
    {
        if (!\in_array(Project::DELETE_ASSIGNED_PROJECTS->value, $this->permissions, true)) {
            return false;
        }

        return $witcherProject->getCreatedByUserId() === $this->user->getId();
    }
}
