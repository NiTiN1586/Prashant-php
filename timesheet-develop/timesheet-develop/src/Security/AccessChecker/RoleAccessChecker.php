<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Security\AccessChecker;

use Jagaad\WitcherApi\Entity\Permission;
use Jagaad\WitcherApi\Entity\PermissionGroup;
use Jagaad\WitcherApi\Entity\Role;
use Jagaad\WitcherApi\Security\Enum\Permission\Role as EnumRole;
use Symfony\Component\HttpFoundation\Request;

class RoleAccessChecker extends AbstractAccessChecker
{
    /**
     * {@inheritdoc}
     */
    public function supports(object|string $resource): bool
    {
        return $resource instanceof Role
            || $resource instanceof Permission
            || $resource instanceof PermissionGroup
            || \in_array($resource, [Role::class, Permission::class, PermissionGroup::class], true);
    }

    /**
     * {@inheritDoc}
     */
    protected function supportsOperation(string $operation, object|string $resource, array $originalData = []): bool
    {
        return match ($operation) {
            self::ITEM_QUERY,
            self::COLLECTION_QUERY,
            Request::METHOD_GET => true,

            self::CREATE,
            self::UPDATE,
            Request::METHOD_POST,
            Request::METHOD_PATCH => \in_array(EnumRole::MANAGE_ROLES->value, $this->permissions, true),
            default => false,
        };
    }
}
