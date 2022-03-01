<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Security\AccessChecker;

use Jagaad\WitcherApi\Security\Enum\Permission\Project;
use Jagaad\WitcherApi\Security\Enum\Permission\Task;
use Jagaad\WitcherApi\Security\ReadableResourceInterface;
use Symfony\Component\HttpFoundation\Request;

final class GenericResourceReadAccessChecker extends AbstractAccessChecker
{
    public function supports(object|string $resource): bool
    {
        return (new \ReflectionClass($resource))
            ->implementsInterface(ReadableResourceInterface::class);
    }

    /**
     * {@inheritDoc}
     */
    protected function supportsOperation(string $operation, object|string $resource, array $originalData = []): bool
    {
        return match ($operation) {
            self::ITEM_QUERY,
            self::COLLECTION_QUERY,
            Request::METHOD_GET => \in_array(Project::VIEW_ALL_PROJECTS->value, $this->permissions, true)
                || \in_array(Project::VIEW_ASSIGNED_PROJECTS->value, $this->permissions, true)
                || \in_array(Task::VIEW_ALL_TASKS->value, $this->permissions, true)
                || \in_array(Task::VIEW_ASSIGNED_TASKS->value, $this->permissions, true),

            default => false
        };
    }
}
