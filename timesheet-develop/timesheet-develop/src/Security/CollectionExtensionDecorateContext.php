<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Security;

use Doctrine\ORM\QueryBuilder;
use Jagaad\WitcherApi\Entity\EntityAuthorInterface;
use Jagaad\WitcherApi\Exception\ResourceAccessDeniedException;
use Jagaad\WitcherApi\Security\Extension\CollectionExtensionQueryDecoratorInterface;
use Jagaad\WitcherApi\Utils\ClassHelper;

final class CollectionExtensionDecorateContext implements CollectionExtensionDecorateContextInterface
{
    /** @var iterable<CollectionExtensionQueryDecoratorInterface> */
    private iterable $queryDecorators;

    /**
     * @param iterable<CollectionExtensionQueryDecoratorInterface> $queryDecorators
     */
    public function __construct(iterable $queryDecorators)
    {
        $this->queryDecorators = $queryDecorators;
    }

    public function decorates(QueryBuilder $queryBuilder, string $resource): void
    {
        foreach ($this->queryDecorators as $queryDecorator) {
            if ($queryDecorator->supports($resource)) {
                $queryDecorator->process($queryBuilder, $resource);

                return;
            }
        }

        // For all resources with EntityAuthorInterface decorator should be created, to filter owner entities
        if (\is_subclass_of($resource, EntityAuthorInterface::class)) {
            throw ResourceAccessDeniedException::create(\sprintf('You don\'t have permissions to invoke operation on resource \'%s\'', ClassHelper::getClass($resource)));
        }
    }
}
