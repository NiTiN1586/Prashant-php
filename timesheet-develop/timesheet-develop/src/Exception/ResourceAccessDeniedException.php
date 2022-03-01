<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Exception;

use GraphQL\Error\ClientAware;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

final class ResourceAccessDeniedException extends AccessDeniedException implements ClientAware
{
    private function __construct(string $message = '', \Throwable $previous = null)
    {
        parent::__construct($message, $previous);
    }

    public static function create(?string $message = null, \Throwable $previous = null): \Throwable
    {
        return new self($message ?? 'Access denied to specified resource.', $previous);
    }

    public function isClientSafe(): bool
    {
        return true;
    }

    public function getCategory(): string
    {
        return 'forbidden';
    }
}
