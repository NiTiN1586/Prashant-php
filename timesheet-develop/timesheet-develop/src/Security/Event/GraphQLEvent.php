<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Security\Event;

use Symfony\Contracts\EventDispatcher\Event;

final class GraphQLEvent extends Event
{
    private ?object $data;
    private string $resourceClass;
    private string $operationName;

    /** @var array<string, mixed> */
    private array $context;

    /**
     * @var mixed[]
     */
    private array $originalData;

    /**
     * @param array<string, mixed> $context
     * @param array<string, mixed> $originalData
     */
    public function __construct(?object $data, string $resourceClass, string $operationName, array $context, array $originalData = [])
    {
        $this->data = $data;
        $this->resourceClass = $resourceClass;
        $this->operationName = $operationName;
        $this->context = $context;
        $this->originalData = $originalData;
    }

    public function getData(): ?object
    {
        return $this->data;
    }

    public function getResourceClass(): string
    {
        return $this->resourceClass;
    }

    public function getOperationName(): string
    {
        return $this->operationName;
    }

    /**
     * @return mixed[]
     */
    public function getContext(): array
    {
        return $this->context;
    }

    /**
     * @return mixed[]
     */
    public function getOriginalData(): array
    {
        return $this->originalData;
    }

    /**
     * @param mixed[] $originalData
     */
    public function setOriginalData(array $originalData): self
    {
        $this->originalData = $originalData;

        return $this;
    }
}
