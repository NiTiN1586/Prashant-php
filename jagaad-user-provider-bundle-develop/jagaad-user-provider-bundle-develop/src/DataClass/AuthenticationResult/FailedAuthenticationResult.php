<?php

declare(strict_types=1);

namespace Jagaad\UserProviderBundle\DataClass\AuthenticationResult;

use Symfony\Component\Security\Core\User\UserInterface;

class FailedAuthenticationResult implements AuthenticationResultInterface
{
    private string $authenticationExceptionMessage;
    
    public function __construct(\Exception $authenticationException)
    {
        $this->authenticationExceptionMessage = $authenticationException->getMessage();
    }

    public function isSuccessful(): bool
    {
        return false;
    }

    public function getAuthenticatedUser(): ?UserInterface
    {
        return null;
    }

    public function getAuthenticationExceptionMessage(): string
    {
        return $this->authenticationExceptionMessage;
    }
}
