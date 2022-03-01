<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Integration\Repository\IssueTracker;

interface BoardReadApiInterface
{
    public const MAX_RESULTS = 20;

    /**
     * @return object
     */
    public function getProjectBoards(string $projectKey, int $startAt = 0, int $maxResults = self::MAX_RESULTS): object;

    /**
     * @return object
     */
    public function getBoardSprints(int $boardId, int $startAt = 0, int $maxResults = self::MAX_RESULTS): object;

    /**
     * @return object
     */
    public function getBoard(int $boardId): object;
}
