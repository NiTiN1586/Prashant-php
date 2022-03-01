<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
 *     denormalizationContext={"groups"={ContextGroup::GROUP_ROLE_WRITE}},
 *     itemOperations={
 *          "get",
 *          "patch"
 *     },
 *     graphql={"item_query", "collection_query", "create", "update"}
 * )
 *
 * @ORM\Entity()
 * @ORM\Table(
 *     name="role",
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(name="UK_role_handle", fields={"handle"}),
 *     },
 *     indexes={
 *         @ORM\Index(name="IDX_role_handle", fields={"handle"}),
 *         @ORM\Index(name="IDX_role_createdAt", fields={"createdAt"}),
 *         @ORM\Index(name="IDX_role_deletedAt", fields={"deletedAt"}),
 *         @ORM\Index(name="IDX_role_updatedAt", fields={"updatedAt"}),
 *     }
 * )
 *
 * @UniqueEntity(fields={"handle"}, message="Role with such handle exists")
 *
 * @ORM\HasLifecycleCallbacks()
 *
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class Role
{
    use SoftDeleteableEntity;
    use TimestampableEntity;

    public const ADMIN = 'ROLE_ADMIN';
    public const MANAGER = 'ROLE_MANAGER';
    public const DEVELOPER = 'ROLE_DEVELOPER';

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="id", type="integer")
     *
     * @Groups({ContextGroup::GROUP_ROLE_READ})
     */
    private int $id;

    /**
     * @ORM\Column(name="handle", type="string", length=50, unique=true, nullable=false)
     *
     * @Assert\Length(
     *      max=50,
     *      maxMessage="Handle cannot be longer than {{ limit }} characters",
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
     * @Groups({ContextGroup::GROUP_ROLE_READ, ContextGroup::GROUP_ROLE_WRITE})
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
     * @Groups({ContextGroup::GROUP_ROLE_READ, ContextGroup::GROUP_ROLE_WRITE})
     */
    private string $description;

    /**
     * @var Collection<int, Permission>
     *
     * @ORM\ManyToMany(targetEntity=Permission::class, cascade={"persist", "remove"})
     * @ORM\JoinTable(name="role_permission",
     *     joinColumns={
     *          @ORM\JoinColumn(name="role", referencedColumnName="id", nullable=false)
     *      },
     *     inverseJoinColumns={
     *          @ORM\JoinColumn(name="permission", referencedColumnName="id", nullable=false)
     *      }
     * )
     *
     * @Groups({ContextGroup::GROUP_ROLE_READ, ContextGroup::GROUP_ROLE_WRITE})
     *
     * @ApiProperty(readableLink=false, writableLink=true)
     */
    private Collection $permissions;

    public function __construct()
    {
        $this->permissions = new ArrayCollection();
    }

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

    /**
     * @return Permission[]
     */
    public function getPermissions(): array
    {
        return $this->permissions->toArray();
    }

    public function addPermission(Permission $permission): self
    {
        if (!$this->permissions->contains($permission)) {
            $this->permissions->add($permission);
        }

        return $this;
    }

    public function removePermission(Permission $permission): self
    {
        if ($this->permissions->contains($permission)) {
            $this->permissions->removeElement($permission);
        }

        return $this;
    }
}
