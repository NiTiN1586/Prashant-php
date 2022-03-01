<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Integration\Application\DTO;

class Request
{
    public const PATH_PARAM = 'pathParam';
    public const KEYS_PARAM = 'keys';
    public const JQL_PARAM = 'jql';

    private const MAX_RESULTS = 20;
    private const START_AT = 0;

    public int $startAt;
    public int $maxResults;

    /** @var array<string, mixed> */
    private array $requestParams;

    /**
     * @param array<string, mixed> $params
     */
    public function __construct(array $params = [], int $startAt = self::START_AT, int $maxResults = self::MAX_RESULTS)
    {
        $this->setRequestParams($params);
        $this->startAt = $startAt;
        $this->maxResults = $maxResults;
    }

    /**
     * @return array<string, mixed>
     */
    public function getRequestParams(): array
    {
        return $this->requestParams;
    }

    /**
     * @param mixed $default
     *
     * @return mixed
     */
    public function getRequestParam(string $name, $default = null)
    {
        return $this->requestParams[$name] ?? $default;
    }

    /**
     * @param array<string, mixed> $params
     */
    public function setRequestParams(array $params): self
    {
        foreach ($params as $name => $value) {
            $this->setRequestParam($name, $value);
        }

        return $this;
    }

    /**
     * @param mixed $value
     */
    public function setRequestParam(string $name, $value): self
    {
        $this->requestParams[$name] = $value;

        return $this;
    }

    public function resetRequestParams(bool $resetAll = false): self
    {
        if ($resetAll) {
            $this->startAt = self::START_AT;
        }

        $this->requestParams = [];

        return $this;
    }

    public function getStartAt(): int
    {
        return $this->startAt;
    }

    public function increaseStartAt(?int $count = null): self
    {
        if (null === $count || $count <= 0) {
            $this->startAt += self::MAX_RESULTS;

            return $this;
        }

        $this->startAt += $count;

        return $this;
    }

    public function resetPagination(): self
    {
        $this->startAt = self::START_AT;
        $this->maxResults = self::MAX_RESULTS;

        return $this;
    }

    public function getMaxResults(): int
    {
        return $this->maxResults;
    }

    public function setMaxResults(int $maxResults): self
    {
        $this->maxResults = $maxResults;

        return $this;
    }
}
