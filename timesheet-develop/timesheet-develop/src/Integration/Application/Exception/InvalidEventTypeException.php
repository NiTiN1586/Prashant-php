<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Integration\Application\Exception;

use Jagaad\WitcherApi\Exception\BaseWitcherException;

final class InvalidEventTypeException extends BaseWitcherException
{
    protected static string $defaultMessage = 'Invalid Event Type Passed.';
}
