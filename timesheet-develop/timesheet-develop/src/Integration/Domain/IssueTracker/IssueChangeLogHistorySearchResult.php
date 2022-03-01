<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Integration\Domain\IssueTracker;

class IssueChangeLogHistorySearchResult
{
    private int $startAt;
    private int $maxResults;
    private int $total;
    private bool $isLast;

    /** @var ChangeLogHistory[] */
    public array $values;

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

    public function isLast(): bool
    {
        return $this->isLast;
    }

    public function setIsLast(bool $isLast): self
    {
        $this->isLast = $isLast;

        return $this;
    }

    /**
     * @return ChangeLogHistory[]
     */
    public function getValues(): array
    {
        return $this->values;
    }

    /**
     * @param ChangeLogHistory[] $values
     */
    public function setValues(array $values): self
    {
        $this->values = $values;

        return $this;
    }
}
