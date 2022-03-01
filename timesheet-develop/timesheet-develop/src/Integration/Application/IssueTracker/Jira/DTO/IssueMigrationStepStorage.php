<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Integration\Application\IssueTracker\Jira\DTO;

use Jagaad\WitcherApi\Entity\EstimationType;
use Jagaad\WitcherApi\Entity\Label;
use Jagaad\WitcherApi\Entity\Priority;
use Jagaad\WitcherApi\Entity\Status;
use Jagaad\WitcherApi\Entity\Task;
use Jagaad\WitcherApi\Entity\TrackerTaskType;
use Jagaad\WitcherApi\Entity\WitcherProject;
use Jagaad\WitcherApi\Integration\Migration\Interfaces\MigrationRequestFlowInterface;
use Symfony\Component\Validator\Constraints as Assert;

class IssueMigrationStepStorage implements MigrationRequestFlowInterface
{
    private int $buffer = 0;

    /**
     * @var array<string, Status>
     *
     * @Assert\Count(min=1, minMessage="Please run status migration first")
     */
    private array $status;

    /***
     * @Assert\Count(min=1, minMessage="Project is required")
     */
    private WitcherProject $project;

    /**
     * @var array<string, Priority>
     *
     * @Assert\Count(min=1, minMessage="Please run priorities migration first")
     */
    private array $priorities;

    /**
     * @var array<string, TrackerTaskType>
     *
     * @Assert\Count(min=1, minMessage="Please run Project Issue Type migration first")
     */
    private array $issueTypes;

    /**
     * @var array<string, Label>
     */
    private array $labels = [];

    /** @var array<string, mixed> */
    private array $customFieldsMapping = [];

    /**
     * @var array<string, Task>
     */
    private array $existingIssues;

    /**
     * @var array<string, EstimationType>
     *
     * @Assert\Count(min=1, minMessage="There are no estimation types found. Should be at list one")
     */
    private array $estimationTypes;

    /**
     * @param array<string, Status> $status
     * @param array<string, Priority> $priorities
     * @param array<string, TrackerTaskType> $issueTypes
     * @param array<string, EstimationType> $estimationTypes
     */
    private function __construct(array $status, array $priorities, array $issueTypes, array $estimationTypes)
    {
        $this->status = $status;
        $this->priorities = $priorities;
        $this->issueTypes = $issueTypes;
        $this->estimationTypes = $estimationTypes;
    }

    public function setCustomFieldsMapping(?object $customFieldsMapping): self
    {
        if (null !== $customFieldsMapping) {
            $this->customFieldsMapping = (array) $customFieldsMapping;
        }

        return $this;
    }

    public function getCustomFieldByName(string $name): ?string
    {
        $fieldKey = \array_search($name, $this->customFieldsMapping, true);

        return \is_string($fieldKey) ? $fieldKey : null;
    }

    public function increase(): self
    {
        ++$this->buffer;

        return $this;
    }

    public function resetBuffer(): self
    {
        $this->buffer = 0;

        return $this;
    }

    public function getBuffer(): int
    {
        return $this->buffer;
    }

    /**
     * @param array<string, Status> $status
     * @param array<string, Priority> $priorities
     * @param array<string, TrackerTaskType> $issueTypes
     * @param array<string, EstimationType> $estimationTypes
     */
    public static function create(array $status, array $priorities, array $issueTypes, array $estimationTypes): self
    {
        return new self($status, $priorities, $issueTypes, $estimationTypes);
    }

    /**
     * @return array<string, Status>
     */
    public function getStatus(): array
    {
        return $this->status;
    }

    public function setProject(WitcherProject $project): self
    {
        $this->project = $project;

        return $this;
    }

    public function getProject(): WitcherProject
    {
        return $this->project;
    }

    /**
     * @return array<string, Priority>
     */
    public function getPriorities(): array
    {
        return $this->priorities;
    }

    /**
     * @return array<string, TrackerTaskType>
     */
    public function getIssueTypes(): array
    {
        return $this->issueTypes;
    }

    public function addLabel(Label $label): self
    {
        if (!\array_key_exists($label->getName(), $this->labels)) {
            $this->labels[$label->getName()] = $label;
        }

        return $this;
    }

    public function getLabel(string $name): ?Label
    {
        return $this->labels[$name] ?? null;
    }

    /**
     * @return Task[]
     */
    public function getExistingIssues(): array
    {
        return $this->existingIssues;
    }

    /**
     * @param Task[] $existingIssues
     */
    public function setExistingIssues(array $existingIssues): self
    {
        $this->existingIssues = $existingIssues;

        return $this;
    }

    public function getEstimationType(string $estimationType): ?EstimationType
    {
        return $this->estimationTypes[$estimationType] ?? null;
    }
}
