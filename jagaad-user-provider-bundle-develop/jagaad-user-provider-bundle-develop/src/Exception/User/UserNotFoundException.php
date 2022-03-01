<?php

declare(strict_types=1);

namespace Jagaad\UserProviderBundle\Exception\User;

use Jagaad\UserProviderBundle\Exception\UserProviderException;

class UserNotFoundException extends UserProviderException
{
    protected static $defaultMessage = 'Failed to find requested user';
}
