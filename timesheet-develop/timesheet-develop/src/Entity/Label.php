<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Jagaad\WitcherApi\Enum\ContextGroup;
use Jagaad\WitcherApi\Security\ReadableResourceInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *     normalizationContext={"groups"={ContextGroup::GROUP_TASK_READ}},
 *     denormalizationContext={"groups"={ContextGroup::GROUP_LABEL_WRITE}},
 *     itemOperations={
 *       "get",
 *       "patch",
 *       "delete"
 *     }
 * )
 *
 * @UniqueEntity(fields={"name"}, message="Label with such name already exists")
 *
 * @ORM\Entity()
 * @ORM\Table(
 *     name="label",
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(name="UK_label_name", columns={"name"}),
 *     },
 *     indexes={
 *         @ORM\Index(name="IDX_label_createdAt", columns={"created_at"}),
 *         @ORM\Index(name="IDX_label_deletedAt", columns={"deleted_at"}),
 *     }
 * )
 *
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 *
 * @ORM\HasLifecycleCallbacks()
 */
class Label implements ReadableResourceInterface
{
    use SoftDeleteableEntity;
    use TimestampableEntity;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="id", type="integer")
     *
     * @Groups({ContextGroup::GROUP_TASK_READ})
     */
    private int $id;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *             "type"="string",
     *             "example"="Frontend"
     *         }
     *     }
     * )
     *
     * @ORM\Column(name="name", type="string", length=30, nullable=false)
     *
     * @Assert\NotBlank()
     * @Assert\Length(
     *      max=30,
     *      maxMessage="Name cannot be longer than {{ limit }} characters",
     * )
     *
     * @Groups({ContextGroup::GROUP_TASK_READ, ContextGroup::GROUP_LABEL_WRITE})
     */
    private string $name;

    public static function create(string $name): self
    {
        $label = new self();
        $label->setName($name);

        return $label;
    }

    public function getId(): int
    {
        return $this->id;
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
}
