<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Integration\Application\Exception;

use Jagaad\WitcherApi\Exception\BaseWitcherException;

final class ValidationException extends BaseWitcherException
{
    protected static string $defaultMessage = 'Validation exception occurred.';
}
