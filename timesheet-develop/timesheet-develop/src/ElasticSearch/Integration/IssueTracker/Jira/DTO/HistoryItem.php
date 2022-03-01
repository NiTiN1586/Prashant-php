<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\ElasticSearch\Integration\IssueTracker\Jira\DTO;

final class HistoryItem
{
    private const DATE_FORMAT = 'Y-m-d\TH:i:s';

    private string $id;
    private ?int $trackerEventId = null;
    private ?int $internalEventId = null;
    private string $slug;
    private string $createdAt;
    private int $creatorId;
    private int $taskId;
    private string $entityType;

    /** @var Changelog[] */
    private array $changelog;

    /**
     * @param Changelog[] $changelog
     */
    public static function create(
        string $id,
        string $entityType,
        int $taskId,
        string $slug,
        \DateTimeInterface $createdAt,
        int $creatorId,
        array $changelog,
        ?int $trackerEventId,
        ?int $internalEventId
    ): self {
        if (null === $trackerEventId && null === $internalEventId) {
            throw new \InvalidArgumentException('trackerEventId and internalEventId can\'t be both empty');
        }

        return (new self())
            ->setId($id)
            ->setEntityType($entityType)
            ->setSlug($slug)
            ->setCreatedAt($createdAt->format(self::DATE_FORMAT))
            ->setChangelog($changelog)
            ->setCreatorId($creatorId)
            ->setTrackerEventId($trackerEventId)
            ->setInternalEventId($internalEventId)
            ->setTaskId($taskId);
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

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    public function setCreatedAt(string $createdAt): self
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

    public function getTaskId(): int
    {
        return $this->taskId;
    }

    public function setTaskId(int $taskId): self
    {
        $this->taskId = $taskId;

        return $this;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): self
    {
        $this->id = $id;

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
