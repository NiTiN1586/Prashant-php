<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Integration\Domain\IssueTracker;

class IssueSearchResult
{
    private int $startAt;
    private int $maxResults;
    private int $total;

    /** @var Issue[] */
    private array $issues = [];

    private ?object $names = null;

    public function getIssue(int $index): Issue
    {
        return $this->issues[$index];
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
     * @return Issue[]
     */
    public function getIssues(): array
    {
        return $this->issues;
    }

    /**
     * @param Issue[] $issues
     */
    public function setIssues(array $issues): self
    {
        $this->issues = $issues;

        return $this;
    }

    public function getNames(): ?object
    {
        return $this->names;
    }

    public function setNames(?object $names): self
    {
        $this->names = $names;

        return $this;
    }
}
