<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Integration\Domain\IssueTracker;

class Priority
{
    private string $name;
    private string $id;
    private string $description;
    private string $statusColor;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getStatusColor(): string
    {
        return $this->statusColor;
    }

    public function setStatusColor(string $statusColor): self
    {
        $this->statusColor = $statusColor;

        return $this;
    }
}
