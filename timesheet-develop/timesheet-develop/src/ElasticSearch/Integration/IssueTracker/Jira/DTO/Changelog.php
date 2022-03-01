<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\ElasticSearch\Integration\IssueTracker\Jira\DTO;

final class Changelog
{
    private string $id;
    private string $field;
    private ?string $from;
    private ?string $to;

    public static function create(string $id, string $field, ?string $from, ?string $to): self
    {
        if (null === $from && null === $to) {
            throw new \InvalidArgumentException('Changelog is required');
        }

        return (new self())
            ->setId($id)
            ->setField($field)
            ->setFrom($from)
            ->setTo($to);
    }

    public function getField(): string
    {
        return $this->field;
    }

    public function setField(string $field): self
    {
        $this->field = $field;

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

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): self
    {
        $this->id = $id;

        return $this;
    }
}
