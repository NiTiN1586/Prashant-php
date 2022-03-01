<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Integration\Domain\IssueTracker;

class ContentField
{
    public string $type;
    public ?string $text = null;

    /** @phpstan-ignore-next-line */
    public array $content = [];
}
