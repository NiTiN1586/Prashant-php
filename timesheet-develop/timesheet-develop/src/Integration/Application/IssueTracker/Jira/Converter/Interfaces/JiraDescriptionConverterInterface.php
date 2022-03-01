<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Integration\Application\IssueTracker\Jira\Converter\Interfaces;

use Jagaad\WitcherApi\Integration\Domain\IssueTracker\Description;

interface JiraDescriptionConverterInterface
{
    public function convert(?Description $description): string;
}
