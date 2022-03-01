<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Cache;

final class CacheKeys
{
    public const USER_DATA_KEY = 'user_%d';
    public const WITCHER_USER_KEY_DATA = 'witcher_user_%d';
    public const USER_API_KEY_DATA = 'user_api_%s';
    public const USER_DATA_KEY_EXPIRE_1_HOUR = 3600;
    public const USER_DATA_KEY_EXPIRE_10_MINUTES = 600;

    public const EXPIRATION_TTL_IN_SECONDS = 'EX';
}
