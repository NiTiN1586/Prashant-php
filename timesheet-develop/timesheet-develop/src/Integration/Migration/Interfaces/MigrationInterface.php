<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Integration\Migration\Interfaces;

use Jagaad\WitcherApi\Integration\Application\DTO\Request;

interface MigrationInterface
{
    public const BATCH_SIZE = 20;

    public const MIGRATE_PROJECT = 'project';
    public const MIGRATE_ISSUE = 'issue';
    public const MIGRATE_ISSUE_TYPES = 'issueType';
    public const MIGRATE_STATUS = 'status';

    public const MIGRATE_HISTORY = 'history';
    public const MIGRATE_PRIORITY = 'priority';
    public const MIGRATE_BRANCHES = 'branch';
    public const MIGRATE_LABEL = 'label';

    public const MIGRATE_USER = 'user';
    public const MIGRATE_SPRINT = 'sprint';

    public const MIGRATION_PRIORITY = [
        self::MIGRATE_USER,
        self::MIGRATE_LABEL,
        self::MIGRATE_PRIORITY,
        self::MIGRATE_STATUS,
        self::MIGRATE_ISSUE_TYPES,
        self::MIGRATE_PROJECT,
        self::MIGRATE_SPRINT,
        self::MIGRATE_ISSUE,
        self::MIGRATE_HISTORY,
        self::MIGRATE_BRANCHES,
    ];

    public function migrate(Request $request): void;

    public function migrateAll(Request $request): void;

    public function getAlias(): string;

    public static function getPriority(): int;
}
