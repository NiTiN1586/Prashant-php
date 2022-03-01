<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Integration\Application\IssueTracker\Jira\Migration\IssueMigrationStep;

use Jagaad\WitcherApi\Entity\Task;
use Jagaad\WitcherApi\Integration\Application\IssueTracker\Jira\DTO\IssueMigrationStepStorage;
use Jagaad\WitcherApi\Integration\Domain\IssueTracker\Issue;
use Jagaad\WitcherApi\Integration\Domain\IssueTracker\IssueField;
use Jagaad\WitcherApi\Integration\Migration\Interfaces\IssueMigrationStepInterface;
use Jagaad\WitcherApi\Renderer\RendererInterface;
use Jagaad\WitcherApi\Repository\SprintRepository;
use Psr\Log\LoggerInterface;

final class SprintMigrationStep implements IssueMigrationStepInterface
{
    private const SPRINT_FIELD_NAME = 'Sprint';
    private const ERROR_MESSAGE = 'Sprint \'%s\' was not found for task \'%s\'. Run Sprint migration';

    public function __construct(
        private SprintRepository $sprintRepository,
        private LoggerInterface $logger,
        private RendererInterface $renderer
    ) {
    }

    public static function getPriority(): int
    {
        return \count(self::ISSUE_STEP_PRIORITY) - \array_search(self::STEP_SPRINT, self::ISSUE_STEP_PRIORITY, true);
    }

    public function process(IssueMigrationStepStorage $migrationDTO, Issue $trackerIssue, Task $task): void
    {
        /** @var IssueField $responseFields */
        $responseFields = $trackerIssue->getFields();
        $sprintFieldReference = $migrationDTO->getCustomFieldByName(self::SPRINT_FIELD_NAME);

        if (null === $sprintFieldReference) {
            return;
        }

        /** @var object[] $responseSprints */
        $responseSprints = $responseFields->getCustomField($sprintFieldReference) ?? [];

        if (0 === \count($responseSprints)) {
            return;
        }

        $this->removeUnassignedTaskSprints($task, $responseSprints);

        $this->populateSprints(
            $task,
            $this->getSprintsToAssign($task, $responseSprints)
        );
    }

    /**
     * @param object[] $responseSprints
     *
     * @return array<int, object>
     */
    private function getSprintsToAssign(Task $task, array $responseSprints): array
    {
        $taskSprints = [];

        foreach ($task->getSprints() as $taskSprint) {
            if (null !== $taskSprint->getExternalId()) {
                $taskSprints[] = $taskSprint->getExternalId();
            }
        }

        $sprintsToAssign = [];

        foreach ($responseSprints as $responseSprint) {
            if (\property_exists($responseSprint, 'id')
                && !\in_array($responseSprint->id, $taskSprints, true)
            ) {
                $sprintsToAssign[$responseSprint->id] = $responseSprint;
            }
        }

        return $sprintsToAssign;
    }

    /**
     * @param object[] $responseSprints
     */
    private function removeUnassignedTaskSprints(Task $task, array $responseSprints): void
    {
        $responseSprintIds = [];

        foreach ($responseSprints as $responseSprint) {
            if (\property_exists($responseSprint, 'id')) {
                $responseSprintIds[] = $responseSprint->id;
            }
        }

        foreach ($task->getSprints() as $sprint) {
            if (!\in_array($sprint->getExternalId(), $responseSprintIds, true)) {
                $task->removeSprint($sprint);
            }
        }
    }

    /**
     * @param array<int, object> $responseSprints
     */
    private function populateSprints(Task $task, array $responseSprints): void
    {
        $existingSprints = $this->sprintRepository->findByExternalIds(\array_keys($responseSprints));

        foreach ($responseSprints as $responseSprint) {
            if (!\property_exists($responseSprint, 'id')) {
                continue;
            }

            $existingSprint = $existingSprints[$responseSprint->id] ?? null;

            if (null === $existingSprint) {
                $sprintName = \property_exists($responseSprint, 'name') ? $responseSprint->name : '';
                $message = \sprintf(self::ERROR_MESSAGE, $sprintName, $task->getExternalKey());

                $this->logger->error($message, ['error' => $message]);
                $this->renderer->renderError($message);

                continue;
            }

            $task->addSprint($existingSprint);
        }
    }
}
