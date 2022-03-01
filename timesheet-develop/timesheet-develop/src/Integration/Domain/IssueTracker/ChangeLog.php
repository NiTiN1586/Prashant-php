<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Integration\Domain\IssueTracker;

class ChangeLog
{
    private int $startAt;
    private int $maxResults;
    private int $total;

    /** @var History[]|null */
    private ?array $histories = null;

    public function getStartAt(): int
    {
        return $this->startAt;
    }

    public function setStartAt(int $startAt): self
    {
        $this->startAt = $startAt;

        return $this;
    }

    public function getMaxResults(): int
    {
        return $this->maxResults;
    }

    public function setMaxResults(int $maxResults): self
    {
        $this->maxResults = $maxResults;

        return $this;
    }

    public function getTotal(): int
    {
        return $this->total;
    }

    public function setTotal(int $total): self
    {
        $this->total = $total;

        return $this;
    }

    /**
     * @return History[]|null
     */
    public function getHistories(): ?array
    {
        return $this->histories;
    }

    /**
     * @param History[]|null $histories
     */
    public function setHistories(?array $histories): self
    {
        $this->histories = $histories;

        return $this;
    }
}
