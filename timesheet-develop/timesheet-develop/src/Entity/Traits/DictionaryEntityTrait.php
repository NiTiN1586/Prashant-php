<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Entity\Traits;

use ApiPlatform\Core\Annotation\ApiProperty;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Jagaad\WitcherApi\Enum\ContextGroup;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

trait DictionaryEntityTrait
{
    use TimestampableEntity;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="id", type="integer")
     *
     * @ApiProperty(identifier=true)
     *
     * @Groups({ContextGroup::GROUP_GENERAL_DICTIONARY_READ})
     */
    private int $id;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *             "type"="string",
     *             "example"="Friendly Name"
     *         }
     *     }
     * )
     *
     * @ORM\Column(name="friendly_name", type="string", nullable=false)
     *
     * @Assert\NotBlank()
     *
     * @Groups({ContextGroup::GROUP_GENERAL_DICTIONARY_READ, ContextGroup::GROUP_GENERAL_DICTIONARY_WRITE})
     */
    private string $friendlyName;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *             "type"="string",
     *             "example"="HANDLE"
     *         }
     *     }
     * )
     *
     * @ORM\Column(name="handle", type="string", length=30, nullable=false)
     *
     * @Assert\NotBlank()
     * @Assert\Length(
     *      max=30,
     *      maxMessage="Handle cannot be longer than {{ limit }} characters",
     * )
     *
     * @Groups({ContextGroup::GROUP_GENERAL_DICTIONARY_READ, ContextGroup::GROUP_GENERAL_DICTIONARY_WRITE})
     */
    private string $handle;

    /**
     * @ORM\Column(name="display_order", type="integer", nullable=true)
     *
     * @Groups({ContextGroup::GROUP_GENERAL_DICTIONARY_READ, ContextGroup::GROUP_GENERAL_DICTIONARY_WRITE})
     */
    private ?int $displayOrder;

    public function getId(): int
    {
        return $this->id;
    }

    public function getFriendlyName(): string
    {
        return $this->friendlyName;
    }

    public function setFriendlyName(string $friendlyName): self
    {
        $this->friendlyName = $friendlyName;

        return $this;
    }

    public function getHandle(): string
    {
        return $this->handle;
    }

    public function setHandle(string $handle): self
    {
        $this->handle = $handle;

        return $this;
    }

    public function getDisplayOrder(): ?int
    {
        return $this->displayOrder;
    }

    public function setDisplayOrder(?int $displayOrder): self
    {
        $this->displayOrder = $displayOrder;

        return $this;
    }
}
