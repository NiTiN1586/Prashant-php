<?php

namespace Jagaad\WitcherApi\Integration\Domain\IssueTracker;

class History
{
    private int $id;
    private User $author;
    private \DateTimeImmutable $created;

    /** @var HistoryItem[]|null */
    private ?array $items;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getAuthor(): User
    {
        return $this->author;
    }

    public function setAuthor(User $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getCreated(): \DateTimeImmutable
    {
        return $this->created;
    }

    public function setCreated(\DateTimeImmutable $created): self
    {
        $this->created = $created;

        return $this;
    }

    /**
     * @return HistoryItem[]|null
     */
    public function getItems(): ?array
    {
        return $this->items;
    }

    /**
     * @param HistoryItem[]|null $items
     */
    public function setItems(?array $items): self
    {
        $this->items = $items;

        return $this;
    }
}
