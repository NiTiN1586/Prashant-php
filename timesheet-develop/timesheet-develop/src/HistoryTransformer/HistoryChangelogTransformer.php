<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\HistoryTransformer;

use Doctrine\ORM\EntityManagerInterface;
use Jagaad\WitcherApi\ElasticSearch\Integration\IssueTracker\Jira\DTO\Changelog;
use Jagaad\WitcherApi\Entity\TrackableActivityInterface;
use Jagaad\WitcherApi\HistoryTransformer\TrackableFields\TrackableFieldsInterface;

final class HistoryChangelogTransformer implements HistoryChangelogTransformerInterface
{
    private EntityManagerInterface $entityManager;

    /** @var array<string, TrackableFieldsInterface> */
    private array $trackableFields;

    /**
     * @param iterable<int, TrackableFieldsInterface> $trackableFields
     */
    public function __construct(iterable $trackableFields, EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;

        foreach ($trackableFields as $trackableField) {
            $this->trackableFields[$trackableField->getAlias()] = $trackableField;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function transform(TrackableActivityInterface $trackableEvent): array
    {
        $results = [];

        $changeSet = $this->entityManager->getUnitOfWork()->getEntityChangeSet($trackableEvent);
        $trackableEntityFields = $this->trackableFields[$trackableEvent->getAlias()] ?? null;

        if (null === $trackableEntityFields || $trackableEvent->isDisabledForTrackableEvents()) {
            return [];
        }

        foreach ($trackableEntityFields->getTrackableFields() as $field) {
            $previous = $changeSet[$field][0] ?? null;
            $current = $changeSet[$field][1] ?? null;

            if ($previous === $current) {
                continue;
            }

            if (\is_object($current)) {
                $current = $trackableEntityFields->getObjectTrackableFiledLog($current, $field);
            }

            if (\is_object($previous)) {
                $previous = $trackableEntityFields->getObjectTrackableFiledLog($previous, $field);
            }

            if (null === $current && null === $previous) {
                continue;
            }

            $results[] = Changelog::create(
                \sha1($previous.$current),
                $field,
                (string) $previous,
                (string) $current
            );
        }

        return $results;
    }
}
