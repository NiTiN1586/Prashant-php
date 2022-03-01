<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\QueryFilter;

interface QueryFilterInterface
{
    public function filter(string $resource): void;
}
