<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\UserCreator\Questionnaire\Cache;

final class CacheKeys
{
    public const USER_DATA_KEY = 'user_questionnaire_%s';
    public const JIRA_USER_DATA_KEY = 'jira_user_%s';
    public const GITLAB_USER_DATA_KEY = 'gitlab_user_%d';
    public const USER_DATA_KEY_EXPIRE = 3600;
}
