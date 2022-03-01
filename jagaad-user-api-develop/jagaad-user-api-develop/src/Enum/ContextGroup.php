<?php

declare(strict_types=1);

namespace Jagaad\UserApi\Enum;

class ContextGroup
{
    public const USER_READ = 'USER_READ';
    public const USER_WRITE = 'USER_WRITE';
    public const USER_PROFILE_READ = 'USER_PROFILE_READ';
    public const USER_PROFILE_WRITE = 'USER_PROFILE_WRITE';
    public const GOOGLE_ACCOUNT_READ = 'GOOGLE_ACCOUNT_READ';
    public const GOOGLE_ACCOUNT_WRITE = 'GOOGLE_ACCOUNT_WRITE';
    public const USER_ADDRESS_READ = 'USER_ADDRESS_READ';
    public const USER_ADDRESS_WRITE = 'USER_ADDRESS_WRITE';
}
