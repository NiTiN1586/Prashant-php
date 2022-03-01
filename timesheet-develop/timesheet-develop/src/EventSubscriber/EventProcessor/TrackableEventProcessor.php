<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\EventSubscriber\EventProcessor;

use Jagaad\WitcherApi\ElasticSearch\ElasticHistoryPersister\ElasticHistoryPersisterInterface;
use Jagaad\WitcherApi\Entity\TrackableActivityInterface;

final class TrackableEventProcessor implements EventProcessorInterface
{
    private ElasticHistoryPersisterInterface $historyPersister;

    public function __construct(ElasticHistoryPersisterInterface $historyPersister)
    {
        $this->historyPersister = $historyPersister;
    }

    public function process(object $entity, string $lifecycleEvent): void
    {
        if (!$entity instanceof TrackableActivityInterface) {
            return;
        }

        $this->historyPersister->persist($entity);
    }
}
