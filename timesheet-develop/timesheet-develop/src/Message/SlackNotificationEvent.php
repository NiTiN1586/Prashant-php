<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Message;

class SlackNotificationEvent implements SlackNotificationInterface
{
    private string $message;

    /** @var array<int, mixed> */
    private array $context;

    private int $level;

    private string $levelName;

    private string $channel;

    private \DateTimeInterface $datetime;

    /** @var array<int, mixed> */
    private array $extra;

    /**
     * @param array<int, mixed> $context
     * @param array<int, mixed> $extra
     */
    public function __construct(
        string $message,
        array $context,
        int $level,
        string $levelName,
        \DateTimeInterface $datetime,
        string $channel,
        array $extra = []
    ) {
        $this->message = $message;
        $this->context = $context;
        $this->level = $level;
        $this->levelName = $levelName;
        $this->channel = $channel;
        $this->datetime = $datetime;
        $this->extra = $extra;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @return array<int, mixed>
     */
    public function getContext(): array
    {
        return $this->context;
    }

    public function getLevel(): int
    {
        return $this->level;
    }

    public function getLevelName(): string
    {
        return $this->levelName;
    }

    public function getChannel(): string
    {
        return $this->channel;
    }

    public function getDatetime(): \DateTimeInterface
    {
        return $this->datetime;
    }

    /**
     * @return array<int, mixed>
     */
    public function getExtra(): array
    {
        return $this->extra;
    }
}
