<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Integration\Domain\IssueTracker\Message;

use Jagaad\WitcherApi\Integration\Domain\IssueTracker\Message\Interfaces\TimeTrackerInterface;

class JiraTimeTrackerEvent implements TimeTrackerInterface
{
    public const ISSUE_CREATED = 'issue_created';
    public const ISSUE_UPDATED = 'issue_updated';
    public const ISSUE_DELETED = 'issue_deleted';

    public const PROJECT_CREATED = 'project_created';
    public const PROJECT_UPDATED = 'project_updated';
    public const PROJECT_DELETED = 'project_soft_deleted';

    public const USER_CREATED = 'user_created';
    public const USER_UPDATED = 'user_updated';
    public const USER_DELETED = 'user_deleted';

    public const SPRINT_CREATED = 'sprint_created';
    public const SPRINT_UPDATED = 'sprint_updated';
    public const SPRINT_DELETED = 'sprint_deleted';

    public const AVAILABLE_EVENT_TYPES = [
        self::ISSUE_UPDATED,
        self::ISSUE_DELETED,
        self::ISSUE_CREATED,
        self::PROJECT_UPDATED,
        self::PROJECT_DELETED,
        self::PROJECT_CREATED,
        self::USER_CREATED,
        self::USER_UPDATED,
        self::USER_DELETED,
        self::SPRINT_CREATED,
        self::SPRINT_UPDATED,
        self::SPRINT_DELETED,
    ];

    public const AVAILABLE_PROJECT_EVENT_TYPES = [
        self::PROJECT_UPDATED,
        self::PROJECT_DELETED,
        self::PROJECT_CREATED,
    ];

    public const AVAILABLE_ISSUE_EVENT_TYPES = [
        self::ISSUE_UPDATED,
        self::ISSUE_DELETED,
        self::ISSUE_CREATED,
    ];

    public const AVAILABLE_USER_EVENT_TYPES = [
        self::USER_CREATED,
        self::USER_UPDATED,
        self::USER_DELETED,
    ];

    public const AVAILABLE_SPRINT_EVENT_TYPES = [
        self::SPRINT_CREATED,
        self::SPRINT_UPDATED,
        self::SPRINT_DELETED,
    ];

    private string $handle;
    private string $event;
    private int $timestamp;

    /** @var array<string, mixed> */
    private array $params;

    /**
     * @param array<string, mixed> $params
     */
    public function __construct(string $handle, string $event, int $timestamp, array $params = [])
    {
        if (0 === $timestamp || \in_array('', [$handle, $event], true)) {
            throw new \InvalidArgumentException('handle, event, timestamp are required');
        }

        if (!\in_array($event, self::AVAILABLE_EVENT_TYPES, true)) {
            throw new \InvalidArgumentException(\sprintf('Event type \'%s\' doesn\'t exist', $event));
        }

        $this->event = $event;
        $this->handle = $handle;
        $this->timestamp = $timestamp;
        $this->params = $params;
    }

    public function getEvent(): string
    {
        return $this->event;
    }

    public function getHandle(): string
    {
        return $this->handle;
    }

    public function getTimestamp(): int
    {
        return $this->timestamp;
    }

    /**
     * @return array<string, mixed>
     */
    public function getParams(): array
    {
        return $this->params;
    }
}
