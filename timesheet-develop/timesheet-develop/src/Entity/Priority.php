<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Jagaad\WitcherApi\Entity\Traits\DictionaryEntityTrait;
use Jagaad\WitcherApi\Enum\ContextGroup;
use Jagaad\WitcherApi\Security\ReadableResourceInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *     normalizationContext={"groups"={ContextGroup::GROUP_PRIORITY_READ, ContextGroup::GROUP_GENERAL_DICTIONARY_READ}},
 *     denormalizationContext={"groups"={ContextGroup::GROUP_PRIORITY_WRITE, ContextGroup::GROUP_GENERAL_DICTIONARY_WRITE}},
 *     itemOperations={
 *       "get",
 *       "patch",
 *       "delete"
 *     }
 * )
 *
 * @UniqueEntity(fields={"handle"}, message="Priority with such handle exists")
 *
 * @ORM\Entity()
 * @ORM\Table(
 *     name="priority",
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(name="UK_priority_handle", fields={"handle"}),
 *     },
 *     indexes={
 *         @ORM\Index(name="IDX_priority_displayOrder", fields={"displayOrder"}),
 *         @ORM\Index(name="IDX_priority_createdAt", fields={"createdAt"}),
 *         @ORM\Index(name="IDX_priority_deletedAt", fields={"deletedAt"}),
 *     }
 * )
 *
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 *
 * @ORM\HasLifecycleCallbacks()
 */
class Priority implements ReadableResourceInterface
{
    use DictionaryEntityTrait;
    use SoftDeleteableEntity;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *             "type"="string",
     *             "example"="Priority Description"
     *         }
     *     }
     * )
     *
     * @ORM\Column(name="description", type="string", length=200, nullable=true)
     *
     * @Assert\Length(
     *      max = 200,
     *      maxMessage = "Description cannot be longer than {{ limit }} characters",
     * )
     *
     * @Groups({ContextGroup::GROUP_PRIORITY_READ, ContextGroup::GROUP_PRIORITY_WRITE})
     */
    private ?string $description;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *             "type"="string",
     *             "example"="#ffffff"
     *         }
     *     }
     * )
     *
     * @ORM\Column(name="status_color", type="string", length=7, nullable=false)
     *
     * @Assert\Length(
     *      max = 7,
     *      maxMessage = "Status color cannot be longer than {{ limit }} characters",
     * )
     *
     * @Groups({ContextGroup::GROUP_PRIORITY_READ, ContextGroup::GROUP_PRIORITY_WRITE})
     */
    private string $statusColor;

    public static function createFromParams(string $name, string $handle, string $statusColor, ?string $description): self
    {
        $priority = new self();
        $priority->friendlyName = $name;
        $priority->handle = $handle;
        $priority->description = $description;
        $priority->statusColor = $statusColor;

        return $priority;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
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
