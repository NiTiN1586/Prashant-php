<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\ApiResource;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Elasticsearch\DataProvider\Filter\MatchFilter;
use Jagaad\WitcherApi\Enum\ContextGroup;
use Jagaad\WitcherApi\Filter\ElasticDateFilter;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     normalizationContext={"groups"={ContextGroup::GROUP_HISTORY_READ}},
 *     itemOperations={"get"},
 *     collectionOperations={"get"},
 *     attributes={"order"={"createdAt": "DESC"}}
 * )
 * @ApiFilter(MatchFilter::class, properties={"taskId", "slug", "creatorId", "changelog.field", "entityType"}),
 * @ApiFilter(ElasticDateFilter::class, properties={"createdAt"})
 */
class History
{
    /**
     * @ApiProperty(identifier=true)
     *
     * @Groups({ContextGroup::GROUP_HISTORY_READ})
     */
    private string $id = '';

    /**
     * @Groups({ContextGroup::GROUP_HISTORY_READ})
     */
    private int $taskId;

    /**
     * @Groups({ContextGroup::GROUP_HISTORY_READ})
     */
    private string $slug;

    /**
     * @Groups({ContextGroup::GROUP_HISTORY_READ})
     */
    private ?int $trackerEventId = null;

    /**
     * @Groups({ContextGroup::GROUP_HISTORY_READ})
     */
    private ?int $internalEventId = null;

    /**
     * @Groups({ContextGroup::GROUP_HISTORY_READ})
     */
    private \DateTimeInterface $createdAt;

    /**
     * @Groups({ContextGroup::GROUP_HISTORY_READ})
     */
    private int $creatorId;
    /**
     * @var Changelog[]
     *
     * @Groups({ContextGroup::GROUP_HISTORY_READ})
     */
    private array $changelog;

    /**
     * @Groups({ContextGroup::GROUP_HISTORY_READ})
     */
    private string $entityType;

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getTaskId(): int
    {
        return $this->taskId;
    }

    public function setTaskId(int $taskId): self
    {
        $this->taskId = $taskId;

        return $this;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getTrackerEventId(): ?int
    {
        return $this->trackerEventId;
    }

    public function setTrackerEventId(?int $trackerEventId): self
    {
        $this->trackerEventId = $trackerEventId;

        return $this;
    }

    public function getInternalEventId(): ?int
    {
        return $this->internalEventId;
    }

    public function setInternalEventId(?int $internalEventId): self
    {
        $this->internalEventId = $internalEventId;

        return $this;
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getCreatorId(): int
    {
        return $this->creatorId;
    }

    public function setCreatorId(int $creatorId): self
    {
        $this->creatorId = $creatorId;

        return $this;
    }

    /**
     * @return Changelog[]
     */
    public function getChangelog(): array
    {
        return $this->changelog;
    }

    /**
     * @param Changelog[] $changelog
     */
    public function setChangelog(array $changelog): self
    {
        $this->changelog = $changelog;

        return $this;
    }

    public function getEntityType(): string
    {
        return $this->entityType;
    }

    public function setEntityType(string $entityType): self
    {
        $this->entityType = $entityType;

        return $this;
    }
}
