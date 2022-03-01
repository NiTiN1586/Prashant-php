<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Jagaad\WitcherApi\Annotation\IgnoreFilter;
use Jagaad\WitcherApi\Entity\Traits\EntityAuthorTrait;
use Jagaad\WitcherApi\Enum\ContextGroup;
use Jagaad\WitcherApi\Enum\HistoryEntityType;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *     attributes={"order"={"createdAt"="DESC"}},
 *     normalizationContext={"groups"={
 *          ContextGroup::GROUP_ACTIVITY_READ,
 *          ContextGroup::GROUP_GENERAL_DICTIONARY_READ,
 *          ContextGroup::GROUP_WITCHER_USER_READ,
 *          ContextGroup::GROUP_TASK_READ,
 *          ContextGroup::GROUP_TECHNOLOGY_READ
 *     }},
 *     denormalizationContext={"groups"={ContextGroup::GROUP_ACTIVITY_WRITE}},
 *     graphql={
 *          "item_query",
 *          "collection_query",
 *          "create",
 *         "update"={
 *             "denormalization_context"={"groups"={ContextGroup::GROUP_ALLOW_MODIFY}}
 *          },
 *         "delete"
 *      },
 *     itemOperations={
 *          "get",
 *          "patch"={
 *              "denormalization_context"={"groups"={ContextGroup::GROUP_ALLOW_MODIFY}}
 *          },
 *          "delete"
 *     }
 * )
 *
 * @ORM\Entity()
 * @ORM\Table(
 *     name="activity",
 *     indexes={
 *         @ORM\Index(name="IDX_activity_createdAt", fields={"createdAt"}),
 *         @ORM\Index(name="IDX_activity_updatedAt", fields={"updatedAt"}),
 *         @ORM\Index(name="IDX_activity_deletedAt", fields={"deletedAt"}),
 *         @ORM\Index(name="IDX_activity_activityType", fields={"activityType"}),
 *         @ORM\Index(name="IDX_activity_task", fields={"task"}),
 *         @ORM\Index(name="IDX_activity_createdBy", fields={"createdBy"}),
 *         @ORM\Index(name="IDX_activity_updatedBy", fields={"updatedBy"}),
 *         @ORM\Index(name="IDX_activity_technology", fields={"technology"}),
 *     }
 * )
 *
 * @IgnoreFilter(filter="softdeleteable", forClasses={WitcherUser::class})
 *
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 *
 * @ORM\HasLifecycleCallbacks
 *
 * @IgnoreFilter(filter="softdeleteable", forClasses={ActivityType::class, Technology::class})
 *
 * @ApiFilter(SearchFilter::class, properties={
 *     "task.slug": "exact",
 *     "activityType":"exact",
 *     "createdBy": "exact",
 * })
 * @ApiFilter(DateFilter::class, properties={"activityAt"})
 * @ApiFilter(OrderFilter::class, properties={"activityAt", "createdBy"})
 */
class Activity implements TrackableActivityInterface
{
    use EntityAuthorTrait;
    use SoftDeleteableEntity;
    use TimestampableEntity;

