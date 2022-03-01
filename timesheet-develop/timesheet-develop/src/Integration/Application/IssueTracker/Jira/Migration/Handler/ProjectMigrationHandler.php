<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Integration\Application\IssueTracker\Jira\Migration\Handler;

use Jagaad\WitcherApi\Entity\WitcherProject;
use Jagaad\WitcherApi\Integration\Application\Exception\ValidationException;
use Jagaad\WitcherApi\Integration\Application\IssueTracker\Jira\DTO\ProjectMigrationStepStorage;
use Jagaad\WitcherApi\Integration\Domain\IssueTracker\Project as ProjectRestResponse;
use Jagaad\WitcherApi\Integration\Migration\ProjectMigrationFlowHandlerInterface;
use Jagaad\WitcherApi\Integration\Migration\ProjectMigrationStepInterface;
use Jagaad\WitcherApi\Repository\WitcherProjectRepository;
use Jagaad\WitcherApi\Utils\ValidationConstraintListConvertUtils;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class ProjectMigrationHandler implements ProjectMigrationFlowHandlerInterface
{
    private ValidatorInterface $validator;

    /** @var iterable<int, ProjectMigrationStepInterface> */
    private iterable $projectMigrationSteps;
    private WitcherProjectRepository $witcherProjectRepository;

    /**
     * @param iterable<int, ProjectMigrationStepInterface> $projectMigrationSteps
     */
    public function __construct(
        iterable $projectMigrationSteps,
        ValidatorInterface $validator,
        WitcherProjectRepository $witcherProjectRepository
    ) {
        $this->validator = $validator;
        $this->projectMigrationSteps = $projectMigrationSteps;
        $this->witcherProjectRepository = $witcherProjectRepository;
    }

    public function process(ProjectMigrationStepStorage $migrationStepStorage, WitcherProject $witcherProject, ProjectRestResponse $response): void
    {
        $this->validate($migrationStepStorage);

        foreach ($this->projectMigrationSteps as $projectMigrationStep) {
            $projectMigrationStep->process($migrationStepStorage, $witcherProject, $response);
        }

        $this->validate($witcherProject);

        $this->witcherProjectRepository->save($witcherProject, false);
        $migrationStepStorage->increase();
    }

    private function validate(object $instance): void
    {
        $errors = $this->validator->validate($instance);

        if ($errors->count() > 0) {
            throw ValidationException::create(ValidationConstraintListConvertUtils::convertConstraintListToString($errors));
        }
    }
}
