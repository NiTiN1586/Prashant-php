<?php

declare(strict_types=1);

namespace Jagaad\UserProviderBundle\DataClass\AuthenticationResult;

use Symfony\Component\Security\Core\User\UserInterface;

interface AuthenticationResultInterface
{
    public function isSuccessful(): bool;
    
    public function getAuthenticatedUser(): ?UserInterface;
}