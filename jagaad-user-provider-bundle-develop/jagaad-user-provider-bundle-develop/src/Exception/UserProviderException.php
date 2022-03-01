<?php

declare(strict_types=1);

namespace Jagaad\UserProviderBundle\Exception;

class UserProviderException extends \Exception
{
    protected static $defaultMessage = 'An internal error occurred.';

    public static function create(?string $message = null, int $code = 0): self
    {
        return new static($message ?? static::$defaultMessage, $code);
    }
}
