<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;
use Jagaad\WitcherApi\Entity\WitcherUser;
use Jagaad\WitcherApi\Enum\ContextGroup;
use Symfony\Component\Serializer\Annotation\Groups;

trait EntityAuthorTrait
{
    /**
     * @ORM\ManyToOne(targetEntity=WitcherUser::class, cascade={"persist"})
     * @ORM\JoinColumn(name="created_by", referencedColumnName="id", nullable=false)
     *
     * @Groups({ContextGroup::GROUP_WITCHER_USER_READ})
     */
    protected WitcherUser $createdBy;

    /**
     * @ORM\ManyToOne(targetEntity=WitcherUser::class, cascade={"persist"})
     * @ORM\JoinColumn(name="updated_by", referencedColumnName="id")
     *
     * @Groups({ContextGroup::GROUP_WITCHER_USER_READ})
     */
    protected ?WitcherUser $updatedBy = null;

    public function getCreatedBy(): WitcherUser
    {
        return $this->createdBy;
    }

    public function setCreatedBy(WitcherUser $createdBy): self
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    public function getUpdatedBy(): ?WitcherUser
    {
        return $this->updatedBy;
    }

    public function setUpdatedBy(?WitcherUser $updatedBy): self
    {
        $this->updatedBy = $updatedBy;

        return $this;
    }

    public function getCreatedByUserId(): int
    {
        return $this->createdBy->getUserId();
    }

    public function getUpdatedByUserId(): ?int
    {
        return null !== $this->updatedBy ? $this->updatedBy->getUserId() : null;
    }

    public function isCreatedByPopulated(): bool
    {
        return isset($this->createdBy);
    }
}
