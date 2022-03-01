<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Message;

interface SlackNotificationInterface
{
    public function getMessage(): string;

    /**
     * @return array<int, mixed>
     */
    public function getContext(): array;

    public function getLevel(): int;

    public function getLevelName(): string;

    public function getChannel(): string;

    public function getDatetime(): \DateTimeInterface;

    /**
     * @return array<int, mixed>
     */
    public function getExtra(): array;
}
