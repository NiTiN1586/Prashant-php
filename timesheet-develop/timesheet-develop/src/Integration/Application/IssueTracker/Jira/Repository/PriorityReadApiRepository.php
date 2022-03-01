<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Integration\Application\IssueTracker\Jira\Repository;

use Assert\Assertion;
use Jagaad\WitcherApi\Integration\Application\Exception\IntegrationApiRequestException;
use Jagaad\WitcherApi\Integration\Domain\ApiClient\ApiClientInterface;
use Jagaad\WitcherApi\Integration\Domain\IssueTracker\Priority;
use Jagaad\WitcherApi\Integration\Repository\IssueTracker\PriorityReadApiInterface;
use Symfony\Component\HttpFoundation\Request;

final class PriorityReadApiRepository implements PriorityReadApiInterface
{
    private ApiClientInterface $apiClient;

    public function __construct(ApiClientInterface $apiClient)
    {
        $this->apiClient = $apiClient;
    }

    /**
     * @return Priority[]
     *
     * @throws IntegrationApiRequestException
     */
    public function getAll(): array
    {
        try {
            /** @var Priority[] $priorities */
            $priorities = $this->apiClient->request(
                '/priority',
                Priority::class,
                null,
                [],
                Request::METHOD_GET,
                true
            );
            Assertion::allIsInstanceOf($priorities, Priority::class, 'Priority migration returned incorrect type');

            return $priorities;
        } catch (\Throwable $exception) {
            throw IntegrationApiRequestException::create($exception->getMessage(), 0, $exception);
        }
    }
}
