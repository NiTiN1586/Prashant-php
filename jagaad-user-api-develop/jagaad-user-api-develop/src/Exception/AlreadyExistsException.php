<?php

declare(strict_types=1);

namespace Jagaad\UserApi\Exception;

final class AlreadyExistsException extends BaseJagaadUserException
{
    protected static string $defaultMessage = 'Processed item already exists.';
}
