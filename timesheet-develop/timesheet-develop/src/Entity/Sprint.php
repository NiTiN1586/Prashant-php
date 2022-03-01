<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Jagaad\WitcherApi\Enum\ContextGroup;
use Jagaad\WitcherApi\Integration\Domain\IssueTracker\Sprint as SprintDTO;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *     attributes={"order":{"createdAt": "DESC"}},
 *     normalizationContext={"groups"={
 *          ContextGroup::GROUP_SPRINT_READ,
 *          ContextGroup::GROUP_ALLOW_MODIFY,
 *     }},
 *     denormalizationContext={"groups"={ContextGroup::GROUP_SPRINT_WRITE}},
 *     itemOperations={
 *          "get",
 *          "patch"={
 *             "denormalization_context"={"groups"={ContextGroup::GROUP_ALLOW_MODIFY}}
 *          }
 *     },
 *     graphql={
 *          "item_query",
 *          "collection_query",
 *          "create",
 *          "update"={
 *             "denormalization_context"={"groups"={ContextGroup::GROUP_ALLOW_MODIFY}}
 *          }
 *     }
 * )
 *
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(
 *     name="sprint",
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(name="UK_sprint_externalId", fields={"externalId"}),
 *     },
 *     indexes={
 *         @ORM\Index(name="IDX_sprint_witcherProject", fields={"witcherProject"}),
 *         @ORM\Index(name="IDX_sprint_createdAt", fields={"createdAt"}),
 *         @ORM\Index(name="IDX_sprint_deletedAt", fields={"deletedAt"}),
 *         @ORM\Index(name="IDX_sprint_closed", fields={"closed"}),
 *         @ORM\Index(name="IDX_sprint_startedAt_endedAt", fields={"startedAt", "endedAt"}),
 *         @ORM\Index(name="IDX_sprint_endedAt", fields={"endedAt"}),
 *         @ORM\Index(name="IDX_sprint_completedAt", fields={"completedAt"}),
 *         @ORM\Index(name="IDX_sprint_externalId", fields={"externalId"}),
 *     }
 * )
 *
 * @UniqueEntity(fields={"externalId"}, message="Sprint with such external key already exists")
 *
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 *
 * @ApiFilter(SearchFilter::class, properties={
 *     "witcherProject.slug":"exact",
 *     "id": "exact",
 * })
 *
 * @ApiFilter(DateFilter::class, properties={"startedAt", "endedAt", "completedAt"})
 * @ApiFilter(BooleanFilter::class, properties={"closed"})
 * @ApiFilter(OrderFilter::class, properties={"witcherProject.slug", "startedAt", "endedAt", "completedAt", "closed"})
 */
class Sprint
{
    use SoftDeleteableEntity;
    use TimestampableEntity;

