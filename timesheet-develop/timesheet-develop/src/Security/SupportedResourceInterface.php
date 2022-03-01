<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Security;

interface SupportedResourceInterface
{
    public function supports(string|object $class): bool;
}
