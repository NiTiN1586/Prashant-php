<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Integration\Domain\GitManagement\Message\Interfaces;

interface GitEventInterface
{
    public function getEvent(): string;

    public function getProject(): int;

    public function getBranch(): string;
}
