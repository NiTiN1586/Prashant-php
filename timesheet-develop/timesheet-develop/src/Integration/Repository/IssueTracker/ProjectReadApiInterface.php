<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Integration\Repository\IssueTracker;

use Jagaad\WitcherApi\Integration\Application\DTO\Request;

interface ProjectReadApiInterface
{
    public function getOneById(string $handle): object;

    public function getAllPaginated(Request $request): object;
}
