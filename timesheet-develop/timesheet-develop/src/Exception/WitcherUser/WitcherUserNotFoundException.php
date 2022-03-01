<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Exception\WitcherUser;

use Jagaad\WitcherApi\Exception\WitcherApiException;

class WitcherUserNotFoundException extends WitcherApiException
{
    protected static string $defaultMessage = 'Witcher user not found';
}
