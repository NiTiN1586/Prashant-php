<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Integration\Domain\IssueTracker;

class IssueType
{
    private string $id;
    private string $name;
    private string $key;
    private bool $subtask;

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function setKey(string $key): self
    {
        $this->key = $key;

        return $this;
    }

    public function isSubtask(): bool
    {
        return $this->subtask;
    }

    public function setSubtask(bool $subtask): self
    {
        $this->subtask = $subtask;

        return $this;
    }
}
