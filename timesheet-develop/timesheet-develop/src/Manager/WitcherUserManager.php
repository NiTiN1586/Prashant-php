<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Manager;

use Jagaad\UserProviderBundle\Security\Model\User;
use Jagaad\WitcherApi\Entity\WitcherUser;
use Jagaad\WitcherApi\Repository\WitcherUserRepository;

class WitcherUserManager
{
    private WitcherUserRepository $witcherUserRepository;

    public function __construct(WitcherUserRepository $witcherUserRepository)
    {
        $this->witcherUserRepository = $witcherUserRepository;
    }

    public function findWitcherUserByUser(User $user): ?WitcherUser
    {
        return $this->witcherUserRepository
            ->findWitcherUserByUserId($user->getId());
    }
}
