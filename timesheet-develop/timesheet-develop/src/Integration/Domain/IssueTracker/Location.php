<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Integration\Domain\IssueTracker;

final class Location
{
    private int $projectId;
    private string $projectKey;

    public function getProjectId(): int
    {
        return $this->projectId;
    }

    public function setProjectId(int $projectId): self
    {
        $this->projectId = $projectId;

        return $this;
    }

    public function getProjectKey(): string
    {
        return $this->projectKey;
    }

    public function setProjectKey(string $projectKey): self
    {
        $this->projectKey = $projectKey;

        return $this;
    }
}
