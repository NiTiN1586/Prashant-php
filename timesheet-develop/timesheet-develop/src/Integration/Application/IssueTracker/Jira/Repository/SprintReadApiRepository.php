<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Integration\Application\IssueTracker\Jira\Repository;

use Jagaad\WitcherApi\Integration\Application\Exception\IntegrationApiRequestException;
use Jagaad\WitcherApi\Integration\Domain\ApiClient\ApiClientInterface;
use Jagaad\WitcherApi\Integration\Domain\IssueTracker\Sprint;
use Jagaad\WitcherApi\Integration\Repository\IssueTracker\SprintReadApiInterface;
use Webmozart\Assert\Assert;

final class SprintReadApiRepository implements SprintReadApiInterface
{
    private const API_URI = '/rest/agile/1.0';
    private const SPRINT_URI = '/sprint';

    public function __construct(private ApiClientInterface $apiClient)
    {
    }

    public function getSprint(int $sprintId): Sprint
    {
        try {
            /** @var Sprint $result */
            $result = $this->apiClient
                ->request(
                    \sprintf('%s/%d', self::SPRINT_URI, $sprintId),
                    Sprint::class,
                    self::API_URI
                );

            Assert::isInstanceOf($result, Sprint::class, 'Sprint API request failed');

            return $result;
        } catch (\Throwable $exception) {
            throw IntegrationApiRequestException::create($exception->getMessage(), $exception->getCode(), $exception);
        }
    }
}
