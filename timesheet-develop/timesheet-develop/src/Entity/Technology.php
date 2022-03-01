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
 *     normalizationContext={"groups"={ContextGroup::GROUP_TECHNOLOGY_READ}},
 *     denormalizationContext={"groups"={ContextGroup::GROUP_TECHNOLOGY_WRITE}},
 *     itemOperations={
 *       "get",
 *       "patch",
 *       "delete"
 *     }
 * )
 *
 * @ORM\Entity()
 * @ORM\Table(
 *     name="technology",
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(name="UK_technology_name", fields={"name"}),
 *     },
 *     indexes={
 *         @ORM\Index(name="IDX_technology_createdAt", fields={"createdAt"}),
 *         @ORM\Index(name="IDX_technology_deletedAt", fields={"deletedAt"}),
 *     }
 * )
 *
 * @UniqueEntity(fields={"name"}, message="Technology name already exists")
 *
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 *
 * @ORM\HasLifecycleCallbacks()
 */
class Technology implements ReadableResourceInterface
{
    use SoftDeleteableEntity;
    use TimestampableEntity;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="id", type="integer")
     *
     * @Groups({ContextGroup::GROUP_TECHNOLOGY_READ})
     */
    private int $id;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *             "type"="string",
     *             "example"="PHP"
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
     * @Assert\Regex(pattern="/^[a-zA-Z0-9]+$/", message="Name should contain digit or letter")
     *
     * @Groups({ContextGroup::GROUP_TECHNOLOGY_READ, ContextGroup::GROUP_TECHNOLOGY_WRITE})
     */
    private string $name;

    public static function create(string $name): self
    {
        $technology = new self();
        $technology->setName($name);

        return $technology;
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
