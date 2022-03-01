<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Security;

use ApiPlatform\Core\GraphQl\Resolver\Stage\WriteStageInterface;
use Doctrine\ORM\EntityManagerInterface;
use Jagaad\WitcherApi\Security\Event\GraphQLEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

final class GraphQLWriteStage implements WriteStageInterface
{
    private WriteStageInterface $writeStage;
    private EventDispatcherInterface $eventDispatcher;
    private EntityManagerInterface $entityManager;

    public function __construct(WriteStageInterface $writeStage, EventDispatcherInterface $eventDispatcher, EntityManagerInterface $entityManager)
    {
        $this->writeStage = $writeStage;
        $this->eventDispatcher = $eventDispatcher;
        $this->entityManager = $entityManager;
    }

    /**
     * @param array<mixed> $context
     */
    public function __invoke($data, string $resourceClass, string $operationName, array $context): ?object
    {
        $this->eventDispatcher->dispatch(
            new GraphQLEvent(
                $data,
                $resourceClass,
                $operationName,
                $context,
                $this->entityManager->getUnitOfWork()->getOriginalEntityData($data)
            )
        );

        return ($this->writeStage)($data, $resourceClass, $operationName, $context);
    }
}
