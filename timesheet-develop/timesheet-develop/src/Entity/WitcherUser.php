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
use Jagaad\UserProviderBundle\Enum\ContextGroup as JagaadContextGroup;
use Jagaad\UserProviderBundle\Security\Model\User;
use Jagaad\WitcherApi\Enum\ContextGroup;
use Jagaad\WitcherApi\Enum\ValidationGroup;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * TODO: Modify serialization groups for related entities in WITCHER-316
 *
 * @ApiResource(
 *     normalizationContext={"groups"={
 *          ContextGroup::GROUP_WITCHER_USER_READ,
 *          ContextGroup::GROUP_DEPARTMENT_READ,
 *          ContextGroup::GROUP_COMPANY_POSITION_READ,
 *          JagaadContextGroup::GROUP_JAGAAD_USER_DETAILS,
 *          ContextGroup::GROUP_COMPANY_POSITION_READ,
 *          ContextGroup::GROUP_ROLE_READ
 *     }},
 *     denormalizationContext={"groups"={ContextGroup::GROUP_WITCHER_USER_WRITE}},
 *     itemOperations={
 *          "get",
 *          "patch"={
 *              "validation_groups"={"Default", ValidationGroup::UPDATE}
 *          },
 *          "delete"
 *     }
 * )
 *
 * @ORM\Entity()
 * @ORM\Table(
 *     name="witcher_user",
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(name="UK_witcher_user_gitLabUserId", fields={"gitLabUserId"}),
 *         @ORM\UniqueConstraint(name="UK_witcher_user_supervisor", fields={"supervisor"}),
 *         @ORM\UniqueConstraint(name="UK_witcher_user_jiraAccount", fields={"jiraAccount"}),
 *         @ORM\UniqueConstraint(name="UK_witcher_user_userId", fields={"userId"})
 *     },
 *     indexes={
 *         @ORM\Index(name="IDX_witcher_user_createdAt", fields={"createdAt"}),
 *         @ORM\Index(name="IDX_witcher_user_deletedAt", fields={"deletedAt"}),
 *         @ORM\Index(name="IDX_witcher_user_companyPosition", fields={"companyPosition"}),
 *         @ORM\Index(name="IDX_witcher_user_role", fields={"role"})
 *     }
 * )
 *
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 *
 * @ORM\HasLifecycleCallbacks
 */
