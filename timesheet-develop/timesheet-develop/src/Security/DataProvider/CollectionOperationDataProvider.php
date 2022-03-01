<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Security\DataProvider;

use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use Jagaad\WitcherApi\Exception\ResourceAccessDeniedException;
use Jagaad\WitcherApi\Security\AccessChecker\AccessCheckerInterface;
use Jagaad\WitcherApi\Security\AccessCheckerContextInterface;
use Jagaad\WitcherApi\Security\SupportedResourceInterface;
use Jagaad\WitcherApi\Utils\ClassHelper;
use Symfony\Component\HttpFoundation\Request;

final class CollectionOperationDataProvider implements ContextAwareCollectionDataProviderInterface, RestrictedDataProviderInterface
{
    private ContextAwareCollectionDataProviderInterface $collectionDataProvider;
    private SupportedResourceInterface $supportedResource;
    private AccessCheckerContextInterface $accessCheckerContext;

    public function __construct(
        ContextAwareCollectionDataProviderInterface $collectionDataProvider,
        SupportedResourceInterface $supportedResource,
        AccessCheckerContextInterface $accessCheckerContext
    ) {
        $this->collectionDataProvider = $collectionDataProvider;
        $this->supportedResource = $supportedResource;
        $this->accessCheckerContext = $accessCheckerContext;
    }

    /**
     * @param array<mixed> $context
     */
    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return $this->supportedResource->supports($resourceClass)
            && \in_array(
                \strtoupper($operationName),
                [
                    AccessCheckerInterface::ITEM_QUERY,
                    AccessCheckerInterface::COLLECTION_QUERY,
                    Request::METHOD_GET,
                ],
                true
            );
    }

    /**
     * @param array<mixed> $context
     *
     * @return iterable<int, object>
     */
    public function getCollection(string $resourceClass, string $operationName = null, array $context = []): iterable
    {
        $operation = $this->getOperationName($operationName, $context);

        if (!$this->accessCheckerContext->hasAccess($resourceClass, $operation)) {
            throw ResourceAccessDeniedException::create(\sprintf('You don\'t have permissions to invoke operation on resource %s', ClassHelper::getClass($resourceClass)));
        }

        return $this->collectionDataProvider->getCollection($resourceClass, $operationName, $context);
    }

    /**
     * @param array<mixed> $context
     */
    private function getOperationName(string $name, array $context = []): string
    {
        $operationType = $context['operation_type'] ?? null;

        if ('collection' === $operationType && Request::METHOD_GET === \strtoupper($name)) {
            return AccessCheckerInterface::COLLECTION_QUERY;
        }

        return $name;
    }
}
