<?php

declare(strict_types=1);

namespace Jagaad\UserProviderBundle\DataClass\AuthenticationResult;

use Symfony\Component\Security\Core\User\UserInterface;

class SuccessfulAuthenticationResult implements AuthenticationResultInterface
{
    private UserInterface $authenticatedUser;
    
    public function __construct(UserInterface $authenticatedUser)
    {
        $this->authenticatedUser = $authenticatedUser;
    }

    public function isSuccessful(): bool
    {
        return true;
    }

    public function getAuthenticatedUser(): ?UserInterface
    {
        return $this->authenticatedUser;
    }
}
