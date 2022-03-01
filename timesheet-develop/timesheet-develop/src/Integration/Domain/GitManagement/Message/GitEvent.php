<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Integration\Domain\GitManagement\Message;

use Jagaad\WitcherApi\Integration\Domain\GitManagement\Message\Interfaces\GitEventInterface;

final class GitEvent implements GitEventInterface
{
    public const BRANCH_CREATED = 'branch_created';
    public const BRANCH_DELETED = 'branch_deleted';

    public const AVAILABLE_EVENT_TYPES = [
        self::BRANCH_CREATED,
        self::BRANCH_DELETED,
    ];

    private string $event;
    private int $project;
    private string $branch;

    public function __construct(string $event, int $project, string $branch)
    {
        if (0 === $project || \in_array('', [$event, $branch], true)) {
            throw new \InvalidArgumentException('project, branch, timestamp are required');
        }

        if (!\in_array($event, self::AVAILABLE_EVENT_TYPES, true)) {
            throw new \InvalidArgumentException('Incorrect Git Event passed');
        }

        $this->event = $event;
        $this->project = $project;
        $this->branch = $branch;
    }

    public function getEvent(): string
    {
        return $this->event;
    }

    public function getProject(): int
    {
        return $this->project;
    }

    public function getBranch(): string
    {
        return $this->branch;
    }
}
