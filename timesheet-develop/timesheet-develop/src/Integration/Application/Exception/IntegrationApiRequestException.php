<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Integration\Application\Exception;

final class IntegrationApiRequestException extends IntegrationException
{
    protected static string $defaultMessage = 'API Request error occurred.';
}
