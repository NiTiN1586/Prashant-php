<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Integration\Application\IssueTracker\Jira\Repository;

use Assert\Assertion;
use Jagaad\WitcherApi\Integration\Application\Exception\IntegrationApiRequestException;
use Jagaad\WitcherApi\Integration\Domain\ApiClient\ApiClientInterface;
use Jagaad\WitcherApi\Integration\Domain\IssueTracker\IssueType;
use Jagaad\WitcherApi\Integration\Repository\IssueTracker\IssueTypeReadApiInterface;
use Symfony\Component\HttpFoundation\Request;

final class IssueTypeReadApiRepository implements IssueTypeReadApiInterface
{
    private ApiClientInterface $apiClient;

    public function __construct(ApiClientInterface $apiClient)
    {
        $this->apiClient = $apiClient;
    }

    /**
     * @return IssueType[]
     *
     * @throws IntegrationApiRequestException
     */
    public function findIssuesTypesByProjectId(int $projectId): array
    {
        try {
            /** @var IssueType[] $projectIssueTypes */
            $projectIssueTypes = $this->apiClient->request(
                '/issuetype/project',
                IssueType::class,
                null,
                ['projectId' => $projectId],
                Request::METHOD_GET,
                true
            );

            Assertion::allIsInstanceOf(
                $projectIssueTypes,
                IssueType::class,
                'Issue Type migration returned incorrect type'
            );

            return $projectIssueTypes;
        } catch (\Throwable $exception) {
            throw IntegrationApiRequestException::create($exception->getMessage(), 0, $exception);
        }
    }

    /**
     * @return IssueType[]
     *
     * @throws IntegrationApiRequestException
     */
    public function findAll(): array
    {
        try {
            /** @var IssueType[] $issueTypes */
            $issueTypes = $this->apiClient->request(
                '/issuetype',
                IssueType::class,
                null,
                [],
                Request::METHOD_GET,
                true
            );

            Assertion::allIsInstanceOf(
                $issueTypes,
                IssueType::class,
                'Issue Type migration returned incorrect type'
            );

            return $issueTypes;
        } catch (\Throwable $exception) {
            throw IntegrationApiRequestException::create($exception->getMessage(), 0, $exception);
        }
    }
}
