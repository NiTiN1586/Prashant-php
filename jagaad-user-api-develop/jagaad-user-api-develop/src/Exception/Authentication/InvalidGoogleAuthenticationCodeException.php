<?php

declare(strict_types=1);

namespace Jagaad\UserApi\Exception\Authentication;

use Jagaad\UserApi\Exception\UserApiException;

class InvalidGoogleAuthenticationCodeException extends UserApiException
{
    protected static string $defaultMessage = 'Google authentication code provided is not valid';
}
