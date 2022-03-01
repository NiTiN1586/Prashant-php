<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Integration\Application\IssueTracker\Jira\Migration\Handler;

use Jagaad\WitcherApi\Entity\Task;
use Jagaad\WitcherApi\Integration\Application\Exception\ValidationException;
use Jagaad\WitcherApi\Integration\Application\IssueTracker\Jira\DTO\IssueMigrationStepStorage;
use Jagaad\WitcherApi\Integration\Domain\IssueTracker\Issue;
use Jagaad\WitcherApi\Integration\Migration\Interfaces\IssueMigrationFlowHandlerInterface;
use Jagaad\WitcherApi\Integration\Migration\Interfaces\IssueMigrationStepInterface;
use Jagaad\WitcherApi\Repository\TaskRepository;
use Jagaad\WitcherApi\Utils\ValidationConstraintListConvertUtils;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class IssueMigrationHandler implements IssueMigrationFlowHandlerInterface
{
    private TaskRepository $taskRepository;
    private ValidatorInterface $validator;

    /** @var iterable<int, IssueMigrationStepInterface> */
    private iterable $issueMigrationSteps;

    /**
     * @param iterable<int, IssueMigrationStepInterface> $issueMigrationSteps
     */
    public function __construct(iterable $issueMigrationSteps, ValidatorInterface $validator, TaskRepository $taskRepository)
    {
        $this->taskRepository = $taskRepository;
        $this->validator = $validator;
        $this->issueMigrationSteps = $issueMigrationSteps;
    }

    public function process(IssueMigrationStepStorage $issueMigrationStepStorage, Task $task, Issue $response): void
    {
        $errors = $this->validator->validate($issueMigrationStepStorage);

        if (\count($errors) > 0) {
            throw ValidationException::create(ValidationConstraintListConvertUtils::convertConstraintListToString($errors));
        }

        foreach ($this->issueMigrationSteps as $migrationStep) {
            $migrationStep->process($issueMigrationStepStorage, $response, $task);
        }

        $errors = $this->validator->validate($task);

        if ($errors->count() > 0) {
            throw ValidationException::create(ValidationConstraintListConvertUtils::convertConstraintListToString($errors));
        }

        $this->taskRepository->save($task, false);
        $issueMigrationStepStorage->increase();
    }
}
