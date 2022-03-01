<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Integration\Application\IssueTracker\Jira\Repository;

use Jagaad\WitcherApi\Integration\Application\Exception\IntegrationApiRequestException;
use Jagaad\WitcherApi\Integration\Domain\ApiClient\ApiClientInterface;
use Jagaad\WitcherApi\Integration\Domain\IssueTracker\Board;
use Jagaad\WitcherApi\Integration\Domain\IssueTracker\BoardSearchResult;
use Jagaad\WitcherApi\Integration\Domain\IssueTracker\SprintSearchResult;
use Jagaad\WitcherApi\Integration\Repository\IssueTracker\BoardReadApiInterface;
use Webmozart\Assert\Assert;

final class BoardReadApiRepository implements BoardReadApiInterface
{
    private const API_URI = '/rest/agile/1.0';
    private const BOARD_URI = '/board';

    public function __construct(private ApiClientInterface $apiClient)
    {
    }

    public function getProjectBoards(string $projectKey, int $startAt = 0, int $maxResults = self::MAX_RESULTS): BoardSearchResult
    {
        try {
            /** @var BoardSearchResult $result */
            $result = $this->apiClient
                ->request(
                    self::BOARD_URI,
                    BoardSearchResult::class,
                    self::API_URI,
                    [
                        'query' => [
                            'projectKeyOrId' => $projectKey,
                            'startAt' => $startAt,
                            'maxResults' => $maxResults,
                        ],
                    ],
                );

            Assert::isInstanceOf($result, BoardSearchResult::class, 'Board API request failed');

            return $result;
        } catch (\Throwable $exception) {
            throw IntegrationApiRequestException::create($exception->getMessage(), $exception->getCode(), $exception);
        }
    }

    public function getBoardSprints(int $boardId, int $startAt = 0, int $maxResults = self::MAX_RESULTS): SprintSearchResult
    {
        try {
            /** @var SprintSearchResult $result */
            $result = $this->apiClient
                ->request(
                    \sprintf('%s/%d/sprint', self::BOARD_URI, $boardId),
                    SprintSearchResult::class,
                    self::API_URI,
                    [
                        'query' => [
                            'startAt' => $startAt,
                            'maxResults' => $maxResults,
                        ],
                    ],
                );

            Assert::isInstanceOf($result, SprintSearchResult::class, 'Sprint API request failed');

            return $result;
        } catch (\Throwable $exception) {
            throw IntegrationApiRequestException::create($exception->getMessage(), $exception->getCode(), $exception);
        }
    }

    public function getBoard(int $boardId): Board
    {
        try {
            /** @var Board $result */
            $result = $this->apiClient
                ->request(
                    \sprintf('%s/%d', self::BOARD_URI, $boardId),
                    Board::class,
                    self::API_URI
                );

            Assert::isInstanceOf($result, Board::class, 'Board API request failed');

            return $result;
        } catch (\Throwable $exception) {
            throw IntegrationApiRequestException::create($exception->getMessage(), $exception->getCode(), $exception);
        }
    }
}
