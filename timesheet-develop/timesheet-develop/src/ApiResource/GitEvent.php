<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\ApiResource;

use ApiPlatform\Core\Action\NotFoundAction;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Jagaad\WitcherApi\Entity\WitcherUser;
use Jagaad\WitcherApi\Enum\ContextGroup;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *     routePrefix="/v1",
 *     normalizationContext={"groups"={ContextGroup::GROUP_PROJECT_EVENT_READ}},
 *     itemOperations={
 *         "get"={
 *             "method"="GET",
 *             "controller"=NotFoundAction::class,
 *             "read"=false,
 *             "output"=false,
 *         },
 *     },
 *     collectionOperations={"get"={
 *          "path"="/git_management/events/{targetTypeId}/{eventType}",
 *          "openapi_context"={
 *              "parameters"={
 *                  {
 *                      "name"="targetTypeId",
 *                      "in"="path",
 *                      "description"="Target Type Id",
 *                      "required"=true,
 *                      "schema"={
 *                          "type": "string",
 *                          "example"="23251658"
 *                      },
 *                      "style"="simple"
 *                  },
 *                  {
 *                      "name"="eventType",
 *                      "in"="path",
 *                      "description"="Event Type",
 *                      "required"=true,
 *                      "schema"={
 *                          "type": "string",
 *                          "example"="USER"
 *                      },
 *                      "style"="simple"
 *                  },
 *                  {
 *                      "name"="after",
 *                      "in"="query",
 *                      "description"="After",
 *                      "required"=false,
 *                      "schema"={
 *                          "type": "date",
 *                          "example"="2021-08-22"
 *                      },
 *                      "style"="simple"
 *                  },
 *                  {
 *                      "name"="before",
 *                      "in"="query",
 *                      "description"="Before",
 *                      "required"=false,
 *                      "schema"={
 *                          "type": "date",
 *                          "example"="2021-08-21"
 *                      },
 *                      "style"="simple"
 *                  }
 *              }
 *          }
 *       }
 *   }
 * )
 */
final class GitEvent
{
    /**
     * @ApiProperty(identifier=true)
     *
     * @Groups({ContextGroup::GROUP_PROJECT_EVENT_READ})
     */
    private int $id;

    /**
     * @Groups({ContextGroup::GROUP_PROJECT_EVENT_READ})
     */
    private int $projectId;

    /**
     * @Assert\NotBlank()
     *
     * @Groups({ContextGroup::GROUP_PROJECT_EVENT_READ})
     */
    private string $action;

    /**
     * @Groups({ContextGroup::GROUP_PROJECT_EVENT_READ})
     */
    private ?string $title = null;

    /**
     * @Groups({ContextGroup::GROUP_PROJECT_EVENT_READ})
     */
    private ?string $targetType;

    /**
     * @Groups({ContextGroup::GROUP_PROJECT_EVENT_READ})
     */
    private ?WitcherUser $author = null;

    /**
     * @Groups({ContextGroup::GROUP_PROJECT_EVENT_READ})
     */
    private \DateTime $createdAt;

    /**
     * @Groups({ContextGroup::GROUP_PROJECT_EVENT_READ})
     */
    private ?string $content = null;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getAction(): string
    {
        return $this->action;
    }

    public function setAction(string $action): self
    {
        $this->action = $action;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getTargetType(): ?string
    {
        return $this->targetType;
    }

    public function setTargetType(?string $targetType): self
    {
        $this->targetType = $targetType;

        return $this;
    }

    public function getAuthor(): ?WitcherUser
    {
        return $this->author;
    }

    public function setAuthor(?WitcherUser $author): self
    {
        $this->author = $author;

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

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getProjectId(): int
    {
        return $this->projectId;
    }

    public function setProjectId(int $projectId): self
    {
        $this->projectId = $projectId;

        return $this;
    }
}
