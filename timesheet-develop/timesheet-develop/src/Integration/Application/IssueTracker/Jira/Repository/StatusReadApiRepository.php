<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Integration\Application\IssueTracker\Jira\Repository;

use Assert\Assertion;
use Jagaad\WitcherApi\Integration\Application\Exception\IntegrationApiRequestException;
use Jagaad\WitcherApi\Integration\Domain\ApiClient\ApiClientInterface;
use Jagaad\WitcherApi\Integration\Domain\IssueTracker\Status;
use Jagaad\WitcherApi\Integration\Repository\IssueTracker\StatusReadApiInterface;
use Symfony\Component\HttpFoundation\Request;

final class StatusReadApiRepository implements StatusReadApiInterface
{
    private ApiClientInterface $apiClient;

    public function __construct(ApiClientInterface $apiClient)
    {
        $this->apiClient = $apiClient;
    }

    /**
     * @return Status[]
     *
     * @throws IntegrationApiRequestException
     */
    public function getAll(): array
    {
        try {
            /** @var Status[] $statuses */
            $statuses = $this->apiClient->request(
                '/status',
                Status::class,
                null,
                [],
                Request::METHOD_GET,
                true
            );
            Assertion::allIsInstanceOf($statuses, Status::class, 'Status API call returned unexpected response');

            return $statuses;
        } catch (\Throwable $exception) {
            throw IntegrationApiRequestException::create($exception->getMessage(), 0, $exception);
        }
    }
}
