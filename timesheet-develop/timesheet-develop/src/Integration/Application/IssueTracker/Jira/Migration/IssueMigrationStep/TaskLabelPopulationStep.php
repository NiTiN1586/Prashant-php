<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Integration\Application\IssueTracker\Jira\Migration\IssueMigrationStep;

use Jagaad\WitcherApi\Entity\Label;
use Jagaad\WitcherApi\Entity\Task;
use Jagaad\WitcherApi\Integration\Application\IssueTracker\Jira\DTO\IssueMigrationStepStorage;
use Jagaad\WitcherApi\Integration\Domain\IssueTracker\Issue;
use Jagaad\WitcherApi\Integration\Domain\IssueTracker\IssueField;
use Jagaad\WitcherApi\Integration\Migration\Interfaces\IssueMigrationStepInterface;
use Jagaad\WitcherApi\Repository\LabelRepository;
use Jagaad\WitcherApi\Repository\TaskRepository;

final class TaskLabelPopulationStep implements IssueMigrationStepInterface
{
    private LabelRepository $labelRepository;
    private TaskRepository $taskRepository;

    public function __construct(LabelRepository $labelRepository, TaskRepository $taskRepository)
    {
        $this->labelRepository = $labelRepository;
        $this->taskRepository = $taskRepository;
    }

    public static function getPriority(): int
    {
        return \count(self::ISSUE_STEP_PRIORITY) - \array_search(self::STEP_TASK_LABEL, self::ISSUE_STEP_PRIORITY, true);
    }

    public function process(
        IssueMigrationStepStorage $migrationDTO,
        Issue $trackerIssue,
        Task $task
    ): void {
        /** @var IssueField $responseFields */
        $responseFields = $trackerIssue->getFields();
        $labels = $responseFields->getLabels();

        if (0 === \count($labels)) {
            return;
        }

        $taskLabels = [];

        if ($this->taskRepository->hasOriginalEntityData($task)) {
            $taskLabels = \array_map(
                static fn (Label $label): string => $label->getName(),
                $this->taskRepository->findAssignedLabelsByTaskId($task->getId(), false)
            );
        }

        $taskLabelsDiff = \array_diff($labels, $taskLabels);
        $existingLabels = $this->labelRepository->findByNames($taskLabelsDiff, false);
        $newlyAddedLabels = \array_diff($taskLabelsDiff, \array_keys($existingLabels));

        $this->removeLabels($task, $labels);
        $this->updateLabels($task, $existingLabels);
        $this->createLabels($migrationDTO, $task, $newlyAddedLabels);
    }

    /**
     * @param string[] $newlyAddedLabels
     */
    private function createLabels(IssueMigrationStepStorage $migrationDTO, Task $task, array $newlyAddedLabels): void
    {
        foreach ($newlyAddedLabels as $newlyAddedLabel) {
            $persistedLabel = $migrationDTO->getLabel($newlyAddedLabel);

            if (null === $persistedLabel) {
                $persistedLabel = Label::create($newlyAddedLabel);
                $this->labelRepository->save($persistedLabel, false);
                $migrationDTO->addLabel($persistedLabel);
            }

            $task->addLabel($persistedLabel);
        }
    }

    /**
     * @param Label[] $existingLabels
     */
    private function updateLabels(Task $task, array $existingLabels): void
    {
        foreach ($existingLabels as $existingLabel) {
            if ($existingLabel->isDeleted()) {
                continue;
            }

            $task->addLabel($existingLabel);
        }
    }

    /**
     * @param string[] $labels
     */
    private function removeLabels(Task $task, array $labels): void
    {
        /** @var Label $labelToCheck */
        foreach ($task->getLabels() as $labelToCheck) {
            if (!\in_array($labelToCheck->getName(), $labels, true)) {
                $task->deleteLabel($labelToCheck);
            }
        }
    }
}
