<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Integration\Repository\IssueTracker;

interface SprintReadApiInterface
{
    /**
     * @return object
     */
    public function getSprint(int $sprintId): object;
}
