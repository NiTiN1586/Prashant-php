<?php

declare(strict_types=1);

namespace Jagaad\UserApi\Exception\Authentication;

use Jagaad\UserApi\Exception\UserApiException;

class MissingUserEmailException extends UserApiException
{
    protected static string $defaultMessage = 'Google account email address is missing';
}
