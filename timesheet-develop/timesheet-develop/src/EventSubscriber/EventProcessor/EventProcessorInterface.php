<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\EventSubscriber\EventProcessor;

interface EventProcessorInterface
{
    public function process(object $entity, string $lifecycleEvent): void;
}
