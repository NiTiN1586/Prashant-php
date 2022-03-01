<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Security\ContextBuilder;

use Jagaad\WitcherApi\Entity\Activity;
use Jagaad\WitcherApi\Entity\Comment;
use Jagaad\WitcherApi\Entity\Label;
use Jagaad\WitcherApi\Entity\WitcherProject;
use Jagaad\WitcherApi\Enum\ContextGroup;
use Jagaad\WitcherApi\Security\Enum\Permission\Task;

final class TaskSerializationGroupAsset extends AbstractSerializationGroupAsset
{
    public function support(string $resource): bool
    {
        return \in_array(
            $resource,
            [
                WitcherProject::class,
                Comment::class,
                Activity::class,
                Label::class,
            ],
            true
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function getGroupsForRemoval(array $permissions): array
    {
        $groupsForRemoval = [];

        if (
            !\in_array(Task::VIEW_ALL_TASKS->value, $permissions, true)
            && !\in_array(Task::VIEW_ASSIGNED_TASKS->value, $permissions, true)
        ) {
            $groupsForRemoval[] = ContextGroup::GROUP_TASK_READ;
        }

        return $groupsForRemoval;
    }
}
