<?php

declare(strict_types=1);

namespace Jagaad\UserProviderBundle\Enum;

final class ApiEndpoint
{
    public const URL_TEMPLATE_GET_AUTHENTICATION_URL = '%s/api/auth/google/auth-url';
    public const URL_TEMPLATE_AUTHENTICATE_USER = '%s/api/auth/google/authenticate-user';
    public const URL_TEMPLATE_GET_USER_BY_ID = '%s/api/users%s';
    public const BASE_URL_TEMPLATE_POST_USER = '%s/api/users';
}
