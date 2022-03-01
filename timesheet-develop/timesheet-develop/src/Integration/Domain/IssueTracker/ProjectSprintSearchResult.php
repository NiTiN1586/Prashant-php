<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Integration\Domain\IssueTracker;

class ProjectSprintSearchResult
{
    /** @var Sprint[] */
    private array $sprints;

    /**
     * @return Sprint[]
     */
    public function getSprints(): array
    {
        return $this->sprints;
    }

    /**
     * @param Sprint[] $sprints
     */
    public function setSprints(array $sprints): self
    {
        $this->sprints = $sprints;

        return $this;
    }
}
