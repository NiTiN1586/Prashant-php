<?php

declare(strict_types=1);

namespace Jagaad\UserApi\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Jagaad\UserApi\Enum\ContextGroup;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *     normalizationContext={"groups"={ContextGroup::USER_READ, ContextGroup::USER_ADDRESS_READ, ContextGroup::USER_PROFILE_READ}},
 *     denormalizationContext={"groups"={ContextGroup::USER_WRITE}},
 * )
 *
 * @ORM\Entity()
 * @ORM\Table(
 *     name="user",
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(name="UK_user_invitationEmail", columns={"invitation_email"}),
 *     }
 * )
 *
 * @ApiFilter(SearchFilter::class, properties={"invitationEmail": "exact", "id": "exact"})
 * @ApiFilter(BooleanFilter::class, properties={"active"})
 */
class User
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="id", type="integer", nullable=false)
     *
     * @Groups({ContextGroup::USER_READ, ContextGroup::USER_ADDRESS_READ, ContextGroup::USER_PROFILE_READ})
     */
    private int $id;

    /**
     * @var Collection<int, GoogleAccount>
     *
     * @ORM\OneToMany(targetEntity=GoogleAccount::class, mappedBy="user", cascade={"persist", "remove"})
     *
     * @Groups({ContextGroup::USER_READ})
     */
    private Collection $googleAccounts;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *             "type"="string",
     *             "example"="mail@mail.mail"
     *         }
     *     }
     * )
     *
     * @ORM\Column(name="invitation_email", type="string", length=40, nullable=false, unique=true)
     *
     * @Assert\NotNull()
     * @Assert\Length(
     *      max = 40,
     *      maxMessage = "User invitation email cannot be longer than {{ limit }} characters",
     * )
     * @Assert\Email()
     *
     * @Groups({ContextGroup::USER_READ, ContextGroup::USER_ADDRESS_READ, ContextGroup::USER_PROFILE_READ, ContextGroup::USER_WRITE})
     */
    private string $invitationEmail;

    /**
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     *
     * @Groups({ContextGroup::USER_READ, ContextGroup::USER_ADDRESS_READ, ContextGroup::USER_PROFILE_READ})
     */
    private \DateTime $createdAt;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *             "type"="string",
     *             "example"=null
     *         }
     *     }
     * )
     * @ORM\Column(name="last_login", type="datetime", nullable=true)
     *
     * @Groups({ContextGroup::USER_READ})
     */
    private ?\DateTime $lastLogin = null;

    /**
     * @ORM\Column(name="active", type="boolean", options={"default": true})
     *
     * @Assert\NotNull()
     *
     * @Groups({ContextGroup::USER_READ, ContextGroup::USER_ADDRESS_READ, ContextGroup::USER_PROFILE_READ, ContextGroup::USER_WRITE})
     */
    private bool $active;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->active = true;
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

    /**
     * @return Collection<int, GoogleAccount>
     */
    public function getGoogleAccounts(): Collection
    {
        return $this->googleAccounts;
    }

    /**
     * @param Collection<int, GoogleAccount> $googleAccounts
     */
    public function setGoogleAccounts(Collection $googleAccounts): self
    {
        $this->googleAccounts = $googleAccounts;

        return $this;
    }

    public function getInvitationEmail(): string
    {
        return $this->invitationEmail;
    }

    public function setInvitationEmail(string $invitationEmail): self
    {
        $this->invitationEmail = $invitationEmail;

        return $this;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getLastLogin(): ?\DateTime
    {
        return $this->lastLogin;
    }

    public function setLastLogin(?\DateTime $lastLogin): void
    {
        $this->lastLogin = $lastLogin;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }
}
