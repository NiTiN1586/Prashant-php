<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Integration\Application\Exception;

use Jagaad\WitcherApi\Exception\BaseWitcherException;

final class DataProviderException extends BaseWitcherException
{
    protected static string $defaultMessage = 'Data can\'t be retrieved due to error.';
}
