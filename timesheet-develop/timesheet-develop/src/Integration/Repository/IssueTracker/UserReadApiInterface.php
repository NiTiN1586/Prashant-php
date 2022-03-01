<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Integration\Repository\IssueTracker;

interface UserReadApiInterface
{
    public function getUserByAccountId(string $accountId): object;

    /**
     * @return object[]
     */
    public function getAll(int $startAt = 0, int $maxResults = 20, bool $onlyActive = true): array;
}
