<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Jagaad\WitcherApi\Annotation\IgnoreFilter;
use Jagaad\WitcherApi\Constraint\TrackerTaskType\ContainsAllowedTrackerTaskType;
use Jagaad\WitcherApi\Entity\Traits\EntityAuthorTrait;
use Jagaad\WitcherApi\Enum\ContextGroup;
use Jagaad\WitcherApi\Enum\HistoryEntityType;
use Jagaad\WitcherApi\Filter\SearchSummaryFilter;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *     normalizationContext={"groups"={
 *          ContextGroup::GROUP_TASK_READ,
 *          ContextGroup::GROUP_GENERAL_DICTIONARY_READ,
 *          ContextGroup::GROUP_WITCHER_USER_READ,
 *          ContextGroup::GROUP_PROJECT_READ,
 *          ContextGroup::GROUP_PRIORITY_READ,
 *          ContextGroup::GROUP_STATUS_READ,
 *          ContextGroup::GROUP_GENERIC_TRAIT_READ,
 *          ContextGroup::GROUP_SPRINT_READ,
 *          ContextGroup::GROUP_ACTIVITY_READ
 *     }},
 *     denormalizationContext={"groups"={ContextGroup::GROUP_TASK_WRITE}},
 *     itemOperations={
 *          "get",
 *          "patch",
 *          "delete"
 *     }
 * )
 *
 * @UniqueEntity(fields={"externalKey"}, message="Task with such external key already exists")
 * @UniqueEntity(fields={"slug"}, message="Task with such slug already exists")
 *
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(
 *     name="task",
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(name="UK_task_externalKey", fields={"externalKey"}),
 *         @ORM\UniqueConstraint(name="UK_task_slug", fields={"slug"})
 *     },
 *     indexes={
 *         @ORM\Index(name="IDX_task_createdAt", fields={"createdAt"}),
 *         @ORM\Index(name="IDX_task_deletedAt", fields={"deletedAt"}),
 *         @ORM\Index(name="IDX_task_witcherProject", fields={"witcherProject"}),
 *         @ORM\Index(name="IDX_task_witcherProject_createdBy", fields={"witcherProject", "createdBy"}),
 *         @ORM\Index(name="IDX_task_assignee", fields={"assignee"}),
 *         @ORM\Index(name="IDX_task_reporter", fields={"reporter"}),
 *         @ORM\Index(name="IDX_task_reporter_updatedBy", fields={"updatedBy"}),
 *         @ORM\Index(name="IDX_task_reporter_createdBy", fields={"createdBy"}),
 *         @ORM\Index(name="IDX_task_status", fields={"status"}),
 *         @ORM\Index(name="IDX_task_priority", fields={"priority"}),
 *         @ORM\Index(name="IDX_task_trackerTaskType", fields={"trackerTaskType"}),
 *         @ORM\Index(name="IDX_task_parentTask", fields={"parentTask"}),
 *     }
 * )
 *
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 *
 * @IgnoreFilter(filter="softdeleteable", forClasses={WitcherUser::class})
 *
 * @ContainsAllowedTrackerTaskType()
 *
 * @ApiFilter(SearchFilter::class, properties={
 *     "createdBy": "exact",
 *     "assignee":"exact",
 *     "reporter":"exact",
 *     "witcherProject.slug":"exact",
 *     "status.handle": "exact",
 *     "sprints",
 *     "sprints.name": "partial",
 * })
 *
 * @ApiFilter(SearchSummaryFilter::class)
 *
 * @ApiFilter(DateFilter::class, properties={"createdAt", "dueAt"})
 * @ApiFilter(OrderFilter::class, properties={"id", "slug", "priority.handle", "createdAt", "dueAt"})
 */
