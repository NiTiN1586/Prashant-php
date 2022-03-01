<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Integration\Domain\Exception;

use Jagaad\WitcherApi\Exception\BaseWitcherException;

final class IssuesTrackerException extends BaseWitcherException
{
    protected static string $defaultMessage = 'Issue Tracker exception occurred.';
}
