<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Integration\Repository\IssueTracker;

interface IssueTypeReadApiInterface
{
    /**
     * @return object[]
     */
    public function findIssuesTypesByProjectId(int $projectId): array;

    /**
     * @return object[]
     */
    public function findAll(): array;
}
