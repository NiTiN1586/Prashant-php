<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Integration\Domain\IssueTracker;

class Project
{
    private const ASSIGN_TYPES = ['PROJECT_LEAD', 'UNASSIGNED'];

    private string $id;
    private string $key;
    private string $name;

    /** @var array<int, mixed> */
    private array $projectCategory;
    private ?string $description = null;
    private ?User $lead = null;

    private object $avatarUrls;

    /** @var IssueType[] */
    private array $issueTypes;

    private ?string $assigneeType = null;
    private string $url;
    private string $projectTypeKey;
    private int $categoryId;

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function setKey(string $key): self
    {
        $this->key = $key;

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

    /**
     * @return mixed[]
     */
    public function getProjectCategory(): array
    {
        return $this->projectCategory;
    }

    /**
     * @param mixed[] $projectCategory
     */
    public function setProjectCategory(array $projectCategory): self
    {
        $this->projectCategory = $projectCategory;

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

    public function getLead(): ?User
    {
        return $this->lead;
    }

    public function getAccountId(): ?string
    {
        return $this->lead?->getAccountId();
    }

    public function setLead(?User $lead): self
    {
        $this->lead = $lead;

        return $this;
    }

    public function getAvatarUrls(): object
    {
        return $this->avatarUrls;
    }

    public function setAvatarUrls(object $avatarUrls): self
    {
        $this->avatarUrls = $avatarUrls;

        return $this;
    }

    /**
     * @return IssueType[]
     */
    public function getIssueTypes(): array
    {
        return $this->issueTypes;
    }

    /**
     * @param IssueType[] $issueTypes
     */
    public function setIssueTypes(array $issueTypes): self
    {
        $this->issueTypes = $issueTypes;

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

    public function getProjectTypeKey(): string
    {
        return $this->projectTypeKey;
    }

    public function setProjectTypeKey(string $projectTypeKey): self
    {
        $this->projectTypeKey = $projectTypeKey;

        return $this;
    }

    public function getCategoryId(): int
    {
        return $this->categoryId;
    }

    public function setCategoryId(int $categoryId): self
    {
        $this->categoryId = $categoryId;

        return $this;
    }

    /**
     * @throws \InvalidArgumentException
     */
    public function setAssigneeType(string $assigneeType): self
    {
        if (!\in_array($assigneeType, self::ASSIGN_TYPES, true)) {
            throw new \InvalidArgumentException(\sprintf('Invalid assigneeType %s', $assigneeType));
        }

        $this->assigneeType = $assigneeType;

        return $this;
    }

    public function getAssigneeType(): ?string
    {
        return $this->assigneeType;
    }
}
