<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Integration\Repository\IssueTracker;

interface StatusReadApiInterface
{
    /**
     * @return object[]
     */
    public function getAll(): array;
}
