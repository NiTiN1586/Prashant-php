<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Jagaad\WitcherApi\Entity\Traits\EntityAuthorTrait;
use Jagaad\WitcherApi\Enum\ContextGroup;
use Jagaad\WitcherApi\Validator as App;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *     normalizationContext={"groups"={
 *          ContextGroup::GROUP_TEAM_READ,
 *          ContextGroup::GROUP_WITCHER_USER_READ,
 *          ContextGroup::GROUP_GENERIC_TRAIT_READ
 *     }},
 *     denormalizationContext={"groups"={ContextGroup::GROUP_TEAM_WRITE}},
 *     itemOperations={
 *       "get",
 *       "patch",
 *       "delete"
 *     }
 * )
 *
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(
 *     name="team",
 *     indexes={
 *         @ORM\Index(name="IDX_team_witcherProject", fields={"witcherProject"}),
 *         @ORM\Index(name="IDX_team_teamLeader", fields={"teamLeader"}),
 *         @ORM\Index(name="IDX_team_createdAt", fields={"createdAt"}),
 *         @ORM\Index(name="IDX_team_createdBy", fields={"createdBy"}),
 *         @ORM\Index(name="IDX_team_updatedBy", fields={"updatedBy"}),
 *         @ORM\Index(name="IDX_team_updatedAt", fields={"updatedAt"}),
 *     }
 * )
 */
class Team implements EntityAuthorInterface
{
    use EntityAuthorTrait;
    use SoftDeleteableEntity;
    use TimestampableEntity;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="id", type="integer")
     *
     * @Groups({ContextGroup::GROUP_GENERAL_DICTIONARY_READ})
     */
    private int $id;

    /**
     * @ORM\Column(name="name", type="string", length=100)
     *
     * @Assert\Length(
     *      max=100,
     *      maxMessage="Team name cannot be longer than {{ limit }} characters",
     * )
     *
     * @Groups({ContextGroup::GROUP_TEAM_READ, ContextGroup::GROUP_TEAM_WRITE})
     */
    private string $name;

    /**
     * @ORM\ManyToOne(targetEntity=WitcherProject::class, inversedBy="teams")
     * @ORM\JoinColumn(name="witcher_project", referencedColumnName="id", nullable=false)
     *
     * @Groups({ContextGroup::GROUP_TEAM_READ, ContextGroup::GROUP_TEAM_WRITE})
     */
    private WitcherProject $witcherProject;

    /**
     * @var Collection<int, WitcherUser>
     *
     * @ORM\ManyToMany(targetEntity=WitcherUser::class, cascade={"persist", "remove"})
     * @ORM\JoinTable(name="witcher_user_team",
     *     joinColumns={
     *          @ORM\JoinColumn(name="team", referencedColumnName="id", nullable=false)
     *      },
     *     inverseJoinColumns={
     *          @ORM\JoinColumn(name="witcher_user", referencedColumnName="id", nullable=false)
     *      }
     * )
     *
     * @App\TeamUniqueCollection
     *
     * @Groups({ContextGroup::GROUP_WITCHER_USER_READ, ContextGroup::GROUP_TEAM_WRITE})
     */
    private Collection $teamMembers;

    /**
     * @ORM\ManyToOne(targetEntity=WitcherUser::class)
     * @ORM\JoinColumn(name="team_leader", referencedColumnName="id")
     *
     * @Groups({ContextGroup::GROUP_WITCHER_USER_READ, ContextGroup::GROUP_TEAM_WRITE})
     */
    private ?WitcherUser $teamLeader = null;

    public function __construct()
    {
        $this->teamMembers = new ArrayCollection();
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

    public function getWitcherProject(): WitcherProject
    {
        return $this->witcherProject;
    }

    public function setWitcherProject(WitcherProject $witcherProject): self
    {
        $this->witcherProject = $witcherProject;

        return $this;
    }

    /**
     * @return Collection<int, WitcherUser>
     */
    public function getTeamMembers(): Collection
    {
        return $this->teamMembers;
    }

    /**
     * @param Collection<int, WitcherUser>|array<int, WitcherUser> $teamMembers
     */
    public function setTeamMembers(Collection|array $teamMembers): self
    {
        if ($teamMembers instanceof Collection) {
            $this->teamMembers = $teamMembers;

            return $this;
        }

        $this->teamMembers = new ArrayCollection($teamMembers);

        return $this;
    }

    public function addTeamMembers(WitcherUser $teamMember): self
    {
        if (!$this->teamMembers->contains($teamMember)) {
            $this->teamMembers->add($teamMember);
        }

        return $this;
    }

    public function deleteTeamMembers(WitcherUser $teamMember): self
    {
        if ($this->teamMembers->contains($teamMember)) {
            $this->teamMembers->removeElement($teamMember);
        }

        return $this;
    }

    public function getTeamLeader(): ?WitcherUser
    {
        return $this->teamLeader;
    }

    public function setTeamLeader(?WitcherUser $teamLeader): self
    {
        $this->teamLeader = $teamLeader;

        return $this;
    }
}
