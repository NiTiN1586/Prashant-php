<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Integration\Domain\IssueTracker;

class IssueField
{
    private string $summary;
    private IssueType $issueType;
    private ?Reporter $reporter = null;

    private \DateTimeImmutable $created;
    private \DateTimeImmutable $updated;

    private ?Description $description = null;

    private ?Priority $priority = null;
    private IssueStatus $status;

    /** @var string[] */
    private array $labels = [];
    private Project $project;

    /** @var Component[] */
    private array $components = [];

    private Reporter $creator;

    private ?Reporter $assignee = null;

    /** @var Issue[] */
    private array $subtasks;
    private ?ParentIssue $parent = null;
    private ?float $timeOriginalEstimate = null;

    /** @var array<string, mixed>|null */
    private ?array $customFields = null;

    public function __construct(bool $updateIssue = false)
    {
        if (!$updateIssue) {
            $this->project = new Project();
            $this->issueType = new IssueType();
        }
    }

    public function getSummary(): string
    {
        return $this->summary;
    }

    public function setSummary(string $summary): self
    {
        $this->summary = $summary;

        return $this;
    }

    public function getIssueType(): IssueType
    {
        return $this->issueType;
    }

    public function setIssueType(IssueType $issueType): self
    {
        $this->issueType = $issueType;

        return $this;
    }

    public function getReporter(): ?Reporter
    {
        return $this->reporter;
    }

    public function getReporterAccountId(): ?string
    {
        return $this->reporter?->getAccountId();
    }

    public function setReporter(?Reporter $reporter): self
    {
        $this->reporter = $reporter;

        return $this;
    }

    public function getCreated(): \DateTimeImmutable
    {
        return $this->created;
    }

    public function setCreated(\DateTimeImmutable $created): self
    {
        $this->created = $created;

        return $this;
    }

    public function getUpdated(): \DateTimeImmutable
    {
        return $this->updated;
    }

    public function setUpdated(\DateTimeImmutable $updated): self
    {
        $this->updated = $updated;

        return $this;
    }

    public function getDescription(): ?Description
    {
        return $this->description;
    }

    public function setDescription(?Description $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPriority(): ?Priority
    {
        return $this->priority;
    }

    public function getPriorityName(): ?string
    {
        return $this->priority?->getName();
    }

    public function setPriority(?Priority $priority): self
    {
        $this->priority = $priority;

        return $this;
    }

    public function getStatus(): IssueStatus
    {
        return $this->status;
    }

    public function getStatusName(): string
    {
        return $this->status->getName();
    }

    public function setStatus(IssueStatus $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return string[]
     */
    public function getLabels(): array
    {
        return $this->labels;
    }

    /**
     * @param string[] $labels
     */
    public function setLabels(array $labels): self
    {
        $this->labels = $labels;

        return $this;
    }

    public function getProject(): Project
    {
        return $this->project;
    }

    public function setProject(Project $project): self
    {
        $this->project = $project;

        return $this;
    }

    /**
     * @return Component[]
     */
    public function getComponents(): array
    {
        return $this->components;
    }

    /**
     * @param Component[] $components
     */
    public function setComponents(array $components): self
    {
        $this->components = $components;

        return $this;
    }

    public function getCreator(): Reporter
    {
        return $this->creator;
    }

    public function getCreatorAccountId(): string
    {
        return $this->creator->getAccountId();
    }

    public function setCreator(Reporter $creator): self
    {
        $this->creator = $creator;

        return $this;
    }

    public function getAssignee(): ?Reporter
    {
        return $this->assignee;
    }

    public function getAssigneeAccountId(): ?string
    {
        return null !== $this->assignee ? $this->assignee->getAccountId() : null;
    }

    public function setAssignee(?Reporter $assignee): self
    {
        $this->assignee = $assignee;

        return $this;
    }

    /**
     * @return Issue[]
     */
    public function getSubtasks(): array
    {
        return $this->subtasks;
    }

    /**
     * @param Issue[] $subtasks
     */
    public function setSubtasks(array $subtasks): self
    {
        $this->subtasks = $subtasks;

        return $this;
    }

    public function getParent(): ?ParentIssue
    {
        return $this->parent;
    }

    public function setParent(?ParentIssue $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getCustomFields(): ?array
    {
        return $this->customFields;
    }

    /**
     * @param array<string, mixed>|null $customFields
     */
    public function setCustomFields(?array $customFields): self
    {
        $this->customFields = $customFields;

        return $this;
    }

    public function getCustomField(string $name): mixed
    {
        return $this->customFields[$name] ?? null;
    }

    public function addCustomField(string $name, mixed $value): self
    {
        if (null === $this->customFields) {
            $this->customFields = [];
        }

        if (!\in_array($name, $this->customFields, true)) {
            $this->customFields[$name] = $value;
        }

        return $this;
    }

    public function getTimeOriginalEstimate(): ?float
    {
        return $this->timeOriginalEstimate;
    }

    public function setTimeOriginalEstimate(?float $timeOriginalEstimate): self
    {
        $this->timeOriginalEstimate = $timeOriginalEstimate;

        return $this;
    }
}
