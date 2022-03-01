<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Integration\Domain\DTO;

final class EventContainer
{
    private object $message;
    private string $route;

    private function __construct(object $message, string $route)
    {
        $this->message = $message;
        $this->route = $route;
    }

    public static function create(object $message, string $route): self
    {
        return new self($message, $route);
    }

    public function getMessage(): object
    {
        return $this->message;
    }

    public function getRoute(): string
    {
        return $this->route;
    }
}
