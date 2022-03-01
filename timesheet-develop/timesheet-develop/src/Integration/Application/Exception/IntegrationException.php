<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Integration\Application\Exception;

use Jagaad\WitcherApi\Exception\BaseWitcherException;

class IntegrationException extends BaseWitcherException
{
    protected static string $defaultMessage = 'Integration internal error error exception.';
}