class WitcherUser
{
    use SoftDeleteableEntity;
    use TimestampableEntity;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="id", type="integer")
     *
     * @ApiProperty(identifier=true)
     *
     * @Groups({
     *     ContextGroup::GROUP_WITCHER_USER_READ,
     *     ContextGroup::GROUP_PROJECT_EVENT_READ,
     *     ContextGroup::GROUP_GIT_COMMIT_READ
     * })
     */
    private int $id;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *             "type"="integer",
     *             "example"=123
     *         }
     *     }
     * )
     *
     * @ORM\Column(name="user_id", type="integer", nullable=false)
     *
     * @Assert\NotNull(message="User ID should be set before saving")
     *
     * @Groups({ContextGroup::GROUP_WITCHER_USER_READ, ContextGroup::GROUP_WITCHER_USER_WRITE})
     */
    private int $userId;

    /**
     * @ORM\ManyToOne(targetEntity=Role::class)
     * @ORM\JoinColumn(name="role", referencedColumnName="id", nullable=false)
     *
     * @Groups({ContextGroup::GROUP_ROLE_READ, ContextGroup::GROUP_WITCHER_USER_WRITE})
     */
    private Role $role;

    /**
     * @var Collection<int, WitcherProject>
     *
     * @ORM\OneToMany(targetEntity=WitcherProject::class, mappedBy="createdBy", cascade={"persist", "remove"})
     */
    private Collection $witcherProjects;

    /**
     * @Groups({JagaadContextGroup::GROUP_JAGAAD_USER_DETAILS})
     */
    private ?User $user = null;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *             "type"="integer",
     *             "example"=14356
     *         }
     *     }
     * )
     *
     * @ORM\Column(name="git_lab_user_id", type="integer", unique=true, nullable=true)
     *
     * @Assert\NotNull(groups={ValidationGroup::UPDATE})
     *
     * @Groups({ContextGroup::GROUP_WITCHER_USER_READ, ContextGroup::GROUP_WITCHER_USER_WRITE})
     */
    private ?int $gitLabUserId = null;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *             "type"="string",
     *             "example"="5b10ac8d82e05b22"
     *         }
     *     }
     * )
     *
     * @ORM\Column(name="jira_account", type="string", length=128, unique=true)
     *
     * @Assert\Length(max=128)
     * @Assert\NotBlank()
     *
     * @Groups({ContextGroup::GROUP_WITCHER_USER_READ, ContextGroup::GROUP_WITCHER_USER_WRITE})
     */
    private string $jiraAccount;

    /**
     * @ORM\ManyToOne (targetEntity=CompanyPosition::class, cascade={"persist"})
     * @ORM\JoinColumn(name="company_position", referencedColumnName="id")
     *
     * @Assert\NotNull(groups={ValidationGroup::UPDATE})
     *
     * @Groups({ContextGroup::GROUP_COMPANY_POSITION_READ, ContextGroup::GROUP_WITCHER_USER_WRITE})
     */
    private ?CompanyPosition $companyPosition = null;

    /**
     * @var Collection<int, Department>
     *
     * @ORM\ManyToMany(targetEntity=Department::class, cascade={"persist", "remove"})
     * @ORM\JoinTable(name="witcher_user_department",
     *     joinColumns={
     *          @ORM\JoinColumn(name="witcher_user", referencedColumnName="id", nullable=false)
     *      },
     *     inverseJoinColumns={
     *          @ORM\JoinColumn(name="department", referencedColumnName="id", nullable=false)
     *      }
     * )
     *
     * @Groups({ContextGroup::GROUP_DEPARTMENT_READ, ContextGroup::GROUP_WITCHER_USER_WRITE})
     *
     * @ApiProperty(readableLink=false, writableLink=true)
     */
    private Collection $departments;

    /**
     * Supervisor to whom current user directly reports
     *
     * @ORM\OneToOne(targetEntity=WitcherUser::class)
     * @ORM\JoinColumn(name="supervisor", referencedColumnName="id", nullable=true)
     *
     * @Groups({ContextGroup::GROUP_WITCHER_USER_READ, ContextGroup::GROUP_WITCHER_USER_WRITE})
     */
    private ?WitcherUser $supervisor = null;

    public function __construct()
    {
        $this->departments = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    public function getRole(): Role
    {
        return $this->role;
    }

    public function setRole(Role $role): self
    {
        $this->role = $role;

        return $this;
    }

    /**
     * @return Collection<int, WitcherProject>
     */
    public function getWitcherProjects(): Collection
    {
        return $this->witcherProjects;
    }

    /**
     * @param Collection<int, WitcherProject> $witcherProjects
     */
    public function setWitcherProjects(Collection $witcherProjects): self
    {
        $this->witcherProjects = $witcherProjects;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getGitLabUserId(): ?int
    {
        return $this->gitLabUserId;
    }

    public function setGitLabUserId(?int $gitLabUserId): self
    {
        $this->gitLabUserId = $gitLabUserId;

        return $this;
    }

    public function getJiraAccount(): string
    {
        return $this->jiraAccount;
    }

    public function setJiraAccount(string $jiraAccount): self
    {
        $this->jiraAccount = $jiraAccount;

        return $this;
    }

    public function getCompanyPosition(): ?CompanyPosition
    {
        return $this->companyPosition;
    }

    public function setCompanyPosition(?CompanyPosition $companyPosition): self
    {
        $this->companyPosition = $companyPosition;

        return $this;
    }

    /**
     * @param Collection<int, Department>|array<int, Department> $departments
     */
    public function setDepartments(Collection|array $departments): self
    {
        if (!$departments instanceof Collection) {
            $this->departments = new ArrayCollection($departments);

            return $this;
        }

        $this->departments = $departments;

        return $this;
    }

    /**
     * @return Collection<int, Department>
     */
    public function getDepartments(): Collection
    {
        return $this->departments;
    }

    public function addDepartment(Department $department): self
    {
        if (!$this->departments->contains($department)) {
            $this->departments->add($department);
        }

        return $this;
    }

    public function removeDepartment(Department $department): self
    {
        if ($this->departments->contains($department)) {
            $this->departments->removeElement($department);
        }

        return $this;
    }

    public function getSupervisor(): ?self
    {
        return $this->supervisor;
    }

    public function setSupervisor(?self $supervisor): self
    {
        $this->supervisor = $supervisor;

        return $this;
    }

    public function isRoleAssigned(): bool
    {
        return isset($this->role);
    }
}
