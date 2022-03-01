<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\EventSubscriber;

use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Jagaad\WitcherApi\EventSubscriber\EventProcessor\EventProcessorContext;

final class EntityDoctrineEventSubscriber implements EventSubscriberInterface
{
    private EventProcessorContext $eventProcessorContext;

    public function __construct(EventProcessorContext $eventProcessorContext)
    {
        $this->eventProcessorContext = $eventProcessorContext;
    }

    /**
     * {@inheritdoc}
     */
    public function getSubscribedEvents(): array
    {
        return [
            Events::preUpdate,
            Events::postUpdate,
            Events::prePersist,
            Events::postPersist,
        ];
    }

    public function preUpdate(LifecycleEventArgs $args): void
    {
        $this->eventProcessorContext->process($args->getEntity(), Events::preUpdate);
    }

    public function prePersist(LifecycleEventArgs $args): void
    {
        $this->eventProcessorContext->process($args->getEntity(), Events::prePersist);
    }

    public function postUpdate(LifecycleEventArgs $args): void
    {
        $this->eventProcessorContext->process($args->getEntity(), Events::postUpdate);
    }

    public function postPersist(LifecycleEventArgs $args): void
    {
        $this->eventProcessorContext->process($args->getEntity(), Events::postPersist);
    }
}
