<?php

declare(strict_types=1);

namespace Jagaad\UserApi\Exception;

class BaseJagaadUserException extends \Exception
{
    protected static string $defaultMessage = 'An internal error occurred.';

    final private function __construct(string $message = '', int $code = 0)
    {
        parent::__construct($message, $code);
    }

    public static function create(?string $message = null, int $code = 0): self
    {
        return new static($message ?? static::$defaultMessage, $code);
    }
}
