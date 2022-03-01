<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Entity;

interface EntityAuthorInterface
{
    public function getCreatedBy(): WitcherUser;

    public function setCreatedBy(WitcherUser $createdBy): object;

    public function getUpdatedBy(): ?WitcherUser;

    public function setUpdatedBy(?WitcherUser $updatedBy): object;

    public function getCreatedByUserId(): int;

    public function getUpdatedByUserId(): ?int;

    public function isCreatedByPopulated(): bool;
}
