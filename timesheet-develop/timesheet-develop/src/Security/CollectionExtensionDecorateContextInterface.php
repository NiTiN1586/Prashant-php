<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Security;

use Doctrine\ORM\QueryBuilder;

interface CollectionExtensionDecorateContextInterface
{
    public function decorates(QueryBuilder $queryBuilder, string $resource): void;
}
