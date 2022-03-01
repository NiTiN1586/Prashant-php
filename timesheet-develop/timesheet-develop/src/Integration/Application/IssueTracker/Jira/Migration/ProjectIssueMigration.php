<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Integration\Application\IssueTracker\Jira\Migration;

use Assert\Assertion;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\ORMException;
use Jagaad\WitcherApi\Entity\Task;
use Jagaad\WitcherApi\Integration\Application\DTO\Request;
use Jagaad\WitcherApi\Integration\Application\Exception\ValidationException;
use Jagaad\WitcherApi\Integration\Application\IssueTracker\Jira\DTO\IssueMigrationStepStorage;
use Jagaad\WitcherApi\Integration\Domain\IssueTracker\Issue;
use Jagaad\WitcherApi\Integration\Domain\IssueTracker\IssueSearchResult;
use Jagaad\WitcherApi\Integration\Domain\IssueTracker\Message\JiraTimeTrackerEvent;
use Jagaad\WitcherApi\Integration\Migration\Interfaces\IssueMigrationFlowHandlerInterface;
use Jagaad\WitcherApi\Integration\Migration\Interfaces\MigrationInterface;
use Jagaad\WitcherApi\Integration\Repository\IssueTracker\IssueReadApiInterface;
use Jagaad\WitcherApi\Renderer\RendererInterface;
use Jagaad\WitcherApi\Repository\EstimationTypeRepository;
use Jagaad\WitcherApi\Repository\PriorityRepository;
use Jagaad\WitcherApi\Repository\StatusRepository;
use Jagaad\WitcherApi\Repository\TaskRepository;
use Jagaad\WitcherApi\Repository\TrackerTaskTypeRepository;
use Jagaad\WitcherApi\Repository\WitcherProjectRepository;
use Psr\Log\LoggerInterface;

class ProjectIssueMigration implements MigrationInterface
{
    private IssueReadApiInterface $issueApiReadRepository;
    private WitcherProjectRepository $witcherProjectRepository;

    private RendererInterface $renderer;
    private IssueMigrationFlowHandlerInterface $migrationFlowHandler;
    private LoggerInterface $logger;

    private TrackerTaskTypeRepository $trackerTaskTypeRepository;
    private PriorityRepository $priorityRepository;
    private StatusRepository $statusRepository;
    private TaskRepository $taskRepository;
    private EstimationTypeRepository $estimationTypeRepository;

    public function __construct(
        IssueMigrationFlowHandlerInterface $migrationFlowHandler,
        WitcherProjectRepository $witcherProjectRepository,
        TrackerTaskTypeRepository $trackerTaskTypeRepository,
        StatusRepository $statusRepository,
        PriorityRepository $priorityRepository,
        EstimationTypeRepository $estimationTypeRepository,
        IssueReadApiInterface $issueApiReadRepository,
        TaskRepository $taskRepository,
        RendererInterface $renderer,
        LoggerInterface $logger
    ) {
        $this->witcherProjectRepository = $witcherProjectRepository;
        $this->renderer = $renderer;
        $this->issueApiReadRepository = $issueApiReadRepository;
        $this->migrationFlowHandler = $migrationFlowHandler;
        $this->trackerTaskTypeRepository = $trackerTaskTypeRepository;
        $this->priorityRepository = $priorityRepository;
        $this->statusRepository = $statusRepository;
        $this->taskRepository = $taskRepository;
        $this->logger = $logger;
        $this->estimationTypeRepository = $estimationTypeRepository;
    }

    public static function getPriority(): int
    {
        return \count(self::MIGRATION_PRIORITY) - \array_search(self::MIGRATE_ISSUE, self::MIGRATION_PRIORITY, true);
    }

    public function getAlias(): string
    {
        return self::MIGRATE_ISSUE;
    }

    public function migrate(Request $request): void
    {
        $issueKeys = $request->getRequestParam(Request::KEYS_PARAM, []);
        $action = $request->getRequestParam('action');

        if (!\is_array($issueKeys)) {
            $issueKeys = \array_filter([$issueKeys]);
        }

        Assertion::allString($issueKeys, 'Task keys parameter should be an array');
        $existingIssues = $this->taskRepository->findByExternalKeys($issueKeys);

        if (JiraTimeTrackerEvent::ISSUE_DELETED === $action) {
            $this->taskRepository->removeBulk($existingIssues);

            return;
        }

        $issueMigrationStepStorage = $this->setup();

        $request->setRequestParam(
            Request::JQL_PARAM,
            \sprintf('issue IN(%s) ORDER BY key', \implode(',', $issueKeys))
        );

        /** @var IssueSearchResult $response */
        $response = $this->issueApiReadRepository->getByJql($request);

        if (0 === \count($response->getIssues())) {
            return;
        }

        $groupedIssues = [];

        $issueMigrationStepStorage->setCustomFieldsMapping($response->getNames());

        foreach ($response->getIssues() as $issue) {
            if (null !== $issue->getFields()) {
                $groupedIssues[$issue->getProjectKey()][] = $issue;
            }
        }

        $projects = $this->witcherProjectRepository->findProjectsByExternalKey(\array_keys($groupedIssues));

        foreach ($projects as $handle => $project) {
            /** @var Issue $responseIssue */
            foreach ($groupedIssues[$handle] as $responseIssue) {
                $existingIssue = $existingIssues[$responseIssue->getKey()] ?? null;

                if (null !== $existingIssue && $existingIssue->isDeleted()) {
                    $this->renderer->renderError(\sprintf('Can\'t process deleted issue \'%s\'', $responseIssue->getKey()));

                    continue;
                }

                $this->migrationFlowHandler->process(
                    $issueMigrationStepStorage->setProject($project),
                    $existingIssue ?? new Task(),
                    $responseIssue
                );
            }
        }

        $this->taskRepository->flush();
    }

