<?php

declare(strict_types=1);

namespace Jagaad\UserApi\Exception\Authentication;

use Jagaad\UserApi\Exception\UserApiException;

class IncompatibleEmailOwnerException extends UserApiException
{
    protected static string $defaultMessage = 'Provided google account belongs to a different user.';
}
