<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Entity;

interface TrackableActivityInterface extends EntityAuthorInterface
{
    public function getAlias(): string;

    public function getTask(): Task;

    public function getId(): int;

    /**
     * @return \DateTime
     */
    public function getUpdatedAt();

    public function isDisabledForTrackableEvents(): bool;
}
