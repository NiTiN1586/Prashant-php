<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Exception\WitcherUser;

use Jagaad\WitcherApi\Exception\BaseWitcherException;

final class WitherUserExistsException extends BaseWitcherException
{
    protected static string $defaultMessage = 'Witcher user already exists';
}
