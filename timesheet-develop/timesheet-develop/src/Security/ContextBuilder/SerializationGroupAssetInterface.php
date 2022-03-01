<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Security\ContextBuilder;

interface SerializationGroupAssetInterface
{
    public function support(string $resource): bool;

    /**
     * @param string[] $groups
     */
    public function process(array &$groups): void;
}
