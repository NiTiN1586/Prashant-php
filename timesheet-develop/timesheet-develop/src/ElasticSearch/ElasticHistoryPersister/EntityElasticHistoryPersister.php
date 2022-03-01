<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\ElasticSearch\ElasticHistoryPersister;

use FOS\ElasticaBundle\Persister\ObjectPersisterInterface;
use Jagaad\WitcherApi\ElasticSearch\Integration\IssueTracker\Jira\DTO\HistoryItem;
use Jagaad\WitcherApi\Entity\TrackableActivityInterface;
use Jagaad\WitcherApi\HistoryTransformer\HistoryChangelogTransformerInterface;

final class EntityElasticHistoryPersister implements ElasticHistoryPersisterInterface
{
    private ObjectPersisterInterface $elasticPersister;
    private HistoryChangelogTransformerInterface $changelogTransformer;

    public function __construct(ObjectPersisterInterface $elasticPersister, HistoryChangelogTransformerInterface $changelogTransformer)
    {
        $this->elasticPersister = $elasticPersister;
        $this->changelogTransformer = $changelogTransformer;
    }

    public function persist(TrackableActivityInterface $entity): void
    {
        $changelogs = $this->changelogTransformer->transform($entity);

        if (0 === \count($changelogs)) {
            return;
        }

        $this->elasticPersister->replaceOne(
            HistoryItem::create(
                \sha1(\sprintf('%d%d', $entity->getTask()->getId(), $entity->getId())),
                $entity->getAlias(),
                $entity->getTask()->getId(),
                $entity->getTask()->getSlug(),
                $entity->getUpdatedAt(),
                $entity->getCreatedBy()->getId(),
                $changelogs,
                null,
                $entity->getId()
            )
        );
    }
}
