<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Integration\Domain\IssueTracker;

class Issue
{
    private string $id;
    private string $key;

    private ?IssueField $fields = null;

    private ?ChangeLog $changelog = null;

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): self
    {
        $this->id = $id;

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

    public function getFields(): ?IssueField
    {
        return $this->fields;
    }

    public function getProjectKey(): ?string
    {
        return null !== $this->fields ? $this->fields->getProject()->getKey() : null;
    }

    public function setFields(?IssueField $fields): self
    {
        $this->fields = $fields;

        return $this;
    }

    public function getChangelog(): ?ChangeLog
    {
        return $this->changelog;
    }

    public function setChangelog(?ChangeLog $changelog): self
    {
        $this->changelog = $changelog;

        return $this;
    }
}