    public const STATE_CLOSED = 'closed';

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="id", type="integer")
     *
     * @Groups({ContextGroup::GROUP_SPRINT_READ})
     */
    private int $id;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *             "type"="string",
     *             "example"="Sprint 1"
     *         }
     *     }
     * )
     *
     * @ORM\Column(name="name", type="string", nullable=false)
     *
     * @Assert\NotBlank()
     *
     * @Groups({
     *     ContextGroup::GROUP_SPRINT_READ,
     *     ContextGroup::GROUP_SPRINT_WRITE,
     *     ContextGroup::GROUP_ALLOW_MODIFY
     * })
     */
    private string $name;

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
     * @ORM\Column(name="started_at", type="datetime_immutable", nullable=true)
     *
     * @Groups({
     *     ContextGroup::GROUP_SPRINT_READ,
     *     ContextGroup::GROUP_SPRINT_WRITE,
     *     ContextGroup::GROUP_ALLOW_MODIFY
     * })
     */
    private ?\DateTimeImmutable $startedAt = null;

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
     * @ORM\Column(name="ended_at", type="datetime_immutable", nullable=true)
     *
     * @Groups({
     *     ContextGroup::GROUP_SPRINT_READ,
     *     ContextGroup::GROUP_SPRINT_WRITE,
     *     ContextGroup::GROUP_ALLOW_MODIFY
     *  })
     */
    private ?\DateTimeImmutable $endedAt = null;

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
     * @ORM\Column(name="completed_at", type="datetime_immutable", nullable=true)
     *
     * @Groups({
     *     ContextGroup::GROUP_SPRINT_READ,
     *     ContextGroup::GROUP_SPRINT_WRITE,
     *     ContextGroup::GROUP_ALLOW_MODIFY
     *  })
     */
    private ?\DateTimeImmutable $completedAt = null;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *             "type"="text",
     *             "example"="Sprint description"
     *         }
     *     }
     * )
     *
     * @ORM\Column(name="description", type="text", length=2000, nullable=true)
     *
     * @Assert\Length(
     *      max=2000,
     *      maxMessage="Sprint description cannot be longer than {{ limit }} characters",
     * )
     *
     * @Groups({
     *     ContextGroup::GROUP_SPRINT_READ,
     *     ContextGroup::GROUP_SPRINT_WRITE,
     *     ContextGroup::GROUP_ALLOW_MODIFY
     *  })
     */
    private ?string $description = null;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *             "type"="boolean",
     *             "example"=false
     *         }
     *     }
     * )
     *
     * @ORM\Column(name="closed", type="boolean", options={"default": false})
     *
     * @Groups({
     *     ContextGroup::GROUP_SPRINT_READ,
     *     ContextGroup::GROUP_SPRINT_WRITE,
     *     ContextGroup::GROUP_ALLOW_MODIFY
     *  })
     */
    private bool $closed = false;

    /**
     * @ORM\ManyToOne(targetEntity=WitcherProject::class, cascade={"persist"})
     * @ORM\JoinColumn(name="witcher_project", referencedColumnName="id", onDelete="CASCADE", nullable=false)
     *
     * @Groups({ContextGroup::GROUP_SPRINT_READ, ContextGroup::GROUP_SPRINT_WRITE})
     */
    private WitcherProject $witcherProject;

    /**
     * @ORM\Column(name="external_id", type="integer", nullable=true)
     */
    private ?int $externalId = null;

    public static function create(
        string $name,
        WitcherProject $witcherProject,
        ?\DateTimeImmutable $startedAt = null,
        ?\DateTimeImmutable $endedAt = null,
        ?\DateTimeImmutable $completedAt = null,
        ?int $externalId = null,
        ?string $description = null,
        bool $closed = false
    ): self {
        $sprint = new self();
        $sprint->setName($name);
        $sprint->setWitcherProject($witcherProject);
        $sprint->setStartedAt($startedAt);
        $sprint->setEndedAt($endedAt);
        $sprint->setCompletedAt($completedAt);
        $sprint->setDescription($description);
        $sprint->setClosed($closed);
        $sprint->setExternalId($externalId);

        return $sprint;
    }

    public static function createFromDTO(SprintDTO $sprint, WitcherProject $project): self
    {
        return self::create(
            $sprint->getName(),
            $project,
            $sprint->getStartDate(),
            $sprint->getEndDate(),
            $sprint->getCompleteDate(),
            $sprint->getId(),
            $sprint->getGoal(),
            $sprint->isClosed()
        );
    }

    public function updateFromDTO(SprintDTO $sprint): self
    {
        $this->setEndedAt($sprint->getEndDate());
        $this->setStartedAt($sprint->getStartDate());
        $this->setClosed($sprint->isClosed());
        $this->setCompletedAt($sprint->getCompleteDate());
        $this->setDescription($sprint->getGoal());

        return $this;
    }

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

    public function getStartedAt(): ?\DateTimeImmutable
    {
        return $this->startedAt;
    }

    public function setStartedAt(?\DateTimeImmutable $startedAt): self
    {
        $this->startedAt = $startedAt;

        return $this;
    }

    public function getEndedAt(): ?\DateTimeImmutable
    {
        return $this->endedAt;
    }

    public function setEndedAt(?\DateTimeImmutable $endedAt): self
    {
        $this->endedAt = $endedAt;

        return $this;
    }

    public function getCompletedAt(): ?\DateTimeImmutable
    {
        return $this->completedAt;
    }

    public function setCompletedAt(?\DateTimeImmutable $completedAt): self
    {
        $this->completedAt = $completedAt;

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

    public function isClosed(): bool
    {
        return $this->closed;
    }

    public function setClosed(bool $closed): self
    {
        $this->closed = $closed;

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

    public function getExternalId(): ?int
    {
        return $this->externalId;
    }

    public function setExternalId(?int $externalId): self
    {
        $this->externalId = $externalId;

        return $this;
    }
}
