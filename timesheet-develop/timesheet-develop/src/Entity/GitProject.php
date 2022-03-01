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
use Jagaad\WitcherApi\Entity\Traits\EntityAuthorTrait;
use Jagaad\WitcherApi\Enum\ContextGroup;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *     normalizationContext={"groups"={ContextGroup::GROUP_PROJECT_READ, ContextGroup::GROUP_WITCHER_USER_READ}},
 *     denormalizationContext={"groups"={ContextGroup::GROUP_GIT_PROJECT_WRITE}},
 *     itemOperations={
 *       "get",
 *       "patch",
 *       "delete"
 *     }
 * )
 *
 * @ORM\Entity()
 * @ORM\Table(
 *     name="git_project",
 *     indexes={
 *         @ORM\Index(name="IDX_git_project_createdAt", fields={"createdAt"}),
 *         @ORM\Index(name="IDX_git_project_witcherProject", fields={"witcherProject"}),
 *         @ORM\Index(name="IDX_git_project_deletedAt", fields={"deletedAt"}),
 *         @ORM\Index(name="IDX_git_project_updatedBy", fields={"updatedBy"}),
 *         @ORM\Index(name="IDX_git_project_createdBy", fields={"createdBy"}),
 *     }
 * )
 *
 * @ORM\HasLifecycleCallbacks
 *
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class GitProject implements EntityAuthorInterface
{
    use EntityAuthorTrait;
    use SoftDeleteableEntity;
    use TimestampableEntity;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="id", type="integer")
     *
     * @Groups({ContextGroup::GROUP_PROJECT_READ})
     */
    private int $id;

    /**
     * @ORM\ManyToOne(targetEntity=WitcherProject::class, cascade={"persist", "remove"}, inversedBy="gitProjects")
     * @ORM\JoinColumn(name="witcher_project", referencedColumnName="id", onDelete="CASCADE", nullable=false)
     *
     * @Groups({ContextGroup::GROUP_PROJECT_READ, ContextGroup::GROUP_GIT_PROJECT_WRITE})
     */
    private WitcherProject $witcherProject;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *             "type"="string",
     *             "example"="http://gitlab.com/project"
     *         }
     *     }
     * )
     *
     * @ORM\Column(name="git_lab_link", type="string", nullable=false)
     *
     * @Assert\NotBlank()
     * @Assert\Url
     *
     * @Groups({ContextGroup::GROUP_PROJECT_READ, ContextGroup::GROUP_GIT_PROJECT_WRITE})
     */
    private string $gitLabLink;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *             "type"="string",
     *             "example"="2351346"
     *         }
     *     }
     * )
     *
     * @ORM\Column(name="git_lab_project_id", type="string", nullable=false)
     *
     * @Assert\NotBlank()
     *
     * @Groups({ContextGroup::GROUP_PROJECT_READ, ContextGroup::GROUP_GIT_PROJECT_WRITE})
     */
    private string $gitLabProjectId;

    /**
     * @var Collection<int, GitBranch>
     *
     * @ORM\OneToMany(targetEntity=GitBranch::class, mappedBy="project", cascade={"persist"})
     */
    private Collection $gitBranches;

    public function __construct()
    {
        $this->gitBranches = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getWitcherProject(): WitcherProject
    {
        return $this->witcherProject;
    }

    public function setWitcherProject(WitcherProject $witcherProject): self
    {
        $this->witcherProject = $witcherProject;

        return $this;
    }

    public function getGitLabLink(): string
    {
        return $this->gitLabLink;
    }

    public function setGitLabLink(string $gitLabLink): self
    {
        $this->gitLabLink = $gitLabLink;

        return $this;
    }

    public function getGitLabProjectId(): string
    {
        return $this->gitLabProjectId;
    }

    public function setGitLabProjectId(string $gitLabProjectId): self
    {
        $this->gitLabProjectId = $gitLabProjectId;

        return $this;
    }

    /**
     * @return Collection<int, GitBranch>
     */
    public function getGitBranches(): Collection
    {
        return $this->gitBranches;
    }

    /**
     * @param Collection<int, GitBranch>|array<int, GitBranch> $gitBranches
     */
    public function setGitBranches(array|Collection $gitBranches): self
    {
        if ($gitBranches instanceof Collection) {
            $this->gitBranches = $gitBranches;

            return $this;
        }

        $this->gitBranches = new ArrayCollection($gitBranches);

        return $this;
    }

    public function addGitBranch(GitBranch $branch): self
    {
        if (!$this->gitBranches->contains($branch)) {
            $this->gitBranches->add($branch);
        }

        return $this;
    }

    public function deleteGitBranch(GitBranch $branch): self
    {
        if ($this->gitBranches->contains($branch)) {
            $this->gitBranches->removeElement($branch);
        }

        return $this;
    }
}
