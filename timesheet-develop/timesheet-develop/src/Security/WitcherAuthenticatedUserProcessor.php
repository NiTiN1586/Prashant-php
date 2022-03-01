<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Security;

use Jagaad\UserProviderBundle\Security\AuthenticatedUserProcessorInterface;
use Jagaad\UserProviderBundle\Security\Model\User;
use Jagaad\WitcherApi\Entity\Permission;
use Jagaad\WitcherApi\Entity\WitcherUser;
use Jagaad\WitcherApi\Exception\WitcherUser\WitcherUserNotFoundException;
use Jagaad\WitcherApi\Manager\WitcherUserManager;

class WitcherAuthenticatedUserProcessor implements AuthenticatedUserProcessorInterface
{
    private WitcherUserManager $witcherUserManager;

    public function __construct(WitcherUserManager $witcherUserManager)
    {
        $this->witcherUserManager = $witcherUserManager;
    }

    /**
     * @throws WitcherUserNotFoundException
     * @throws \Jagaad\WitcherApi\Exception\WitcherApiException
     */
    public function process(User $user): void
    {
        $witcherUser = $this->witcherUserManager->findWitcherUserByUser($user);

        if (!$witcherUser instanceof WitcherUser) {
            throw WitcherUserNotFoundException::create();
        }

        $user->setRoles([$witcherUser->getRole()->getHandle()]);
        $user->setPermissions(
            \array_map(
                static fn (Permission $permission) => $permission->getHandle(),
                $witcherUser->getRole()->getPermissions()
            ),
        );
    }
}
