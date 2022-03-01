<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\HistoryTransformer;

use Jagaad\WitcherApi\ElasticSearch\Integration\IssueTracker\Jira\DTO\Changelog;
use Jagaad\WitcherApi\Entity\TrackableActivityInterface;

interface HistoryChangelogTransformerInterface
{
    /**
     * @return Changelog[]
     */
    public function transform(TrackableActivityInterface $trackableEvent): array;
}
