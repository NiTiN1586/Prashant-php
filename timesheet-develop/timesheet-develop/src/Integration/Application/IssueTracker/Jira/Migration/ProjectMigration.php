<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Integration\Application\IssueTracker\Jira\Migration;

use Assert\Assertion;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\ORMException;
use Jagaad\WitcherApi\Entity\WitcherProject;
use Jagaad\WitcherApi\Integration\Application\DTO\Request;
use Jagaad\WitcherApi\Integration\Application\Exception\ValidationException;
use Jagaad\WitcherApi\Integration\Application\IssueTracker\Jira\DTO\ProjectMigrationStepStorage;
use Jagaad\WitcherApi\Integration\Domain\IssueTracker\Message\JiraTimeTrackerEvent;
use Jagaad\WitcherApi\Integration\Domain\IssueTracker\PaginatedProjects;
use Jagaad\WitcherApi\Integration\Domain\IssueTracker\Project as ProjectRestResponse;
use Jagaad\WitcherApi\Integration\Migration\Interfaces\MigrationInterface;
use Jagaad\WitcherApi\Integration\Migration\ProjectMigrationFlowHandlerInterface;
use Jagaad\WitcherApi\Integration\Repository\IssueTracker\ProjectReadApiInterface;
use Jagaad\WitcherApi\Renderer\RendererInterface;
use Jagaad\WitcherApi\Repository\TrackerTaskTypeRepository;
use Jagaad\WitcherApi\Repository\WitcherProjectRepository;
use Jagaad\WitcherApi\Repository\WitcherUserRepository;
use Psr\Log\LoggerInterface;

class ProjectMigration implements MigrationInterface
{
    private ProjectReadApiInterface $projectReadApiRepository;
    private WitcherProjectRepository $projectRepository;
    private WitcherUserRepository $witcherUserRepository;
    private TrackerTaskTypeRepository $trackerTaskTypeRepository;
    private LoggerInterface $logger;
    private RendererInterface $renderer;

    private ProjectMigrationFlowHandlerInterface $migrationFlowHandler;

    public function __construct(
        ProjectReadApiInterface $projectReadApiRepository,
        WitcherProjectRepository $projectRepository,
        WitcherUserRepository $witcherUserRepository,
        TrackerTaskTypeRepository $trackerTaskTypeRepository,
        ProjectMigrationFlowHandlerInterface $migrationFlowHandler,
        LoggerInterface $consoleNotifier,
        RendererInterface $renderer
    ) {
        $this->projectReadApiRepository = $projectReadApiRepository;
        $this->projectRepository = $projectRepository;
        $this->witcherUserRepository = $witcherUserRepository;
        $this->logger = $consoleNotifier;
        $this->renderer = $renderer;
        $this->trackerTaskTypeRepository = $trackerTaskTypeRepository;
        $this->migrationFlowHandler = $migrationFlowHandler;
    }

    public static function getPriority(): int
    {
        return \count(self::MIGRATION_PRIORITY) - \array_search(self::MIGRATE_PROJECT, self::MIGRATION_PRIORITY, true);
    }

    public function getAlias(): string
    {
        return self::MIGRATE_PROJECT;
    }

    /**
     * {@inheritdoc}
     */
    public function migrate(Request $request): void
    {
        $projects = $request->getRequestParam(Request::KEYS_PARAM, []);
        $action = $request->getRequestParam('action');

        if (!\is_array($projects)) {
            $projects = \array_filter([$projects]);
        }

        Assertion::allString($projects, 'Please pass project keys using comma as separator');

        if (JiraTimeTrackerEvent::PROJECT_DELETED === $action) {
            if (0 === \count($projects)) {
                return;
            }

            $this->projectRepository->removeBulk($this->projectRepository->findProjectsByExternalKey($projects));

            return;
        }

        $this->migrateAll($request);
    }

    /**
     * {@inheritdoc}
     */
    public function migrateAll(Request $request): void
    {
        $migrationStepDTO = (new ProjectMigrationStepStorage())
            ->setTrackerTaskTypes(
                $this->trackerTaskTypeRepository->findAll(false)
            );

        $this->paginate($request, $migrationStepDTO);
        $this->projectRepository->flush();
    }

    /**
     * @param ProjectRestResponse[] $apiResponse
     */
    private function process(array $apiResponse, ProjectMigrationStepStorage $migrationStepDTO): void
    {
        $projectHandles = \array_filter(
            \array_map(static fn (ProjectRestResponse $project) => $project->getKey(), $apiResponse)
        );

        $existingProjects = $this->projectRepository->findProjectsByExternalKey($projectHandles, false);

        foreach ($apiResponse as $response) {
            try {
                $existingProject = $existingProjects[$response->getKey()] ?? null;

                if (null !== $existingProject
                    && ($existingProject->isDeleted() || null === $existingProject->getExternalKey())
                ) {
                    $this->renderer->renderError(
                        \sprintf(
                            'Project \'%s\' %s. Skipping',
                            $response->getName(),
                            $existingProject->isDeleted() ? 'was deleted' : 'doesn\'t contain tracker project'
                        )
                    );

                    continue;
                }

                $this->renderer->renderNotice(
                    \sprintf(
                        '%s project \'%s\'',
                        null === $existingProject ? 'Persisting' : 'Updating',
                        $response->getKey()
                    )
                );

                $this->migrationFlowHandler->process(
                    $migrationStepDTO,
                    $existingProject ?? new WitcherProject(),
                    $response
                );

                if (0 === $migrationStepDTO->getBuffer() % self::BATCH_SIZE) {
                    $migrationStepDTO->resetBuffer();
                    $this->projectRepository->flush();
                }
            } catch (ValidationException $exception) {
                $this->renderer->renderError($exception->getMessage());
            } catch (ORMException|UniqueConstraintViolationException $exception) {
                $this->projectRepository->restoreEntityManager();

                $this->renderer->renderError(
                    \sprintf('Exception occurred during migration of \'%s\'. Please see logs for details', $response->getKey())
                );

                $this->logger->error($exception->getMessage(), ['error' => $exception]);
            } catch (\Throwable $exception) {
                $this->renderer->renderError(
                    \sprintf('Exception occurred during migration of \'%s\'. Please see logs for details', $response->getKey())
                );

                $this->logger->error($exception->getMessage(), ['error' => $exception]);
            }
        }
    }

    private function paginate(Request $request, ProjectMigrationStepStorage $migrationStepDTO): void
    {
        try {
            /** @var PaginatedProjects $projectsResponse */
            $projectsResponse = $this->projectReadApiRepository->getAllPaginated($request);

            if (0 === \count($projectsResponse->getValues())) {
                return;
            }

            $accounts = \array_filter(
                \array_map(static fn (ProjectRestResponse $project) => $project->getAccountId(), $projectsResponse->getValues())
            );

            $accounts = \array_unique($accounts);

            $migrationStepDTO->setOwners(
                $this->witcherUserRepository->findWitcherUserByJiraAccountIds($accounts, false)
            );

            $this->process($projectsResponse->getValues(), $migrationStepDTO);

            if ($projectsResponse->isLast()) {
                return;
            }

            $request->increaseStartAt();
            $this->paginate($request, $migrationStepDTO);
        } catch (\Throwable $exception) {
            $this->renderer->renderError('Exception occurred during migration. Please see logs for details');
            $this->logger->error($exception->getMessage(), ['error' => $exception]);
        }
    }
}
