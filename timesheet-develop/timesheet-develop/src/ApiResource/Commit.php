<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\ApiResource;

use ApiPlatform\Core\Action\NotFoundAction;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Jagaad\WitcherApi\Enum\ContextGroup;
use Jagaad\WitcherApi\Filter\GitBranchFilter;
use Jagaad\WitcherApi\Filter\GitProjectFilter;
use Jagaad\WitcherApi\Security\ReadableResourceInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *     routePrefix="/v1",
 *     normalizationContext={"groups"={ContextGroup::GROUP_GIT_COMMIT_READ}},
 *     itemOperations={"get"={
 *          "method"="GET",
 *          "controller"=NotFoundAction::class,
 *          "read"=false,
 *          "output"=false,
 *     }},
 *     collectionOperations={"get"={"path"="/gitlab/commits"}},
 *     graphql={"collection_query"}
 * )
 *
 * @ApiFilter(GitProjectFilter::class)
 * @ApiFilter(GitBranchFilter::class)
 */
class Commit implements ReadableResourceInterface
{
    /**
     * @Assert\NotBlank()
     * @ApiProperty(identifier=true)
     *
     * @Groups({ContextGroup::GROUP_GIT_COMMIT_READ})
     */
    private string $id;

    /**
     * @Assert\NotBlank()
     *
     * @Groups({ContextGroup::GROUP_GIT_COMMIT_READ})
     */
    private string $shortId;

    /**
     * @Assert\NotBlank()
     *
     * @Groups({ContextGroup::GROUP_GIT_COMMIT_READ})
     */
    private string $title;

    /**
     * @Assert\NotBlank()
     *
     * @Groups({ContextGroup::GROUP_GIT_COMMIT_READ})
     */
    private string $message;

    /**
     * @Assert\NotNull()
     *
     * @Groups({ContextGroup::GROUP_GIT_COMMIT_READ})
     */
    private \DateTime $createdAt;

    /**
     * @Assert\NotNull()
     *
     * @Groups({ContextGroup::GROUP_GIT_COMMIT_READ})
     */
    private \DateTime $committedAt;

    /**
     * @Assert\NotBlank()
     *
     * @Groups({ContextGroup::GROUP_GIT_COMMIT_READ})
     */
    private string $url;

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getShortId(): string
    {
        return $this->shortId;
    }

    public function setShortId(string $shortId): self
    {
        $this->shortId = $shortId;

        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getCommittedAt(): \DateTime
    {
        return $this->committedAt;
    }

    public function setCommittedAt(\DateTime $committedAt): self
    {
        $this->committedAt = $committedAt;

        return $this;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }
}
