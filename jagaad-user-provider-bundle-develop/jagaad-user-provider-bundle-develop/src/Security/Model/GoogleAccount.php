<?php

declare(strict_types=1);

namespace Jagaad\UserProviderBundle\Security\Model;

use Jagaad\UserProviderBundle\Enum\ContextGroup;
use Symfony\Component\Serializer\Annotation\Groups;

class GoogleAccount
{
    /**
     * @Groups({ContextGroup::GROUP_JAGAAD_USER_DETAILS})
     */
    private string $googleAccountId;

    /**
     * @Groups({ContextGroup::GROUP_JAGAAD_USER_DETAILS})
     */
    private string $email;

    /**
     * @Groups({ContextGroup::GROUP_JAGAAD_USER_DETAILS})
     */
    private string $firstName;

    /**
     * @Groups({ContextGroup::GROUP_JAGAAD_USER_DETAILS})
     */
    private string $lastName;

    /**
     * @Groups({ContextGroup::GROUP_JAGAAD_USER_DETAILS})
     */
    private string $avatarUrl;

    public function getGoogleAccountId(): string
    {
        return $this->googleAccountId;
    }

    public function setGoogleAccountId(string $googleAccountId): self
    {
        $this->googleAccountId = $googleAccountId;

        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getAvatarUrl(): string
    {
        return $this->avatarUrl;
    }

    public function setAvatarUrl(string $avatarUrl): self
    {
        $this->avatarUrl = $avatarUrl;

        return $this;
    }
}

