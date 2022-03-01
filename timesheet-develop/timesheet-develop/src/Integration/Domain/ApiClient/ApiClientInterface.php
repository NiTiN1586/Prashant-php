<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Integration\Domain\ApiClient;

use Symfony\Component\HttpFoundation\Request;

interface ApiClientInterface
{
    /**
     * @param array<string, mixed> $params
     *
     * @return object[]|object
     */
    public function request(
        string $requestUri,
        string $type,
        ?string $apiUri = null,
        array $params = [],
        string $method = Request::METHOD_GET,
        bool $mapArray = false,
        bool $listAsQueryString = false
    ): object|array;
}
