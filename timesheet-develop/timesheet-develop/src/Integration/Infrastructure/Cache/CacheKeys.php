<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Integration\Infrastructure\Cache;

final class CacheKeys
{
    public const BRANCH_DATA_KEY = 'branch_%d_%s_%s';
    public const PROJECT_BRANCH_DATA_KEY = 'branch_%s_%s';
    public const INTEGRATION_DATA_KEY_EXPIRE_1_HOUR = 3600;
    public const USER_DATA_KEY_EXPIRE_10_MINUTES = 600;
}
