<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Integration\Application\GitManagement\GitLab\Migration;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\ORMException;
use Jagaad\WitcherApi\Entity\GitBranch;
use Jagaad\WitcherApi\Entity\GitProject;
use Jagaad\WitcherApi\Entity\Task;
use Jagaad\WitcherApi\Integration\Application\DTO\Request;
use Jagaad\WitcherApi\Integration\Application\Exception\IntegrationException;
use Jagaad\WitcherApi\Integration\Domain\GitManagement\Branch;
use Jagaad\WitcherApi\Integration\Domain\GitManagement\Message\GitEvent;
use Jagaad\WitcherApi\Integration\Migration\Interfaces\MigrationInterface;
use Jagaad\WitcherApi\Integration\Repository\GitManagement\ProjectBranchReadApiInterface;
use Jagaad\WitcherApi\Renderer\RendererInterface;
use Jagaad\WitcherApi\Repository\GitBranchRepository;
use Jagaad\WitcherApi\Repository\GitProjectRepository;
use Jagaad\WitcherApi\Repository\TaskRepository;
use Jagaad\WitcherApi\Utils\GitBranchUtils;
use Jagaad\WitcherApi\Utils\ValidationConstraintListConvertUtils;
use Psr\Log\LoggerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class GitlabBranchMigration implements MigrationInterface
{
    private ProjectBranchReadApiInterface $projectBranchRepository;
    private LoggerInterface $logger;
    private TaskRepository $taskRepository;
    private GitBranchRepository $gitBranchRepository;
    private RendererInterface $renderer;
    private GitProjectRepository $gitProjectRepository;
    private ValidatorInterface $validator;

    public function __construct(
        ProjectBranchReadApiInterface $projectBranchRepository,
        LoggerInterface $logger,
        TaskRepository $taskRepository,
        GitBranchRepository $gitBranchRepository,
        GitProjectRepository $gitProjectRepository,
        ValidatorInterface $validator,
        RendererInterface $renderer
    ) {
        $this->projectBranchRepository = $projectBranchRepository;
        $this->logger = $logger;
        $this->taskRepository = $taskRepository;
        $this->gitBranchRepository = $gitBranchRepository;
        $this->gitProjectRepository = $gitProjectRepository;
        $this->renderer = $renderer;
        $this->validator = $validator;
    }

    public static function getPriority(): int
    {
        return \count(self::MIGRATION_PRIORITY) - \array_search(self::MIGRATE_BRANCHES, self::MIGRATION_PRIORITY, true);
    }

    public function getAlias(): string
    {
        return self::MIGRATE_BRANCHES;
    }

    public function migrate(Request $request): void
    {
        $project = $request->getRequestParam('project');
        $branch = $request->getRequestParam('branch', '');
        $action = $request->getRequestParam('action');

        if (
            !\is_int($project)
            || !\in_array($action, GitEvent::AVAILABLE_EVENT_TYPES, true)
            || '' === \trim($branch)
        ) {
            $this->logger->notice('Project, action, branch are required');

            return;
        }

        /** @var GitBranch|null $gitBranch */
        $gitBranch = $this->gitBranchRepository->findOneByBranchAndProject($branch, $project, false);

        if (null === $gitBranch) {
            if (GitEvent::BRANCH_DELETED === $action) {
                return;
            }

            $this->process($branch, $project);

            return;
        }

        if (GitEvent::BRANCH_DELETED === $action) {
            $this->gitBranchRepository->remove($gitBranch);

            return;
        }

        if ($gitBranch->isDeleted()) {
            $gitBranch->undelete();
            $this->gitBranchRepository->save($gitBranch);
        }
    }

    public function migrateAll(Request $request): void
    {
        $taskIds = $this->taskRepository->findTaskIdsForGitlabProjects();

        if (0 === \count($taskIds)) {
            $this->renderer->renderError('Tasks are not assigned to Gitlab project. Nothing to migrate.');

            return;
        }

        $buffer = 1;

        while ($batch = \array_splice($taskIds, 0, self::BATCH_SIZE)) {
            foreach ($this->taskRepository->findExternalTasksByIds($batch) as $task) {
                foreach ($task->getWitcherProject()->getGitProjects() as $gitlabProject) {
                    try {
                        /** @var string $externalKey */
                        $externalKey = $task->getExternalKey();

                        $gitlabBranches = $this->projectBranchRepository
                            ->findGitlabProjectBranchesByHandle($gitlabProject->getGitLabProjectId(), $externalKey);

                        if (0 !== \count($gitlabBranches)) {
                            $this->save($gitlabBranches, $gitlabProject, $task, $buffer);
                        }
                    } catch (\Throwable $exception) {
                        $this->logger->error($exception->getMessage(), ['error' => $exception]);
                        $this->renderer->renderError('Error occurred during branch migration. Please see logs for details');
                    }
                }
            }
        }

        $this->gitBranchRepository->flush();
    }

    /**
     * @param Branch[] $gitlabBranches
     */
    private function save(array $gitlabBranches, GitProject $gitlabProject, Task $task, int &$buffer): void
    {
        $existingBranches = $this->gitBranchRepository->findByHandles(
            \array_map(static fn (Branch $branch) => $branch->getName(), $gitlabBranches)
        );

        foreach ($gitlabBranches as $gitlabBranch) {
            try {
                if (isset($existingBranches[$gitlabBranch->getName()])) {
                    $this->renderer->renderNotice(
                        \sprintf('Branch \'%s\' already exists.', $gitlabBranch->getName())
                    );

                    continue;
                }

                $this->renderer->renderNotice(\sprintf('Persisting branch \'%s\'...', $gitlabBranch->getName()));

                $gitBranch = GitBranch::createFromDTOs(
                    $gitlabBranch,
                    $gitlabProject,
                    $task
                );

                $errors = $this->validator->validate($gitBranch);

                if ($errors->count() > 0) {
                    $this->renderer->renderError(ValidationConstraintListConvertUtils::convertConstraintListToString($errors));

                    continue;
                }

                $this->gitBranchRepository->save($gitBranch, false);

                if (0 === $buffer % self::BATCH_SIZE) {
                    $buffer = 0;
                    $this->gitBranchRepository->flush();
                }

                ++$buffer;
            } catch (ORMException|UniqueConstraintViolationException $exception) {
                $this->gitBranchRepository->restoreEntityManager();
                $this->logger->error($exception->getMessage(), ['error' => $exception]);

                $this->renderer->renderError(
                    \sprintf('Exception occurred during migration of \'%s\'. Please see logs for details', $gitlabBranch->getName())
                );
            } catch (\Throwable $exception) {
                $this->logger->error($exception->getMessage(), ['error' => $exception]);

                $this->renderer->renderError(
                    \sprintf('Exception occurred during migration of \'%s\'. Please see logs for details', $gitlabBranch->getName())
                );
            }
        }
    }

    private function process(string $branch, int $project): void
    {
        try {
            $gitlabProject = $this->gitProjectRepository->findOneBy(['gitLabProjectId' => (string) $project]);
            $gitlabBranch = $this->projectBranchRepository->findGitlabProjectBranchesByHandle((string) $project, $branch);

            if (0 === \count($gitlabBranch)) {
                throw new \InvalidArgumentException(\sprintf('Branch %s was not found', $branch));
            }

            if (null === $gitlabProject) {
                throw new \InvalidArgumentException(\sprintf('Gitlab project doesn\'t exist for branch %s', $branch));
            }

            $branchName = GitBranchUtils::getBranchNameFromPattern($branch);

            if (null === $branchName) {
                throw new \InvalidArgumentException(\sprintf('Branch %s doesn\'t match pattern %s', $branch, GitBranchUtils::BRANCH_PATTERN));
            }

            $task = $this->taskRepository->findOneBy(['externalKey' => $branchName]);

            if (null === $task) {
                throw new \InvalidArgumentException(\sprintf('Task for branch %s was not found.', $branch));
            }

            $gitBranch = GitBranch::createFromDTOs($gitlabBranch[0], $gitlabProject, $task);
            $errors = $this->validator->validate($gitBranch);

            if ($errors->count() > 0) {
                throw IntegrationException::create(ValidationConstraintListConvertUtils::convertConstraintListToString($errors));
            }

            $this->gitBranchRepository->save(GitBranch::createFromDTOs($gitlabBranch[0], $gitlabProject, $task), true);
        } catch (\Throwable $exception) {
            $this->logger->error($exception->getMessage(), ['error' => $exception]);

            throw IntegrationException::create($exception->getMessage(), 0, $exception);
        }
    }
}
