<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Integration\Domain\Interfaces;

use Jagaad\WitcherApi\Integration\Domain\DTO\EventContainer;

interface EventProcessorInterface
{
    public function process(EventContainer $eventContainer): void;
}
