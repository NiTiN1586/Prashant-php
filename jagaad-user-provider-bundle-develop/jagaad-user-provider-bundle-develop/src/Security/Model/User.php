<?php

declare(strict_types=1);

namespace Jagaad\UserProviderBundle\Security\Model;

use Jagaad\UserProviderBundle\Enum\ContextGroup;
use Jagaad\UserProviderBundle\Security\Model\Interfaces\ExtendedUserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

class User implements ExtendedUserInterface
{
    public const SEARCH_FIELD_EMAIL = 'invitationEmail';
    public const SEARCH_FIELD_USER_ID = 'id';

    /**
     * @Groups({ContextGroup::GROUP_JAGAAD_USER_DETAILS})
     */
    private int $id;

    /**
     * @var GoogleAccount[]
     *
     * @Groups({ContextGroup::GROUP_JAGAAD_USER_DETAILS})
     */
    private array $googleAccounts;

    /**
     * @Groups({ContextGroup::GROUP_JAGAAD_USER_DETAILS})
     */
    private string $invitationEmail;

    /**
     * @Groups({ContextGroup::GROUP_JAGAAD_USER_DETAILS})
     */
    private \DateTime $createdAt;

    /**
     * @Groups({ContextGroup::GROUP_JAGAAD_USER_DETAILS})
     */
    private ?\DateTime $lastLogin = null;

    /**
     * @Groups({ContextGroup::GROUP_JAGAAD_USER_DETAILS})
     */
    private bool $active;

    /**
     * @Groups({ContextGroup::GROUP_JAGAAD_USER_DETAILS})
     */
    private array $roles = [];

    /**
     * @Groups({ContextGroup::GROUP_JAGAAD_USER_DETAILS})
     */
    private array $permissions = [];

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getPassword()
    {
        return null;
    }

    public function getSalt()
    {
        return null;
    }

    public function getUsername()
    {
        return $this->getInvitationEmail();
    }

    public function eraseCredentials()
    {
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

    public function getGoogleAccounts(): array
    {
        return $this->googleAccounts;
    }

    public function setGoogleAccounts(array $googleAccounts): self
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

    public function setLastLogin(?\DateTime $lastLogin): self
    {
        $this->lastLogin = $lastLogin;

        return $this;
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

    /**
     * @param string[] $permissions
     *
     * @return self
     */
    public function setPermissions(array $permissions): self
    {
        $this->permissions = $permissions;

        return $this;
    }

    /**
     * @return string[]
    */
    public function getPermissions(): array
    {
        return $this->permissions;
    }
}
