<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Integration\Domain\GitManagement;

use Symfony\Component\Validator\Constraints as Assert;

final class Branch
{
    /**
     * @Assert\NotBlank()
     */
    private string $name;
    private bool $merged = false;

    /**
     * @Assert\NotBlank()
     */
    private string $webUrl;

    public static function create(string $name, string $url, bool $merged = false): self
    {
        return (new self())
            ->setName($name)
            ->setMerged($merged)
            ->setWebUrl($url);
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function setMerged(bool $merged): self
    {
        $this->merged = $merged;

        return $this;
    }

    public function setWebUrl(string $webUrl): self
    {
        $this->webUrl = $webUrl;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function isMerged(): bool
    {
        return $this->merged;
    }

    public function getWebUrl(): string
    {
        return $this->webUrl;
    }
}
