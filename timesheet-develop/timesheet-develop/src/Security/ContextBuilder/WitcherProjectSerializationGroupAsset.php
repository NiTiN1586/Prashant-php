<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Security\ContextBuilder;

use Jagaad\WitcherApi\Entity\WitcherProjectTrackerTaskType;
use Jagaad\WitcherApi\Enum\ContextGroup;
use Jagaad\WitcherApi\Security\Enum\Permission\Project;

final class WitcherProjectSerializationGroupAsset extends AbstractSerializationGroupAsset
{
    public function support(string $resource): bool
    {
        return WitcherProjectTrackerTaskType::class === $resource;
    }

    /**
     * {@inheritdoc}
     */
    protected function getGroupsForRemoval(array $permissions): array
    {
        $groupsForRemoval = [];

        if (
            !\in_array(Project::VIEW_ALL_PROJECTS->value, $permissions, true)
            && !\in_array(Project::VIEW_ASSIGNED_PROJECTS->value, $permissions, true)
        ) {
            $groupsForRemoval[] = ContextGroup::GROUP_PROJECT_READ;
        }

        return $groupsForRemoval;
    }
}
