<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Integration\Repository\IssueTracker;

interface LabelReadApiInterface
{
    public function getAllPaginated(int $current = 0, int $maxItems = 20): object;
}
