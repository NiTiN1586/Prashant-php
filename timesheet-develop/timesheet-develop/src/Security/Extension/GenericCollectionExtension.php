<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Security\Extension;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use Doctrine\ORM\QueryBuilder;
use Jagaad\WitcherApi\Entity\Role;
use Jagaad\WitcherApi\Security\CollectionExtensionDecorateContextInterface;
use Symfony\Component\Security\Core\Security;

final class GenericCollectionExtension implements QueryCollectionExtensionInterface
{
    private CollectionExtensionDecorateContextInterface $collectionExtensionDecorateContext;
    private Security $security;

    public function __construct(
        CollectionExtensionDecorateContextInterface $collectionExtensionDecorateContext,
        Security $security
    ) {
        $this->collectionExtensionDecorateContext = $collectionExtensionDecorateContext;
        $this->security = $security;
    }

    public function applyToCollection(
        QueryBuilder $queryBuilder,
        QueryNameGeneratorInterface $queryNameGenerator,
        string $resourceClass,
        string $operationName = null
    ): void {
        if ($this->security->isGranted(Role::ADMIN)) {
            return;
        }

        $this->collectionExtensionDecorateContext->decorates($queryBuilder, $resourceClass);
    }
}
