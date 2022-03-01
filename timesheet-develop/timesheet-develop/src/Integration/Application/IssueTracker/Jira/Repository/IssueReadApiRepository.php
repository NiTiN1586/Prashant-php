<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Integration\Application\IssueTracker\Jira\Repository;

use Jagaad\WitcherApi\Integration\Application\DTO\Request;
use Jagaad\WitcherApi\Integration\Application\Exception\IntegrationApiRequestException;
use Jagaad\WitcherApi\Integration\Application\Transformer\RequestTransformerInterface;
use Jagaad\WitcherApi\Integration\Domain\ApiClient\ApiClientInterface;
use Jagaad\WitcherApi\Integration\Domain\IssueTracker\IssueChangeLogHistorySearchResult;
use Jagaad\WitcherApi\Integration\Domain\IssueTracker\IssueSearchResult;
use Jagaad\WitcherApi\Integration\Repository\IssueTracker\IssueReadApiInterface;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

final class IssueReadApiRepository implements IssueReadApiInterface
{
    private RequestTransformerInterface $issueRequestTransformer;
    private RequestTransformerInterface $historyRequestTransformer;
    private ApiClientInterface $apiClient;

    public function __construct(
        ApiClientInterface $apiClient,
        RequestTransformerInterface $issueRequestTransformer,
        RequestTransformerInterface $historyRequestTransformer
    ) {
        $this->issueRequestTransformer = $issueRequestTransformer;
        $this->historyRequestTransformer = $historyRequestTransformer;
        $this->apiClient = $apiClient;
    }

    /**
     * @throws IntegrationApiRequestException
     */
    public function getByJql(Request $request): IssueSearchResult
    {
        if (null === $request->getRequestParam(Request::JQL_PARAM)) {
            throw new \InvalidArgumentException('Jql request parameter is required');
        }

        try {
            $result = $this->apiClient->request(
                '/search',
                IssueSearchResult::class,
                null,
                ['data' => \json_encode($this->issueRequestTransformer->transform($request), \JSON_THROW_ON_ERROR)],
                HttpRequest::METHOD_POST
            );

            if (!$result instanceof IssueSearchResult) {
                throw new \LogicException('Api returned incorrect type');
            }

            return $result;
        } catch (\Throwable $exception) {
            throw IntegrationApiRequestException::create($exception->getMessage(), 0, $exception);
        }
    }

    /**
     * @throws IntegrationApiRequestException
     */
    public function getChangeLog(Request $request): IssueChangeLogHistorySearchResult
    {
        try {
            $issueKey = $request->getRequestParam(Request::PATH_PARAM);

            if (null === $issueKey) {
                throw new \InvalidArgumentException('Issue key is required.');
            }

            $result = $this->apiClient->request(
                \sprintf('/issue/%s/changelog', $issueKey),
                IssueChangeLogHistorySearchResult::class,
                null,
                ['query' => $this->historyRequestTransformer->transform($request)],
            );

            if (!$result instanceof IssueChangeLogHistorySearchResult) {
                throw new \LogicException('Api returned incorrect type');
            }

            return $result;
        } catch (\Throwable $exception) {
            throw IntegrationApiRequestException::create($exception->getMessage(), 0, $exception);
        }
    }
}
