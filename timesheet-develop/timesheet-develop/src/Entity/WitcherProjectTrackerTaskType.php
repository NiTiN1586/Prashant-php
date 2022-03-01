<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Jagaad\WitcherApi\Entity\Traits\EntityAuthorTrait;
use Jagaad\WitcherApi\Enum\ContextGroup;
use Jagaad\WitcherApi\Security\ReadableResourceInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *     normalizationContext={"groups"={
 *          ContextGroup::GROUP_PROJECT_READ,
 *          ContextGroup::GROUP_GENERIC_TRAIT_READ,
 *          ContextGroup::GROUP_GENERAL_DICTIONARY_READ
 *     }},
 *     denormalizationContext={"groups"={ContextGroup::PROJECT_TRACKER_TASK_TYPE_WRITE}},
 *     itemOperations={"get", "patch", "delete"},
 *     graphql={"item_query", "collection_query", "update", "delete"}
 * )
 *
 * @ORM\Entity()
 *
 * @UniqueEntity(fields={"witcherProject", "trackerTaskType"}, message="Tracker task type has been defined for Witcher Project.")
 *
 * @ORM\Table(
 *     name="witcher_project_tracker_task_type",
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(name="UK_witcher_project_tracker_task_type_wP_tTT", columns={"witcher_project", "tracker_task_type"})
 *     }, indexes={
 *         @ORM\Index(name="IDX_witcher_project_deletedAt", columns={"deleted_at"}),
 *         @ORM\Index(name="IDX_witcher_project_tracker_task_type_wP_tTT", columns={"witcher_project", "tracker_task_type"}),
 *         @ORM\Index(name="IDX_witcher_project_tracker_task_type_trackerTaskType", columns={"tracker_task_type"}),
 *         @ORM\Index(name="IDX_witcher_project_tracker_task_type_witcherProject", columns={"witcher_project"}),
 *         @ORM\Index(name="IDX_witcher_project_tracker_task_type_createdBy", columns={"created_by"}),
 *         @ORM\Index(name="IDX_witcher_project_tracker_task_type_updatedBy", columns={"updated_by"}),
 *     }
 * )
 *
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 *
 * @ApiFilter(BooleanFilter::class, properties={"deletedAt", "isSubTaskLevel"})
 * @ApiFilter(SearchFilter::class, properties={"witcherProject.slug", "isSubTaskLevel", "trackerTaskType.friendlyName"})
 *
 * @ORM\HasLifecycleCallbacks
 */
class WitcherProjectTrackerTaskType implements EntityAuthorInterface, ReadableResourceInterface
{
    use EntityAuthorTrait;
    use SoftDeleteableEntity;
    use TimestampableEntity;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="id", type="integer")
     *
     * @ApiProperty(identifier=true)
     *
     * @Groups({ContextGroup::GROUP_PROJECT_READ})
     */
    private int $id;

    /**
     * @ORM\Column(name="sub_task_level", type="boolean", options={"default": false})
     *
     * @Assert\NotNull()
     *
     * @Groups({ContextGroup::GROUP_PROJECT_READ, ContextGroup::PROJECT_TRACKER_TASK_TYPE_WRITE})
     */
    private bool $isSubTaskLevel = false;

    /**
     * @ORM\ManyToOne(targetEntity=WitcherProject::class, inversedBy="witcherProjectTrackerTaskTypes")
     * @ORM\JoinColumn(name="witcher_project", referencedColumnName="id", onDelete="CASCADE", nullable=false)
     *
     * @Groups({ContextGroup::PROJECT_TRACKER_TASK_TYPE_READ, ContextGroup::PROJECT_TRACKER_TASK_TYPE_WRITE})
     */
    private WitcherProject $witcherProject;

    /**
     * @ORM\ManyToOne(targetEntity=TrackerTaskType::class)
     * @ORM\JoinColumn(name="tracker_task_type", referencedColumnName="id", onDelete="CASCADE", nullable=false)
     *
     * @Groups({ContextGroup::GROUP_GENERAL_DICTIONARY_READ, ContextGroup::PROJECT_TRACKER_TASK_TYPE_WRITE})
     */
    private TrackerTaskType $trackerTaskType;

    /**
     * @ORM\Column(name="display_order", type="integer", nullable=true)
     *
     * @Groups({ContextGroup::GROUP_PROJECT_READ, ContextGroup::PROJECT_TRACKER_TASK_TYPE_WRITE})
     */
    private ?int $displayOrder;

    public static function create(
        WitcherProject $project,
        TrackerTaskType $trackerTaskType,
        WitcherUser $createdBy,
        bool $isSubTaskLevel = false): self
    {
        $witcherProjectTrackerTaskType = new self();
        $witcherProjectTrackerTaskType->isSubTaskLevel = $isSubTaskLevel;
        $witcherProjectTrackerTaskType->trackerTaskType = $trackerTaskType;
        $witcherProjectTrackerTaskType->witcherProject = $project;
        $witcherProjectTrackerTaskType->createdBy = $createdBy;

        return $witcherProjectTrackerTaskType;
    }

    public function isSubTaskLevel(): bool
    {
        return $this->isSubTaskLevel;
    }

    public function setIsSubTaskLevel(bool $isSubTaskLevel): self
    {
        $this->isSubTaskLevel = $isSubTaskLevel;

        return $this;
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

    public function getTrackerTaskType(): TrackerTaskType
    {
        return $this->trackerTaskType;
    }

    public function setTrackerTaskType(TrackerTaskType $trackerTaskType): self
    {
        $this->trackerTaskType = $trackerTaskType;

        return $this;
    }

    public function getDisplayOrder(): ?int
    {
        return $this->displayOrder;
    }

    public function setDisplayOrder(?int $displayOrder): self
    {
        $this->displayOrder = $displayOrder;

        return $this;
    }
}
