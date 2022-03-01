<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Security;

interface AccessCheckerContextInterface
{
    /**
     * @param array<mixed> $originalData
     */
    public function hasAccess(object|string $resource, string $operation, array $originalData = []): bool;
}
