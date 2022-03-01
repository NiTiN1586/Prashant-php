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
 *     normalizationContext={"groups"={ContextGroup::GROUP_DEPARTMENT_READ}},
 *     denormalizationContext={"groups"={ContextGroup::GROUP_DEPARTMENT_WRITE}},
 *     itemOperations={
 *       "get",
 *       "patch",
 *       "delete"
 *     }
 * )
 *
 * @ORM\Entity()
 * @UniqueEntity(fields={"handle"}, message="Department with such handle already exists")
 * @ORM\Table(
 *     name="department",
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(name="UK_department_handle", fields={"handle"})
 *     },
 *     indexes={
 *         @ORM\Index(name="IDX_department_createdAt", fields={"createdAt"}),
 *         @ORM\Index(name="IDX_department_deletedAt", fields={"deletedAt"})
 *     }
 * )
 *
 * @ORM\HasLifecycleCallbacks
 *
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class Department implements ReadableResourceInterface
{
    use SoftDeleteableEntity;
    use TimestampableEntity;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="id", type="integer")
     *
     * @Groups({ContextGroup::GROUP_DEPARTMENT_READ})
     */
    private int $id;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *             "type"="string",
     *             "example"="Marketing"
     *         }
     *     }
     * )
     *
     * @ORM\Column(name="name", type="string", length=50, nullable=false)
     *
     * @Assert\NotBlank()
     * @Assert\Length(
     *      max=50,
     *      maxMessage="Department name cannot be longer than {{ limit }} characters",
     * )
     *
     * @Groups({ContextGroup::GROUP_DEPARTMENT_READ, ContextGroup::GROUP_DEPARTMENT_WRITE})
     */
    private string $name;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *             "type"="string",
     *             "example"="MARKETING"
     *         }
     *     }
     * )
     *
     * @ORM\Column(name="handle", type="string", length=50, nullable=false)
     *
     * @Assert\NotBlank()
     * @Assert\Length(
     *      max=50,
     *      maxMessage="Handle cannot be longer than {{ limit }} characters",
     * )
     *
     * @Groups({ContextGroup::GROUP_DEPARTMENT_READ, ContextGroup::GROUP_DEPARTMENT_WRITE})
     */
    private string $handle;

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

    public function getHandle(): string
    {
        return $this->handle;
    }

    public function setHandle(string $handle): self
    {
        $this->handle = $handle;

        return $this;
    }
}
