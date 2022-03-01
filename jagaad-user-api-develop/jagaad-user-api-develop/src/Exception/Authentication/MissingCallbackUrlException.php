<?php

declare(strict_types=1);

namespace Jagaad\UserApi\Exception\Authentication;

use Jagaad\UserApi\Exception\UserApiException;

class MissingCallbackUrlException extends UserApiException
{
    protected static string $defaultMessage = 'Callback URL for google authentication is missing';
}
