<?php

declare(strict_types=1);

namespace Jagaad\UserProviderBundle\Exception\Authentication;

use Jagaad\UserProviderBundle\Exception\UserProviderException;

class UserProviderAuthenticationException extends UserProviderException
{
    protected static $defaultMessage = 'An authentication error occurred.';
}
