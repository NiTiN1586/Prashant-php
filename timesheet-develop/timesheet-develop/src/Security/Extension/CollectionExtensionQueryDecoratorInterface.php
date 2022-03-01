<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Security\Extension;

use Doctrine\ORM\QueryBuilder;

interface CollectionExtensionQueryDecoratorInterface
{
    public function process(QueryBuilder $queryBuilder, string $resource): void;

    public function supports(string $resource): bool;
}
