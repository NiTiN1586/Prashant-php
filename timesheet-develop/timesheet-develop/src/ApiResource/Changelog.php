<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\ApiResource;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Jagaad\WitcherApi\Enum\ContextGroup;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     attributes={"pagination_enabled": false},
 *     normalizationContext={"groups"={ContextGroup::GROUP_HISTORY_READ}},
 *     itemOperations={"get"},
 *     collectionOperations={"get"}
 * )
 */
final class Changelog
{
    /**
     * @ApiProperty(identifier=true)
     *
     * @Groups({ContextGroup::GROUP_HISTORY_READ})
     */
    private string $id = '';

    /**
     * @Groups({ContextGroup::GROUP_HISTORY_READ})
     */
    private string $field;

    /**
     * @Groups({ContextGroup::GROUP_HISTORY_READ})
     */
    private ?string $from;

    /**
     * @Groups({ContextGroup::GROUP_HISTORY_READ})
     */
    private ?string $to;

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): self
    {
        $this->id = $id;

        return $this;
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
}
