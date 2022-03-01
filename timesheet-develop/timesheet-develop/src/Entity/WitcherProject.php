<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Jagaad\WitcherApi\Annotation\IgnoreFilter;
use Jagaad\WitcherApi\Entity\Traits\EntityAuthorTrait;
use Jagaad\WitcherApi\Enum\ContextGroup;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *     normalizationContext={"groups"={
 *          ContextGroup::GROUP_PROJECT_READ,
 *          ContextGroup::GROUP_SPRINT_READ,
 *          ContextGroup::GROUP_TASK_READ,
 *          ContextGroup::GROUP_WITCHER_USER_READ,
 *          ContextGroup::GROUP_CLIENT_READ,
 *          ContextGroup::GROUP_GENERAL_DICTIONARY_READ,
 *          ContextGroup::PROJECT_TRACKER_TASK_TYPE_READ,
 *          ContextGroup::GROUP_TEAM_READ,
 *     }},
 *     denormalizationContext={"groups"={ContextGroup::GROUP_PROJECT_WRITE}},
 *     itemOperations={
 *          "get",
 *          "patch",
 *          "delete"
 *     }
 * )
 *
 * @ORM\Entity()
 * @ORM\Table(
 *     name="witcher_project",
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(name="UK_witcher_project_slug", fields={"slug"}),
 *         @ORM\UniqueConstraint(name="UK_witcher_project_externalKey", fields={"externalKey"})
 *     },
 *     indexes={
 *         @ORM\Index(name="IDX_witcher_project_deletedAt", fields={"deletedAt"}),
 *         @ORM\Index(name="IDX_witcher_project_estimationType", fields={"estimationType"}),
 *         @ORM\Index(name="IDX_witcher_project_updatedBy", fields={"updatedBy"}),
 *         @ORM\Index(name="IDX_witcher_project_createdBy", fields={"createdBy"}),
 *         @ORM\Index(name="IDX_witcher_project_client", fields={"client"}),
 *         @ORM\Index(name="IDX_witcher_project_projectType", fields={"projectType"}),
 *     }
 * )
 *
 * @UniqueEntity(fields={"slug"}, message="slug already exists")
 * @UniqueEntity(fields={"externalKey"}, message="External key already exists")
 *
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 *
 * @ORM\HasLifecycleCallbacks
 *
 * @IgnoreFilter(filter="softdeleteable", forClasses={WitcherUser::class})
 *
 * @ApiFilter(SearchFilter::class, properties={
 *     "slug",
 *     "projectType",
 * })
 */
class WitcherProject implements EntityAuthorInterface
{
    use EntityAuthorTrait;
    use SoftDeleteableEntity;
    use TimestampableEntity;

