<?php

declare(strict_types=1);

namespace Jagaad\UserApi\Exception\Authentication;

use Jagaad\UserApi\Exception\UserApiException;

class MissingGoogleAuthenticationCodeException extends UserApiException
{
    protected static string $defaultMessage = 'Google authentication code is missing in callback request query';
}
