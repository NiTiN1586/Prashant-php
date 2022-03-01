<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Integration\Application\IssueTracker\Jira\ApiClient;

use Jagaad\WitcherApi\Integration\Domain\ApiClient\ApiClientInterface;
use JiraRestApi\JiraClient;
use Symfony\Component\HttpFoundation\Request;

class JiraApiClient implements ApiClientInterface
{
    private JiraClient $jiraClient;
    private \JsonMapper $jsonMapper;

    public function __construct(JiraClient $jiraClient, \JsonMapper $jsonMapper)
    {
        $this->jiraClient = $jiraClient;
        $this->jsonMapper = $jsonMapper;
    }

    /**
     * {@inheritdoc}
     */
    public function request(
        string $requestUri,
        string $type,
        ?string $apiUri = null,
        array $params = [],
        string $method = Request::METHOD_GET,
        bool $mapArray = false,
        bool $listAsQueryString = false
    ): object|array {
        $this->setAPIUri($apiUri);

        if (!\in_array($method, [Request::METHOD_POST, Request::METHOD_GET], true)) {
            throw new \InvalidArgumentException(\sprintf('Only %s request methods are allowed', \implode(',', [Request::METHOD_GET,  Request::METHOD_POST])));
        }

        $response = null;

        if (Request::METHOD_GET === $method) {
            $query = $listAsQueryString
                ? $this->toHttpQueryParameter($params['query'] ?? [])
                : $this->jiraClient->toHttpQueryParameter($params['query'] ?? []);

            $response = $this->jiraClient->exec(\sprintf('%s%s', $requestUri, $query));
        }

        if (Request::METHOD_POST === $method) {
            $response = $this->jiraClient->exec($requestUri, $params['data'] ?? [], $method);
        }

        if (!\is_string($response)) {
            throw new \LogicException('Invalid response type returned');
        }

        $response = \json_decode($response, false, 512, \JSON_THROW_ON_ERROR);

        if ($mapArray) {
            $data = $this->jsonMapper->mapArray($response, [], $type);

            if (!\is_array($data)) {
                throw new \LogicException('Invalid mapped type returned');
            }

            return $data;
        }

        $data = $this->jsonMapper->map($response, new $type());

        if (!\is_object($data)) {
            throw new \LogicException('Invalid mapped type returned');
        }

        return $data;
    }

    /**
     * @param array<mixed> $paramArray
     */
    private function toHttpQueryParameter(array $paramArray): string
    {
        $queryParam = '?';

        foreach ($paramArray as $key => $value) {
            if (\is_array($value)) {
                $queryParam .= $this->normalizeArrayQueryParams($key, $value);

                continue;
            }

            $queryParam .= $key.'='.$value.'&';
        }

        return $queryParam;
    }

    /**
     * @param array<int, string> $items
     */
    private function normalizeArrayQueryParams(string $key, array $items): string
    {
        $queryParam = '';

        foreach ($items as $item) {
            $queryParam .= $key.'='.$item.'&';
        }

        return $queryParam;
    }

    private function setAPIUri(?string $apiUri = null): self
    {
        if (null === $apiUri) {
            $this->jiraClient->setRestApiV3();

            return $this;
        }

        $this->jiraClient->setAPIUri($apiUri);

        return $this;
    }
}
