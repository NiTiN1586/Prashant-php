<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Integration\Domain\IssueTracker;

final class SprintSearchResult
{
    private int $maxResults;
    private int $startAt;
    private bool $isLast;

    /** @var Sprint[] */
    private array $values;

    public function getMaxResults(): int
    {
        return $this->maxResults;
    }

    public function setMaxResults(int $maxResults): self
    {
        $this->maxResults = $maxResults;

        return $this;
    }

    public function getStartAt(): int
    {
        return $this->startAt;
    }

    public function setStartAt(int $startAt): self
    {
        $this->startAt = $startAt;

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
     * @return Sprint[]
     */
    public function getValues(): array
    {
        return $this->values;
    }

    /**
     * @param Sprint[] $values
     */
    public function setValues(array $values): self
    {
        $this->values = $values;

        return $this;
    }
}
