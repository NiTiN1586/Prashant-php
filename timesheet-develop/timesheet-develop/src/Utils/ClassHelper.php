<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Utils;

final class ClassHelper
{
    public static function getClass(object|string $resource): string
    {
        $parts = \explode('\\', \is_string($resource) ? $resource : \get_class($resource));

        return \array_pop($parts);
    }
}
