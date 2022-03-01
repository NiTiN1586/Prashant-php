<?php

declare(strict_types=1);

namespace Jagaad\UserProviderBundle\Security;

use Jagaad\UserProviderBundle\Security\Model\User;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserProvider implements UserProviderInterface
{
    public function loadUserByUsername(string $username): UserInterface
    {
        return new User();
    }

    public function refreshUser(UserInterface $user): UserInterface
    {
        return $user;
    }

    public function supportsClass(string $class)
    {
        return User::class === $class;
    }
}
