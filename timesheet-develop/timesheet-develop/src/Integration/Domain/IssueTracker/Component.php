<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Integration\Domain\IssueTracker;

class Component
{
    private string $id;
    private ?string $name;

    public function __construct(?string $name = null)
    {
        $this->name = $name;
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }
}
