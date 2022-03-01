<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Integration\Domain\IssueTracker;

final class Board
{
    private int $id;
    private Location $location;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getLocation(): Location
    {
        return $this->location;
    }

    public function getProjectKey(): string
    {
        return $this->location->getProjectKey();
    }

    public function setLocation(Location $location): self
    {
        $this->location = $location;

        return $this;
    }
}
