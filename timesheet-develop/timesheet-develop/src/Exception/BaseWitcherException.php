<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Exception;

class BaseWitcherException extends \Exception
{
    protected static string $defaultMessage = 'An internal error occurred.';

    final private function __construct(string $message = '', int $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public static function create(?string $message = null, int $code = 0, \Throwable $previous = null): \Throwable
    {
        return new static($message ?? static::$defaultMessage, $code, $previous);
    }
}
