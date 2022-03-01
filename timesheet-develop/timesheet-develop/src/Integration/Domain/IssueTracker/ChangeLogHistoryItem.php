<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Integration\Domain\IssueTracker;

class ChangeLogHistoryItem
{
    private string $field;
    private string $fieldType;
    private string $fieldId;
    private ?string $fromString = null;
    private ?string $toString = null;
    private ?string $from = null;
    private ?string $to = null;

    public function getField(): string
    {
        return $this->field;
    }

    public function setField(string $field): self
    {
        $this->field = $field;

        return $this;
    }

    public function getFieldType(): string
    {
        return $this->fieldType;
    }

    public function setFieldType(string $fieldType): self
    {
        $this->fieldType = $fieldType;

        return $this;
    }

    public function getFieldId(): string
    {
        return $this->fieldId;
    }

    public function setFieldId(string $fieldId): self
    {
        $this->fieldId = $fieldId;

        return $this;
    }

    public function getFromString(): ?string
    {
        return $this->fromString;
    }

    public function setFromString(?string $fromString): self
    {
        $this->fromString = $fromString;

        return $this;
    }

    public function getToString(): ?string
    {
        return $this->toString;
    }

    public function setToString(?string $toString): self
    {
        $this->toString = $toString;

        return $this;
    }

    public function getFrom(): ?string
    {
        return $this->from;
    }

    public function setFrom(?string $from): self
    {
        $this->from = $from;

        return $this;
    }

    public function getTo(): ?string
    {
        return $this->to;
    }

    public function setTo(?string $to): self
    {
        $this->to = $to;

        return $this;
    }
}
