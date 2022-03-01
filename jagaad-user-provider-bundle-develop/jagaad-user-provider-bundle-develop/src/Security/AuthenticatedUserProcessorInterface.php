<?php

namespace Jagaad\UserProviderBundle\Security;

use Jagaad\UserProviderBundle\Security\Model\User;

interface AuthenticatedUserProcessorInterface
{
    public function process(User $user): void;
}
