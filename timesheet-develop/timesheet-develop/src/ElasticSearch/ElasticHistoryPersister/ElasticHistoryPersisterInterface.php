<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\ElasticSearch\ElasticHistoryPersister;

use Jagaad\WitcherApi\Entity\TrackableActivityInterface;

interface ElasticHistoryPersisterInterface
{
    public function persist(TrackableActivityInterface $entity): void;
}
