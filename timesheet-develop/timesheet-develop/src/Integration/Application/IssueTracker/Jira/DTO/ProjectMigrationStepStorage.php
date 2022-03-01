<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Integration\Application\IssueTracker\Jira\DTO;

use Assert\Assertion;
use Jagaad\WitcherApi\Entity\TrackerTaskType;
use Jagaad\WitcherApi\Entity\WitcherUser;
use Symfony\Component\Validator\Constraints as Assert;

final class ProjectMigrationStepStorage
{
    private int $buffer = 0;

    /**
     * @var TrackerTaskType[]
     *
     * @Assert\NotNull()
     * @Assert\Count(min=1, minMessage="Please run issueType migration first")
     */
    private array $trackerTaskTypes = [];

    /**
     * @var WitcherUser[]
     *
     * @Assert\NotNull()
     */
    private array $owners = [];

    public function increase(): self
    {
        ++$this->buffer;

        return $this;
    }

    public function resetBuffer(): self
    {
        $this->buffer = 0;

        return $this;
    }

    public function getBuffer(): int
    {
        return $this->buffer;
    }

    /**
     * @return TrackerTaskType[]
     */
    public function getTrackerTaskTypes(): array
    {
        return $this->trackerTaskTypes;
    }

    /**
     * @param TrackerTaskType[] $trackerTaskTypes
     */
    public function setTrackerTaskTypes(array $trackerTaskTypes): self
    {
        Assertion::allIsInstanceOf($trackerTaskTypes, TrackerTaskType::class, 'Argument of invalid type passed');

        $this->trackerTaskTypes = $trackerTaskTypes;

        return $this;
    }

    /**
     * @return WitcherUser[]
     */
    public function getOwners(): array
    {
        return $this->owners;
    }

    public function getOwner(string $accountId): ?WitcherUser
    {
        return $this->owners[$accountId] ?? null;
    }

    /**
     * @param WitcherUser[] $owners
     */
    public function setOwners(array $owners): self
    {
        Assertion::allIsInstanceOf($owners, WitcherUser::class, 'Argument of invalid type passed');

        $this->owners = $owners;

        return $this;
    }

    public function getTrackerTaskTypeByName(string $name): ?TrackerTaskType
    {
        return $this->trackerTaskTypes[$name] ?? null;
    }

    public function appendTrackerTaskType(TrackerTaskType $trackerTaskType): self
    {
        $this->trackerTaskTypes[$trackerTaskType->getFriendlyName()] = $trackerTaskType;

        return $this;
    }
}
