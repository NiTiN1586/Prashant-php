<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\EventSubscriber\EventProcessor;

final class EventProcessorContext
{
    /** @var iterable<EventProcessorInterface> */
    private iterable $eventProcessors;

    /**
     * @param iterable<EventProcessorInterface> $eventProcessors
     */
    public function __construct(iterable $eventProcessors)
    {
        $this->eventProcessors = $eventProcessors;
    }

    public function process(object $entity, string $event): void
    {
        foreach ($this->eventProcessors as $eventProcessor) {
            $eventProcessor->process($entity, $event);
        }
    }
}
