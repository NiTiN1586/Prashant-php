<?php

declare(strict_types=1);

namespace Jagaad\UserProviderBundle\Security\Model\Interfaces;

use Symfony\Component\Security\Core\User\UserInterface;

interface ExtendedUserInterface extends UserInterface
{
    public function getPermissions(): array;
}
