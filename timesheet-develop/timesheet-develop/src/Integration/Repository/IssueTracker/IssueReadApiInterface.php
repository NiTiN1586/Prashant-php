<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Integration\Repository\IssueTracker;

use Jagaad\WitcherApi\Integration\Application\DTO\Request;

interface IssueReadApiInterface
{
    public function getByJql(Request $request): object;

    public function getChangeLog(Request $request): object;
}
