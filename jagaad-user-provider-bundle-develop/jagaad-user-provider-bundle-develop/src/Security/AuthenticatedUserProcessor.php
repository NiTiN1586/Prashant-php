<?php

declare(strict_types=1);

namespace Jagaad\UserProviderBundle\Security;

use Jagaad\UserProviderBundle\Security\Model\User;

/**
 * Default authenticated user processor does no modifications to the user and performs no extra checks
 * Decorate the "jagaad.user_processor" service to provide a custom logic
 */
class AuthenticatedUserProcessor implements AuthenticatedUserProcessorInterface
{
    public function process(User $user): void
    {
    }
}
