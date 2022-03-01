<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use Jagaad\WitcherApi\QueryFilter\QueryFilterInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final class IgnoreFilterEventSubscriber implements EventSubscriberInterface
{
    public function __construct(private QueryFilterInterface $queryFilter)
    {
    }

    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => ['preRead', EventPriorities::PRE_READ],
            RequestEvent::class => 'preRead',
        ];
    }

    public function preRead(RequestEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $resource = $event->getRequest()->get('_api_resource_class', null);

        if (null === $resource || '' === $resource) {
            return;
        }

        try {
            $this->queryFilter->filter($resource);
        } catch (\Throwable $exception) {
            // Do nothing
        }
    }
}