    private const GIT_SHA_REGEX = '/[0-9a-f]{5,40}$/';

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="id", type="integer")
     *
     * @Groups({ContextGroup::GROUP_ACTIVITY_READ})
     */
    private int $id;

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
     * @ORM\Column(name="activity_at", type="datetime_immutable", nullable=false)
     *
     * @Groups({ContextGroup::GROUP_ACTIVITY_READ, ContextGroup::GROUP_ALLOW_MODIFY, ContextGroup::GROUP_ACTIVITY_WRITE})
     */
    private \DateTimeImmutable $activityAt;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *             "type"="integer",
     *             "example"=1
     *         }
     *     }
     * )
     *
     * @ORM\Column(name="estimation_time", type="integer", options={"default" : 0})
     *
     * @Assert\PositiveOrZero(message="Estimation Time should be positive or zero value")
     *
     * @Groups({ContextGroup::GROUP_ACTIVITY_READ, ContextGroup::GROUP_ALLOW_MODIFY, ContextGroup::GROUP_ACTIVITY_WRITE})
     */
    private int $estimationTime = 0;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *             "type"="integer",
     *             "example"=1
     *         }
     *     }
     * )
     *
     * @ORM\Column(name="estimation_sp", type="integer", options={"default" : 0})
     *
     * @Assert\PositiveOrZero(message="Estimation Sp should be positive or zero value")
     *
     * @Groups({ContextGroup::GROUP_ACTIVITY_READ, ContextGroup::GROUP_ALLOW_MODIFY, ContextGroup::GROUP_ACTIVITY_WRITE})
     */
    private int $estimationSp = 0;

    /**
     * @ORM\ManyToOne(targetEntity=Task::class, cascade={"persist"}, inversedBy="activities")
     * @ORM\JoinColumn(name="task", referencedColumnName="id", onDelete="CASCADE", nullable=false)
     *
     * @Groups({ContextGroup::GROUP_ACTIVITY_READ, ContextGroup::GROUP_ACTIVITY_WRITE})
     */
    private Task $task;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *             "type"="string",
     *             "example"="comment"
     *         }
     *     }
     * )
     *
     * @ORM\Column(name="comment", type="string", length=400, nullable=true)
     *
     * @Assert\Length(
     *      max = 400,
     *      maxMessage = "Activity comment cannot be longer than {{ limit }} characters",
     * )
     *
     * @Groups({ContextGroup::GROUP_ACTIVITY_READ, ContextGroup::GROUP_ALLOW_MODIFY, ContextGroup::GROUP_ACTIVITY_WRITE})
     */
    private ?string $comment;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *             "type"="integer",
     *             "example"="1"
     *         }
     *     }
     * )
     * @ORM\ManyToOne(targetEntity=ActivityType::class, cascade={"persist"}, inversedBy="activities")
     * @ORM\JoinColumn(name="activity_type", referencedColumnName="id", nullable=false)
     *
     * @Groups({
     *     ContextGroup::GROUP_ACTIVITY_READ,
     *     ContextGroup::GROUP_ACTIVITY_WRITE,
     *     ContextGroup::GROUP_ALLOW_MODIFY,
     *     ContextGroup::GROUP_GENERAL_DICTIONARY_READ
     *  })
     */
    private ActivityType $activityType;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *             "type"="integer",
     *             "example"="1"
     *         }
     *     }
     * )
     * @ORM\ManyToOne(targetEntity=Technology::class, cascade={"persist"})
     * @ORM\JoinColumn(name="technology", referencedColumnName="id")
     *
     * @Groups({
     *     ContextGroup::GROUP_TECHNOLOGY_READ,
     *     ContextGroup::GROUP_ACTIVITY_WRITE,
     *     ContextGroup::GROUP_ALLOW_MODIFY
     *  })
     */
    private ?Technology $technology = null;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *             "type"="string",
     *             "example"="https://example.gitlab.com/project/-/commit/99fac12"
     *         }
     *     }
     * )
     *
     * @ORM\Column(name="source", type="string", length=400, nullable=true)
     *
     * @Assert\Length(
     *      max=400,
     *      maxMessage = "Source cannot be longer than {{ limit }} characters",
     * )
     *
     * @Assert\Url(message="source should be in url format")
     *
     * @Groups({ContextGroup::GROUP_ACTIVITY_READ, ContextGroup::GROUP_ALLOW_MODIFY, ContextGroup::GROUP_ACTIVITY_WRITE})
     */
    private ?string $source = null;

    public function getId(): int
    {
        return $this->id;
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

    public function getTask(): Task
    {
        return $this->task;
    }

    public function setTask(Task $task): self
    {
        $this->task = $task;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * @return ActivityType
     */
    public function getActivityType(): ActivityType
    {
        return $this->activityType;
    }

    public function setActivityType(ActivityType $activityType): self
    {
        $this->activityType = $activityType;

        return $this;
    }

    public function getAlias(): string
    {
        return HistoryEntityType::ACTIVITY;
    }

    public function isDisabledForTrackableEvents(): bool
    {
        return false;
    }

    public function getActivityAt(): \DateTimeImmutable
    {
        return $this->activityAt;
    }

    public function setActivityAt(\DateTimeImmutable $activityAt): self
    {
        $this->activityAt = $activityAt;

        return $this;
    }

    public function getTechnology(): ?Technology
    {
        return $this->technology;
    }

    public function setTechnology(?Technology $technology = null): self
    {
        $this->technology = $technology;

        return $this;
    }

    public function getSource(): ?string
    {
        return $this->source;
    }

    public function setSource(?string $source): self
    {
        $this->source = $source;

        return $this;
    }

    /**
     * @Groups({ContextGroup::GROUP_ACTIVITY_READ})
     * @SerializedName("sourceHash")
     */
    public function getSourceHash(): ?string
    {
        if (null === $this->source) {
            return null;
        }

        $fragments = [];

        if (1 !== \preg_match(self::GIT_SHA_REGEX, $this->source, $fragments)) {
            return null;
        }

        return \current($fragments);
    }
}
