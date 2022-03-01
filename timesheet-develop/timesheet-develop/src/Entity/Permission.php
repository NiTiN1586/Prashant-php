<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Jagaad\WitcherApi\Enum\ContextGroup;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *     normalizationContext={"groups"={ContextGroup::GROUP_ROLE_READ}},
 *     collectionOperations={"get"},
 *     itemOperations={"get"},
 *     graphql={"collection_query", "item_query"}
 * )
 *
 * @ORM\Entity()
 * @ORM\Table(
 *     name="permission",
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(name="UK_permission_handle", fields={"handle"}),
 *     },
 *     indexes={
 *         @ORM\Index(name="IDX_permission_handle", fields={"handle"}),
 *         @ORM\Index(name="IDX_permission_createdAt", fields={"createdAt"}),
 *         @ORM\Index(name="IDX_permission_updatedAt", fields={"updatedAt"}),
 *         @ORM\Index(name="IDX_permission_deletedAt", fields={"deletedAt"}),
 *         @ORM\Index(name="IDX_permission_permissionGroup", fields={"permissionGroup"}),
 *     }
 * )
 *
 * @UniqueEntity(fields={"handle"}, message="Permission with such handle exists")
 *
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 *
 * @ORM\HasLifecycleCallbacks()
 */
class Permission
{
    use SoftDeleteableEntity;
    use TimestampableEntity;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="id", type="integer")
     *
     * @Groups({ContextGroup::GROUP_ROLE_READ})
     */
    private int $id;

    /**
     * @ORM\Column(name="handle", type="string", length=50, nullable=false)
     *
     * @Assert\NotBlank()
     * @Assert\Length(
     *      max=50,
     *      maxMessage="Permission handle cannot be longer than {{ limit }} characters",
     * )
     *
     * @Groups({ContextGroup::GROUP_ROLE_READ})
     */
    private string $handle;

    /**
     * @ORM\Column(name="name", type="string", length=50, nullable=false)
     *
     * @Assert\NotBlank()
     * @Assert\Length(
     *      max=50,
     *      maxMessage="Name cannot be longer than {{ limit }} characters",
     * )
     *
     * @Groups({ContextGroup::GROUP_ROLE_READ})
     */
    private string $name;

    /**
     * @ORM\Column(name="description", type="string", length=1000, nullable=false)
     *
     * @Assert\NotBlank()
     * @Assert\Length(
     *      max=1000,
     *      maxMessage="Description cannot be longer than {{ limit }} characters",
     * )
     *
     * @Groups({ContextGroup::GROUP_ROLE_READ})
     */
    private string $description;

    /**
     * @ORM\ManyToOne(targetEntity=PermissionGroup::class, cascade={"persist", "remove"}, inversedBy="permissions")
     * @ORM\JoinColumn(name="permission_group", referencedColumnName="id", nullable=false)
     *
     * @Groups({ContextGroup::GROUP_ROLE_READ})
     */
    private PermissionGroup $permissionGroup;

    public function getId(): int
    {
        return $this->id;
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

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPermissionGroup(): PermissionGroup
    {
        return $this->permissionGroup;
    }

    public function setPermissionGroup(PermissionGroup $permissionGroup): self
    {
        $this->permissionGroup = $permissionGroup;

        return $this;
    }
}
