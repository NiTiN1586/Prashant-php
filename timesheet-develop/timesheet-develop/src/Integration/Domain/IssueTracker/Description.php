<?php

namespace Jagaad\WitcherApi\Integration\Domain\IssueTracker;

class Description
{
    private string $type = 'doc';
    private int $version = 1;

    /** @var ContentField[]|null */
    private ?array $content = null;

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getVersion(): int
    {
        return $this->version;
    }

    public function setVersion(int $version): self
    {
        $this->version = $version;

        return $this;
    }

    /**
     * @return ContentField[]|null
     */
    public function getContent(): ?array
    {
        return $this->content;
    }

    /**
     * @param ContentField[]|null $content
     */
    public function setContent(?array $content): self
    {
        $this->content = $content;

        return $this;
    }
}
