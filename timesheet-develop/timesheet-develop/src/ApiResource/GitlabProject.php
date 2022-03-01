<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\ApiResource;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Jagaad\WitcherApi\Enum\ContextGroup;
use Jagaad\WitcherApi\Security\ReadableResourceInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *     routePrefix="/v1",
 *     normalizationContext={"groups"={ContextGroup::GROUP_GITLAB_PROJECT_READ}},
 *     itemOperations={"get"={
 *          "path"="/gitlab/project/{id}"
 *     }},
 *     collectionOperations={"get"={
 *          "path"="/gitlab/project",
 *     }},
 *     graphql={"item_query", "collection_query"}
 * )
 */
class GitlabProject implements ReadableResourceInterface
{
    /**
     * @ApiProperty(identifier=true)
     *
     * @Groups({ContextGroup::GROUP_GITLAB_PROJECT_READ})
     */
    private int $id;

    /**
     * @Groups({ContextGroup::GROUP_GITLAB_PROJECT_READ})
     */
    private ?string $description = null;

    /**
     * @Assert\NotBlank()
     *
     * @Groups({ContextGroup::GROUP_GITLAB_PROJECT_READ})
     */
    private string $name;

    /**
     * @Assert\Url()
     *
     * @Groups({ContextGroup::GROUP_GITLAB_PROJECT_READ})
     */
    private string $url;

    /**
     * @param array<string, mixed> $project
     */
    public static function createFromArray(array $project): self
    {
        if (!isset($project['name'], $project['web_url'], $project['id'])) {
            throw new \InvalidArgumentException('name, web_url, id are required');
        }

        $gitlabProject = new self();

        $gitlabProject->setDescription($project['description'] ?? null);
        $gitlabProject->setName($project['name']);
        $gitlabProject->setId($project['id']);
        $gitlabProject->setUrl($project['web_url']);

        return $gitlabProject;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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
