<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Integration\Application\IssueTracker\Jira\Repository;

use Jagaad\WitcherApi\Integration\Application\DTO\Request;
use Jagaad\WitcherApi\Integration\Application\Exception\IntegrationApiRequestException;
use Jagaad\WitcherApi\Integration\Application\Transformer\RequestTransformerInterface;
use Jagaad\WitcherApi\Integration\Domain\ApiClient\ApiClientInterface;
use Jagaad\WitcherApi\Integration\Domain\IssueTracker\PaginatedProjects;
use Jagaad\WitcherApi\Integration\Domain\IssueTracker\Project;
use Jagaad\WitcherApi\Integration\Repository\IssueTracker\ProjectReadApiInterface;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

final class ProjectReadApiRepository implements ProjectReadApiInterface
{
    private const URI = '/project/search';

    private RequestTransformerInterface $projectRequestTransformer;
    private ApiClientInterface $apiClient;

    public function __construct(
        ApiClientInterface $apiClient,
        RequestTransformerInterface $projectRequestTransformer
    ) {
        $this->projectRequestTransformer = $projectRequestTransformer;
        $this->apiClient = $apiClient;
    }

    /**
     * @throws IntegrationApiRequestException
     */
    public function getOneById(string $handle): Project
    {
        try {
            $result = $this->apiClient->request(
                \sprintf('%s/%s', self::URI, $handle),
                Project::class,
                null
            );

            if (!$result instanceof Project) {
                throw new \LogicException('Invalid type returned by API request');
            }

            return $result;
        } catch (\Throwable $exception) {
            throw IntegrationApiRequestException::create($exception->getMessage(), 0, $exception);
        }
    }

    /**
     * @throws IntegrationApiRequestException
     */
    public function getAllPaginated(Request $request): PaginatedProjects
    {
        try {
            $result = $this->apiClient->request(
                self::URI,
                PaginatedProjects::class,
                null,
                ['query' => $this->projectRequestTransformer->transform($request)],
                HttpRequest::METHOD_GET,
                false,
                true
            );

            if (!$result instanceof PaginatedProjects) {
                throw new \LogicException('Invalid type returned by API request');
            }

            return $result;
        } catch (\Throwable $exception) {
            throw IntegrationApiRequestException::create($exception->getMessage(), 0, $exception);
        }
    }
}
