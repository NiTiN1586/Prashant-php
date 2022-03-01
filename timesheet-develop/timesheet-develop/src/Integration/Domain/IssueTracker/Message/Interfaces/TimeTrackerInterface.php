<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Integration\Domain\IssueTracker\Message\Interfaces;

interface TimeTrackerInterface
{
    public function getEvent(): string;

    public function getHandle(): string;

    public function getTimestamp(): int;

    /**
     * @return array<string, mixed>
     */
    public function getParams(): array;
}
