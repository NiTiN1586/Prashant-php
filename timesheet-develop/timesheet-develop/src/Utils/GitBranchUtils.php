<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Utils;

final class GitBranchUtils
{
    public const BRANCH_PATTERN = '/^([a-zA-Z]+-\d+)/';

    public static function getBranchNameFromPattern(string $name): ?string
    {
        $matcher = [];

        \preg_match(self::BRANCH_PATTERN, $name, $matcher);

        return $matcher[0] ?? null;
    }
}