    public function migrateAll(Request $request): void
    {
        if (0 === $this->witcherProjectRepository->count([])) {
            $this->renderer->renderError('No projects were found for migration');
        }

        $issueMigrationStepStorage = $this->setup();
        $offsetStart = 0;

        $this->renderer->renderError(
            'Task will not be assigned to user if user was not created or has incorrect jira account'
        );

        while ($projects = $this->witcherProjectRepository->getExternalProjectsPaginated($offsetStart, self::BATCH_SIZE)) {
            foreach ($projects as $slug => $project) {
                try {
                    $request->resetRequestParams(true);
                    $request->setRequestParam(Request::JQL_PARAM, \sprintf('project=%s ORDER BY key', $slug));

                    $this->paginate(
                        $request,
                        $issueMigrationStepStorage->setProject($project)
                    );
                } catch (\Throwable $exception) {
                    $this->renderer->renderError('Migration error occurred. Please see logs for details');
                    $this->logger->debug($exception->getMessage(), ['error' => $exception]);
                }
            }

            $offsetStart += self::BATCH_SIZE;
        }

        $this->taskRepository->flush();
    }

    private function paginate(Request $request, IssueMigrationStepStorage $issueMigrationStepStorage): void
    {
        /** @var IssueSearchResult $response */
        $response = $this->issueApiReadRepository->getByJql($request);

        $issueMigrationStepStorage->setCustomFieldsMapping($response->getNames());

        if (0 === \count($response->getIssues())) {
            return;
        }

        $existingIssues = $this->taskRepository->findByExternalKeys(
            \array_map(static fn (Issue $issue): string => $issue->getKey(), $response->getIssues()),
        );

        $this->renderer->renderError(
            \sprintf(
                'Processed %d of %d issues for %s.',
                $response->getStartAt(),
                $response->getTotal(),
                $issueMigrationStepStorage->getProject()->getExternalKey()
            )
        );

        foreach ($response->getIssues() as $responseIssue) {
            try {
                if (null === $responseIssue->getFields()) {
                    $this->renderer->renderError(\sprintf('Nothing to update for %s', $responseIssue->getKey()));

                    continue;
                }

                /** @var Task|null $existingIssue */
                $existingIssue = $existingIssues[$responseIssue->getKey()] ?? null;

                if (null !== $existingIssue && $existingIssue->isDeleted()) {
                    $this->renderer->renderError(\sprintf('Can\'t process deleted issue \'%s\'', $responseIssue->getKey()));

                    continue;
                }

                $this->renderer->renderNotice(\sprintf('Processing %s', $responseIssue->getKey()));

                $this->migrationFlowHandler->process(
                    $issueMigrationStepStorage,
                    $existingIssue ?? new Task(),
                    $responseIssue
                );

                if (0 === $issueMigrationStepStorage->getBuffer() % self::BATCH_SIZE) {
                    $this->taskRepository->flush();
                    $issueMigrationStepStorage->resetBuffer();
                }
            } catch (ValidationException $exception) {
                $this->renderer->renderError($exception->getMessage());
            } catch (ORMException|UniqueConstraintViolationException $exception) {
                $this->taskRepository->restoreEntityManager();

                $this->renderer->renderError(
                    \sprintf('Exception occurred during migration of \'%s\'. Please see logs for details', $responseIssue->getKey())
                );

                $this->logger->error($exception->getMessage(), ['error' => $exception]);
            } catch (\Throwable $exception) {
                $this->logger->error($exception->getMessage(), ['error' => $exception]);

                $this->renderer->renderError(
                    \sprintf('Exception occurred during migration of \'%s\'. Please see logs for details', $responseIssue->getKey())
                );
            }
        }

        $request->increaseStartAt();
        $this->paginate($request, $issueMigrationStepStorage);
    }

    private function setup(): IssueMigrationStepStorage
    {
        return IssueMigrationStepStorage::create(
            $this->statusRepository->findAll(),
            $this->priorityRepository->findAll(),
            $this->trackerTaskTypeRepository->findAll(),
            $this->estimationTypeRepository->findAll()
        );
    }
}
