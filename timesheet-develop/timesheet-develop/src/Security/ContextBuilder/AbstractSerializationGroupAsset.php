<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Security\ContextBuilder;

use Jagaad\UserProviderBundle\Security\Model\User;
use Jagaad\WitcherApi\Entity\Role;
use Symfony\Component\Security\Core\Security;

abstract class AbstractSerializationGroupAsset implements SerializationGroupAssetInterface
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function process(array &$groups): void
    {
        $user = $this->security->getUser();

        if (!$user instanceof User) {
            return;
        }

        if ($this->security->isGranted(Role::ADMIN)) {
            return;
        }

        $groupsForRemoval = $this->getGroupsForRemoval($user->getPermissions());

        if (0 === \count($groupsForRemoval)) {
            return;
        }

        foreach ($groups as $key => $group) {
            if (\in_array($group, $groupsForRemoval, true)) {
                unset($groups[$key]);
            }
        }
    }

    /**
     * @param string[] $permissions
     *
     * @return string[]
     */
    abstract protected function getGroupsForRemoval(array $permissions): array;
}
