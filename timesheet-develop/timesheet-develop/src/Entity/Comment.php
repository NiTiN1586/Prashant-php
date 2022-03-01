<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
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
 *     normalizationContext={"groups"={
 *          ContextGroup::GROUP_TASK_COMMENT_READ,
 *          ContextGroup::GROUP_GENERIC_TRAIT_READ,
 *          ContextGroup::GROUP_TASK_READ,
 *          ContextGroup::GROUP_WITCHER_USER_READ,
 *          ContextGroup::GROUP_ACTIVITY_READ
 *     }},
 *     denormalizationContext={"groups"={ContextGroup::GROUP_TASK_COMMENT_WRITE}},
 *     graphql={
 *          "get",
 *          "create",
 *         "update"={
 *             "denormalization_context"={"groups"={ContextGroup::GROUP_ALLOW_MODIFY}}
 *          },
 *         "delete"
 *     },
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
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(
 *     name="comment",
 *     indexes={
 *         @ORM\Index(name="IDX_comment_createdAt", fields={"createdAt"}),
 *         @ORM\Index(name="IDX_comment_deletedAt", fields={"deletedAt"}),
 *         @ORM\Index(name="IDX_comment_task", fields={"task"}),
 *         @ORM\Index(name="IDX_comment_updatedBy", fields={"updatedBy"}),
 *         @ORM\Index(name="IDX_comment_createdBy", fields={"createdBy"}),
 *     }
 * )
 *
 * @ApiFilter(DateFilter::class, properties={"createdAt"})
 * @ApiFilter(OrderFilter::class, properties={"createdAt"})
 *
 * @IgnoreFilter(filter="softdeleteable", forClasses={WitcherUser::class})
 *
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class Comment implements TrackableActivityInterface
{
    use EntityAuthorTrait;
    use SoftDeleteableEntity;
    use TimestampableEntity;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="id", type="integer")
     *
     * @Groups({ContextGroup::GROUP_TASK_COMMENT_READ})
     */
    private int $id;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *             "type"="string",
     *             "example"="WITCHER-1"
     *         }
     *     }
     * )
     *
     * @ORM\ManyToOne(targetEntity=Task::class, inversedBy="comments")
     * @ORM\JoinColumn(name="task", referencedColumnName="id", onDelete="CASCADE", nullable=false)
     *
     * @Groups({ContextGroup::GROUP_ACTIVITY_READ, ContextGroup::GROUP_TASK_COMMENT_WRITE})
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
     * @ORM\Column(name="comment", type="text", length=65535, nullable=false)
     *
     * @Assert\NotBlank(message="Commnet is required")
     *
     * @Assert\Length(
     *      max=65535,
     *      maxMessage="Task comment cannot be longer than {{ limit }} characters",
     * )
     *
     * @Groups({
     *     ContextGroup::GROUP_ALLOW_MODIFY,
     *     ContextGroup::GROUP_TASK_COMMENT_WRITE,
     *     ContextGroup::GROUP_TASK_COMMENT_READ
     *  })
     */
    private string $comment;

    public function getId(): int
    {
        return $this->id;
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

    public function getComment(): string
    {
        return $this->comment;
    }

    public function setComment(string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getAlias(): string
    {
        return HistoryEntityType::COMMENT;
    }

    public function isDisabledForTrackableEvents(): bool
    {
        return false;
    }

    /**
     * @Groups({ContextGroup::GROUP_TASK_READ})
     * @SerializedName("createdAt")
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }
}