class Task implements TrackableActivityInterface
{
    use EntityAuthorTrait;
    use SoftDeleteableEntity;
    use TimestampableEntity;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="id", type="integer")
     * @ApiProperty(identifier=false)
     *
     * @Groups({
     *     ContextGroup::GROUP_TASK_READ,
     *     ContextGroup::GROUP_SPRINT_READ,
     *     ContextGroup::GROUP_ACTIVITY_READ
     * })
     */
    private int $id;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *             "type"="string",
     *             "example"="Task title"
     *         }
     *     }
     * )
     *
     * @ORM\Column(name="summary", type="string", nullable=false)
     *
     * @Assert\NotBlank()
     *
     * @Groups({ContextGroup::GROUP_TASK_READ, ContextGroup::GROUP_TASK_WRITE})
     */
    private string $summary;

    /**
     * @Gedmo\Slug(handlers={
     *     @Gedmo\SlugHandler(class="Gedmo\Sluggable\Handler\RelativeSlugHandler", options={
     *          @Gedmo\SlugHandlerOption(name="relationField", value="witcherProject"),
     *          @Gedmo\SlugHandlerOption(name="relationSlugField", value="slug"),
     *          @Gedmo\SlugHandlerOption(name="separator", value="")
     *      })
     * }, fields={"id"}, updatable=false, unique=true, separator="-")
     *
     * @ORM\Column(name="slug", type="string", length=30, unique=true)
     *
     * @Assert\Length(
     *      max=30,
     *      maxMessage="Slug cannot be longer than {{ limit }} characters",
     * )
     *
     * @ApiProperty(identifier=true)
     *
     * @Groups({
     *     ContextGroup::GROUP_TASK_READ,
     *     ContextGroup::GROUP_SPRINT_READ,
     *     ContextGroup::GROUP_ACTIVITY_READ
     * })
     */
    private string $slug;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *             "type"="string",
     *             "format"="date",
     *             "example"="2022-01-28"
     *         }
     *     }
     * )
     *
     * @ORM\Column(name="due_at", type="datetime_immutable", nullable=true)
     *
     * @Groups({ContextGroup::GROUP_TASK_READ, ContextGroup::GROUP_TASK_WRITE})
     */
    private ?\DateTimeImmutable $dueAt = null;

    /**
     * @ORM\Column(name="external_key", type="string", length=30, nullable=true)
     *
     * @Assert\Length(
     *      max=30,
     *      maxMessage="External key cannot be longer than {{ limit }} characters",
     * )
     *
     * @Groups({ContextGroup::GROUP_TASK_READ})
     */
    private ?string $externalKey = null;

    /**
     * @ORM\ManyToOne(targetEntity=WitcherProject::class, cascade={"persist"}, inversedBy="tasks")
     * @ORM\JoinColumn(name="witcher_project", referencedColumnName="id", onDelete="CASCADE", nullable=false)
     *
     * @Groups({ContextGroup::GROUP_PROJECT_READ, ContextGroup::GROUP_TASK_WRITE})
     */
    private WitcherProject $witcherProject;

    /**
     * @ORM\ManyToOne(targetEntity=WitcherUser::class, cascade={"persist"})
     * @ORM\JoinColumn(name="assignee", referencedColumnName="id", onDelete="SET NULL")
     *
     * @Groups({ContextGroup::GROUP_WITCHER_USER_READ, ContextGroup::GROUP_TASK_WRITE})
     */
    private ?WitcherUser $assignee = null;

    /**
     * @ORM\ManyToOne(targetEntity=WitcherUser::class, cascade={"persist"})
     * @ORM\JoinColumn(name="reporter", referencedColumnName="id", onDelete="SET NULL")
     *
     * @Groups({ContextGroup::GROUP_WITCHER_USER_READ, ContextGroup::GROUP_TASK_WRITE})
     */
    private ?WitcherUser $reporter = null;

    /**
     * @ORM\Column(name="estimation_time", type="integer", options={"default" : 0})
     *
     * @Groups({ContextGroup::GROUP_TASK_READ, ContextGroup::GROUP_TASK_WRITE})
     */
    private int $estimationTime = 0;

    /**
     * @ORM\Column(name="estimation_sp", type="integer", options={"default" : 0})
     *
     * @Groups({ContextGroup::GROUP_TASK_READ, ContextGroup::GROUP_TASK_WRITE})
     */
    private int $estimationSp = 0;

    /**
     * @ORM\ManyToOne(targetEntity=Status::class)
     * @ORM\JoinColumn(name="status", referencedColumnName="id", nullable=false)
     *
     * @Groups({ContextGroup::GROUP_STATUS_READ, ContextGroup::GROUP_TASK_WRITE})
     */
    private Status $status;

    /**
     * @ORM\ManyToOne(targetEntity=Priority::class)
     * @ORM\JoinColumn(name="priority", referencedColumnName="id", onDelete="SET NULL", nullable=true)
     *
     * @Groups({ContextGroup::GROUP_PRIORITY_READ, ContextGroup::GROUP_TASK_WRITE})
     */
    private ?Priority $priority = null;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *             "type"="string",
     *             "example"="http://jira.example.com/issues/ISS-1"
     *         }
     *     }
     * )
     *
     * @ORM\Column(name="external_tracker_link", type="string", length=255, nullable=true)
     *
     * @Assert\Url(message="Invalid url passed for externalTrackerLink")
     * @Assert\Length(
     *      max=255,
     *      maxMessage="External tracker link cannot be longer than {{ limit }} characters",
     * )
     *
     * @Groups({ContextGroup::GROUP_TASK_READ, ContextGroup::GROUP_TASK_WRITE})
     */
    private ?string $externalTrackerLink = null;

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
     * @ORM\Column(name="description", type="text", length=65535, nullable=true)
     *
     * @Assert\Length(
     *      max=65535,
     *      maxMessage="Task description cannot be longer than {{ limit }} characters",
     * )
     *
     * @Groups({ContextGroup::GROUP_TASK_READ, ContextGroup::GROUP_TASK_WRITE})
     */
    private ?string $description = null;

    /**
     * @var Collection<int, Task>
     *
     * @ORM\OneToMany(targetEntity=Task::class, mappedBy="parentTask")
     */
    private Collection $subTasks;

    /**
     * @ORM\ManyToOne(targetEntity=Task::class, inversedBy="subTasks")
     * @ORM\JoinColumn(name="parent_task", onDelete="SET NULL", referencedColumnName="id")
     */
    private ?Task $parentTask = null;

    /**
     * @var Collection<int, Activity>
     *
     * @ORM\OneToMany(targetEntity=Activity::class, mappedBy="task", cascade={"persist"})
     *
     * @Groups({ContextGroup::GROUP_ACTIVITY_READ, ContextGroup::GROUP_GENERAL_DICTIONARY_READ})
     *
     * @ApiProperty(readableLink=false, writableLink=false)
     */
    private Collection $activities;

    /**
     * @var Collection<int, GitBranch>
     *
     * @ORM\OneToMany(targetEntity=GitBranch::class, mappedBy="task", cascade={"persist"})
     *
     * @ApiProperty(readableLink=false, writableLink=false)
     */
    private Collection $gitBranches;

    /**
     * @ORM\ManyToOne(targetEntity=TrackerTaskType::class)
     * @ORM\JoinColumn(name="tracker_task_type", referencedColumnName="id", nullable=false)
     *
     * @Groups({ContextGroup::GROUP_GENERAL_DICTIONARY_READ, ContextGroup::GROUP_TASK_WRITE})
     */
    private TrackerTaskType $trackerTaskType;

    /**
     * @var Collection<int, Label>
     *
     * @ORM\ManyToMany(targetEntity=Label::class, cascade={"persist", "remove"})
     * @ORM\JoinTable(name="task_label",
     *     joinColumns={
     *          @ORM\JoinColumn(name="task", referencedColumnName="id", nullable=false)
     *      },
     *     inverseJoinColumns={
     *          @ORM\JoinColumn(name="label", referencedColumnName="id", nullable=false)
     *      }
     * )
     *
     * @Groups({ContextGroup::GROUP_TASK_READ, ContextGroup::GROUP_TASK_WRITE})
     *
     * @ApiProperty(readableLink=false, writableLink=true)
     */
    private Collection $labels;

    /**
     * @var Collection<int, Sprint>
     *
     * @ORM\ManyToMany(targetEntity=Sprint::class, cascade={"persist", "remove"})
     * @ORM\JoinTable(name="task_sprint",
     *     joinColumns={
     *          @ORM\JoinColumn(name="task", referencedColumnName="id", nullable=false)
     *      },
     *     inverseJoinColumns={
     *          @ORM\JoinColumn(name="sprint", referencedColumnName="id", nullable=false)
     *      }
     * )
     *
     * @Groups({ContextGroup::GROUP_SPRINT_READ, ContextGroup::GROUP_TASK_WRITE})
     *
     * @ApiProperty(readableLink=false, writableLink=true)
     */
    private Collection $sprints;

    /**
     * @var Collection<int, Comment>
     *
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="task")
     *
     * @Groups({ContextGroup::GROUP_TASK_COMMENT_READ})
     */
    private Collection $comments;

    public function __construct()
    {
        $this->labels = new ArrayCollection();
        $this->sprints = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
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

    public function getAssignee(): ?WitcherUser
    {
        return $this->assignee;
    }

    public function setAssignee(?WitcherUser $assignee): self
    {
        $this->assignee = $assignee;

        return $this;
    }

    public function getReporter(): ?WitcherUser
    {
        return $this->reporter;
    }

    public function setReporter(?WitcherUser $reporter): self
    {
        $this->reporter = $reporter;

        return $this;
    }

    public function getStatus(): Status
    {
        return $this->status;
    }

    public function setStatus(Status $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getPriority(): ?Priority
    {
        return $this->priority;
    }

    public function setPriority(?Priority $priority): self
    {
        $this->priority = $priority;

        return $this;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Activity[]
     */
    public function getActivities(): array
    {
        return $this->activities->getValues();
    }

    public function addActivity(Activity $activity): self
    {
        if (!$this->activities->contains($activity)) {
            $this->activities->add($activity);
        }

        return $this;
    }

    public function deleteActivity(Activity $activity): self
    {
        if ($this->activities->contains($activity)) {
            $this->activities->removeElement($activity);
        }

        return $this;
    }

    /**
     * @return Collection<int, Task>
     */
    public function getSubTasks(): Collection
    {
        return $this->subTasks;
    }

    /**
     * @param Collection<int, Task> $subTasks
     */
    public function setSubTasks(Collection $subTasks): self
    {
        $this->subTasks = $subTasks;

        return $this;
    }

    public function addSubTask(self $subTask): self
    {
        $subTask->setParentTask($this);
        $this->subTasks->add($subTask);

        return $this;
    }

    public function getParentTask(): ?self
    {
        return $this->parentTask;
    }

    public function setParentTask(?self $parentTask): self
    {
        $this->parentTask = $parentTask;

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
     * @param Collection<int, GitBranch> $gitBranches
     */
    public function setGitBranches(Collection $gitBranches): self
    {
        $this->gitBranches = $gitBranches;

        return $this;
    }

    /**
     * @param Collection<int, Label>|array<int, Label> $labels
     */
    public function setLabels(Collection|array $labels): self
    {
        if ($labels instanceof Collection) {
            $this->labels = $labels;

            return $this;
        }

        $this->labels = new ArrayCollection($labels);

        return $this;
    }

    /**
     * @return Collection<int, Label>
     */
    public function getLabels(): Collection
    {
        return $this->labels;
    }

    public function addLabel(Label $label): self
    {
        if (!$this->labels->contains($label)) {
            $this->labels->add($label);
        }

        return $this;
    }

    public function deleteLabel(Label $label): self
    {
        if ($this->labels->contains($label)) {
            $this->labels->removeElement($label);
        }

        return $this;
    }

    public function getTrackerTaskType(): TrackerTaskType
    {
        return $this->trackerTaskType;
    }

    public function setTrackerTaskType(TrackerTaskType $trackerTaskType): self
    {
        $this->trackerTaskType = $trackerTaskType;

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

    public function getExternalKey(): ?string
    {
        return $this->externalKey;
    }

    public function setExternalKey(?string $externalKey): self
    {
        $this->externalKey = $externalKey;

        return $this;
    }

    public function getSummary(): string
    {
        return $this->summary;
    }

    public function setSummary(string $summary): self
    {
        $this->summary = $summary;

        return $this;
    }

    public function getEstimationTime(): int
    {
        return $this->estimationTime;
    }

    public function setEstimationTime(int $estimationTime = 0): self
    {
        $this->estimationTime = $estimationTime;

        return $this;
    }

    public function getEstimationSp(): int
    {
        return $this->estimationSp;
    }

    public function setEstimationSp(int $estimationSp = 0): self
    {
        $this->estimationSp = $estimationSp;

        return $this;
    }

    /**
     * @return Comment[]
     */
    public function getComments(): array
    {
        return $this->comments->getValues();
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $comment->setTask($this);
            $this->comments->add($comment);
        }

        return $this;
    }

    public function deleteComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
        }

        return $this;
    }

    public function getAlias(): string
    {
        return HistoryEntityType::TASK;
    }

    public function getTask(): self
    {
        return $this;
    }

    public function isDisabledForTrackableEvents(): bool
    {
        return null !== $this->externalKey;
    }

    /**
     * @Groups({ContextGroup::GROUP_TASK_READ})
     * @SerializedName("createdAt")
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function getDueAt(): ?\DateTimeImmutable
    {
        return $this->dueAt;
    }

    public function setDueAt(?\DateTimeImmutable $dueAt): self
    {
        $this->dueAt = $dueAt;

        return $this;
    }

    /**
     * @param Collection<int, Sprint>|array<int, Sprint> $sprints
     */
    public function setSprints(Collection|array $sprints): self
    {
        if ($sprints instanceof Collection) {
            $this->sprints = $sprints;

            return $this;
        }

        $this->sprints = new ArrayCollection($sprints);

        return $this;
    }

    /**
     * @return Collection<int, Sprint>
     */
    public function getSprints(): Collection
    {
        return $this->sprints;
    }

    public function addSprint(Sprint $sprint): self
    {
        if (!$this->sprints->contains($sprint)) {
            $this->sprints->add($sprint);
        }

        return $this;
    }

    public function removeSprint(Sprint $sprint): self
    {
        if ($this->sprints->contains($sprint)) {
            $this->sprints->removeElement($sprint);
        }

        return $this;
    }

    /**
     * @Groups({ContextGroup::GROUP_TASK_READ})
     * @SerializedName("efficiencySp")
     */
    public function getEfficiencySp(): ?string
    {
        if ($this->estimationSp <= 0) {
            return null;
        }

        $result = 0;

        foreach ($this->getActivities() as $activity) {
            $result += $activity->getEstimationSp();
        }

        return \number_format(
            ($result * 100) / $this->estimationSp,
            2
        );
    }

    /**
     * @Groups({ContextGroup::GROUP_TASK_READ})
     * @SerializedName("efficiencyTime")
     */
    public function getEfficiencyTime(): ?string
    {
        if ($this->estimationTime <= 0) {
            return null;
        }

        $result = 0;

        foreach ($this->getActivities() as $activity) {
            $result += $activity->getEstimationTime();
        }

        return \number_format(
            ($result * 100) / $this->estimationTime,
            2
        );
    }

    public function isAvailableFor(int $userId): bool
    {
        return $this->getCreatedByUserId() === $userId
            || $this->assignee?->getUserId() === $userId
            || $this->reporter?->getUserId() === $userId;
    }
}
