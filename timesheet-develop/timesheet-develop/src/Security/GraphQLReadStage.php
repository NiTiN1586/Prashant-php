<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Security;

use ApiPlatform\Core\GraphQl\Resolver\Stage\ReadStageInterface;
use Jagaad\WitcherApi\QueryFilter\QueryFilterInterface;
use Jagaad\WitcherApi\Security\AccessChecker\AccessCheckerInterface;
use Jagaad\WitcherApi\Security\Event\GraphQLEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

final class GraphQLReadStage implements ReadStageInterface
{
    public function __construct(
        private ReadStageInterface $readStage,
        private EventDispatcherInterface $eventDispatcher,
        private QueryFilterInterface $queryFilter
    ) {
    }

    /**
     * @param array<mixed> $context
     */
    public function __invoke(?string $resourceClass, ?string $rootClass, string $operationName, array $context): ?object
    {
        $readObject = ($this->readStage)($resourceClass, $rootClass, $operationName, $context);
        $this->queryFilter->filter($resourceClass);

        if (AccessCheckerInterface::ITEM_QUERY === \strtoupper($operationName)) {
            $this->eventDispatcher->dispatch(new GraphQLEvent($readObject, $resourceClass, $operationName, $context));
        }

        return $readObject;
    }
}