    /**
     * @ApiProperty(identifier=false)
     *
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="id", type="integer")
     *
     * @Groups({
     *     ContextGroup::GROUP_PROJECT_READ,
     *     ContextGroup::GROUP_SPRINT_READ,
     *     ContextGroup::PROJECT_TRACKER_TASK_TYPE_READ,
     *     ContextGroup::GROUP_TEAM_READ
     * })
     */
    private int $id;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *             "type"="string",
     *             "example"="project_name"
     *         }
     *     }
     * )
     *
     * @ORM\Column(name="name", type="string", length=50, nullable=false)
     *
     * @Assert\NotNull()
     * @Assert\Length(
     *      max=50,
     *      maxMessage="Project name cannot be longer than {{ limit }} characters",
     * )
     *
     * @Groups({ContextGroup::GROUP_PROJECT_READ, ContextGroup::GROUP_PROJECT_WRITE})
     */
    private string $name;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *             "type"="string",
     *             "example"="description"
     *         }
     *     }
     * )
     *
     * @ORM\Column(name="description", type="string", length=400, nullable=true)
     *
     * @Assert\Length(
     *      max=400,
     *      maxMessage="Project description cannot be longer than {{ limit }} characters",
     * )
     *
     * @Groups({ContextGroup::GROUP_PROJECT_READ, ContextGroup::GROUP_PROJECT_WRITE})
     */
    private ?string $description = null;

    /**
     * @ORM\ManyToOne(targetEntity=Client::class, cascade={"persist", "remove"}, inversedBy="witcherProjects")
     * @ORM\JoinColumn(name="client", referencedColumnName="id", onDelete="SET NULL", nullable=true)
     *
     * @Groups({ContextGroup::GROUP_CLIENT_READ, ContextGroup::GROUP_PROJECT_WRITE})
     */
    private ?Client $client = null;

    /**
     * @ORM\ManyToOne(targetEntity=WitcherUser::class, cascade={"persist"}, inversedBy="witcherProjects")
     * @ORM\JoinColumn(name="created_by", referencedColumnName="id", nullable=false)
     *
     * @Groups({ContextGroup::GROUP_WITCHER_USER_READ})
     */
    protected WitcherUser $createdBy;

    /**
     * @ORM\ManyToOne(targetEntity=ProjectType::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="project_type", referencedColumnName="id", onDelete="SET NULL", nullable=true)
     *
     * @Groups({ContextGroup::GROUP_GENERAL_DICTIONARY_READ, ContextGroup::GROUP_PROJECT_WRITE})
     */
    private ?ProjectType $projectType = null;

    /**
     * @var Collection<int, Task>
     *
     * @ORM\OneToMany(targetEntity=Task::class, mappedBy="witcherProject", cascade={"persist", "remove"})
     *
     * @Groups({ContextGroup::GROUP_PROJECT_READ, ContextGroup::GROUP_PROJECT_WRITE})
     *
     * @ApiProperty(readableLink=false, writableLink=false)
     */
    private Collection $tasks;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *             "type"="boolean",
     *             "example"=true
     *         }
     *     }
     * )
     *
     * @ORM\Column(name="is_billable", type="boolean", options={"default": true})
     *
     * @Assert\NotNull()
     *
     * @Groups({ContextGroup::GROUP_PROJECT_READ, ContextGroup::GROUP_PROJECT_WRITE})
     */
    private bool $isBillable = true;

    /**
     * @var Collection<int, GitProject>
     *
     * @ORM\OneToMany(targetEntity=GitProject::class, mappedBy="witcherProject", cascade={"persist", "remove"})
     *
     * @Groups({ContextGroup::GROUP_PROJECT_READ})
     *
     * @ApiProperty(readableLink=false, writableLink=false)
     */
    private Collection $gitProjects;

    /**
     * @var Collection<int, WitcherProjectTrackerTaskType>
     *
     * @ORM\OneToMany(targetEntity=WitcherProjectTrackerTaskType::class, mappedBy="witcherProject", cascade={"persist", "remove"}, orphanRemoval=true)
     *
     * @Groups({ContextGroup::GROUP_PROJECT_READ, ContextGroup::GROUP_PROJECT_WRITE})
     *
     * @ApiProperty(readableLink=false, writableLink=false)
     */
    private Collection $witcherProjectTrackerTaskTypes;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *             "type"="string",
     *             "example"="https://jagaad.atlassian.net/wiki/spaces/PROJECT/overview"
     *         }
     *     }
     * )
     *
     * @ORM\Column(name="confluence_link", type="string", length=150, nullable=true)
     *
     * @Assert\Length(
     *      max=150,
     *      maxMessage="Confluence link cannot be longer than {{ limit }} characters",
     * )
     *
     * @Groups({ContextGroup::GROUP_PROJECT_READ, ContextGroup::GROUP_PROJECT_WRITE})
     */
    private ?string $confluenceLink = null;

    /**
     * @ORM\Column(name="external_tracker_link", type="string", length=255, nullable=true)
     *
     * @Assert\Length(
     *     max=255,
     *     maxMessage="External tracker link cannot be longer than {{ limit }} characters"
     * )
     *
     * @Assert\Url(message="Invalid url passed for externalTrackerLink")
     *
     * @Groups({ContextGroup::GROUP_PROJECT_READ, ContextGroup::GROUP_PROJECT_WRITE})
     */
    private ?string $externalTrackerLink = null;

    /**
     * @ApiProperty(identifier=true)
     *
     * @ORM\Column(name="slug", type="string", length=20)
     *
     * @Assert\Length(
     *     min=2,
     *     max=20,
     *     minMessage="Slug must be at least {{ limit }} characters long",
     *     maxMessage="Slug cannot be longer than {{ limit }} characters"
     * )
     * @Assert\Regex("/^[A-Z]+$/")
     *
     * @Groups({
     *     ContextGroup::GROUP_PROJECT_READ,
     *     ContextGroup::GROUP_SPRINT_READ,
     *     ContextGroup::GROUP_PROJECT_WRITE,
     *     ContextGroup::PROJECT_TRACKER_TASK_TYPE_READ,
     *     ContextGroup::GROUP_TEAM_READ
     * })
     */
    private string $slug;

    /**
     * @ORM\Column(name="external_key", type="string", length=20, nullable=true)
     *
     * @Assert\Length(
     *      max=30,
     *      maxMessage="External key cannot be longer than {{ limit }} characters",
     * )
     *
     * @Assert\Regex("/^[A-Z]+$/")
     *
     * @Groups({ContextGroup::GROUP_PROJECT_READ})
     */
    private ?string $externalKey = null;

    /**
     * @ORM\ManyToOne(targetEntity=EstimationType::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="estimation_type", referencedColumnName="id", onDelete="SET NULL", nullable=true)
     *
     * @Groups({ContextGroup::GROUP_GENERAL_DICTIONARY_READ, ContextGroup::GROUP_PROJECT_WRITE})
     */
    private ?EstimationType $estimationType = null;

    /**
     * @var Collection<int, Team>
     *
     * @ORM\OneToMany(targetEntity=Team::class, mappedBy="witcherProject")
     */
    private Collection $teams;

    public function __construct()
    {
        $this->witcherProjectTrackerTaskTypes = new ArrayCollection();
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

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): self
    {
        $this->client = $client;

        return $this;
    }

    public function isBillable(): bool
    {
        return $this->isBillable;
    }

    public function setIsBillable(bool $isBillable): self
    {
        $this->isBillable = $isBillable;

        return $this;
    }

    public function getProjectType(): ?ProjectType
    {
        return $this->projectType;
    }

    public function setProjectType(?ProjectType $projectType): self
    {
        $this->projectType = $projectType;

        return $this;
    }

    /**
     * @return Collection<int, Task>
     */
    public function getTasks(): Collection
    {
        return $this->tasks;
    }

    /**
     * @return Collection<int, GitProject>
     */
    public function getGitProjects(): Collection
    {
        return $this->gitProjects;
    }

    /**
     * @param Collection<int, GitProject> $gitProjects
     */
    public function setGitProjects(Collection $gitProjects): self
    {
        $this->gitProjects = $gitProjects;

        return $this;
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

    public function getConfluenceLink(): ?string
    {
        return $this->confluenceLink;
    }

    public function setConfluenceLink(?string $confluenceLink): self
    {
        $this->confluenceLink = $confluenceLink;

        return $this;
    }

    /**
     * @return Collection<int,WitcherProjectTrackerTaskType>
     */
    public function getWitcherProjectTrackerTaskTypes(): Collection
    {
        return $this->witcherProjectTrackerTaskTypes;
    }

    /**
     * @param Collection<int,WitcherProjectTrackerTaskType> $witcherProjectTrackerTaskTypes
     */
    public function setWitcherProjectTrackerTaskTypes(Collection $witcherProjectTrackerTaskTypes): self
    {
        $this->witcherProjectTrackerTaskTypes = $witcherProjectTrackerTaskTypes;

        return $this;
    }

    public function addWitcherProjectTrackerTaskType(WitcherProjectTrackerTaskType $witcherProjectTrackerTaskType): self
    {
        if (!$this->witcherProjectTrackerTaskTypes->contains($witcherProjectTrackerTaskType)) {
            $witcherProjectTrackerTaskType->setWitcherProject($this);
            $this->witcherProjectTrackerTaskTypes->add($witcherProjectTrackerTaskType);
        }

        return $this;
    }

    public function removeWitcherProjectTrackerTaskType(WitcherProjectTrackerTaskType $witcherProjectTrackerTaskType): self
    {
        if ($this->witcherProjectTrackerTaskTypes->contains($witcherProjectTrackerTaskType)) {
            $this->witcherProjectTrackerTaskTypes->removeElement($witcherProjectTrackerTaskType);
        }

        return $this;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getEstimationType(): ?EstimationType
    {
        return $this->estimationType;
    }

    public function setEstimationType(?EstimationType $estimationType): self
    {
        $this->estimationType = $estimationType;

        return $this;
    }

    public function isStoryPointEstimatedProject(): bool
    {
        return null !== $this->estimationType && $this->estimationType->isStoryPointEstimationType();
    }

    public function isTimeEstimatedProject(): bool
    {
        return null !== $this->estimationType && $this->estimationType->isTimeEstimationType();
    }

    /**
     * @return Collection<int, Team>
     */
    public function getTeams(): Collection
    {
        return $this->teams;
    }

    public function getExternalTrackerLink(): ?string
    {
        return $this->externalTrackerLink;
    }

    public function setExternalTrackerLink(?string $externalTrackerLink): self
    {
        $this->externalTrackerLink = $externalTrackerLink;

        return $this;
    }

    public function getExternalKey(): ?string
    {
        return $this->externalKey;
    }

    public function setExternalKey(?string $externalKey): self
    {
        $this->externalKey = $externalKey;

        return $this;
    }
}
