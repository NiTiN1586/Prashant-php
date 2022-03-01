<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Integration\Application\IssueTracker\Jira\Migration;

use Jagaad\WitcherApi\Integration\Application\DTO\Request;
use Jagaad\WitcherApi\Integration\Migration\Interfaces\HistoryMigrationFlowHandlerInterface;
use Jagaad\WitcherApi\Integration\Migration\Interfaces\MigrationInterface;
use Jagaad\WitcherApi\Renderer\RendererInterface;
use Jagaad\WitcherApi\Repository\TaskRepository;
use Psr\Log\LoggerInterface;

class HistoryMigration implements MigrationInterface
{
    private TaskRepository $taskRepository;
    private LoggerInterface $logger;
    private RendererInterface $renderer;
    private HistoryMigrationFlowHandlerInterface $historyMigrationFlowHandler;

    public function __construct(
        HistoryMigrationFlowHandlerInterface $historyMigrationFlowHandler,
        TaskRepository $taskRepository,
        LoggerInterface $logger,
        RendererInterface $renderer
    ) {
        $this->taskRepository = $taskRepository;
        $this->logger = $logger;
        $this->renderer = $renderer;
        $this->historyMigrationFlowHandler = $historyMigrationFlowHandler;
    }

    public static function getPriority(): int
    {
        return \count(self::MIGRATION_PRIORITY) - \array_search(self::MIGRATE_HISTORY, self::MIGRATION_PRIORITY, true);
    }

    public function getAlias(): string
    {
        return self::MIGRATE_HISTORY;
    }

    public function migrate(Request $request): void
    {
        // TODO to be implemented in a separate ticket
    }

    public function migrateAll(Request $request): void
    {
        $offset = 0;

        while ($tasks = $this->taskRepository->findActiveExternalTasks($offset, self::BATCH_SIZE)) {
            foreach ($tasks as $task) {
                try {
                    $this->renderer->renderNotice(\sprintf('Migrating history for %s', $task->getSlug()));
                    $request->resetRequestParams(true);
                    $request->setRequestParam(Request::PATH_PARAM, $task->getExternalKey());
                    $this->historyMigrationFlowHandler->process($request, $task);
                    \sleep(1);
                } catch (\Throwable $exception) {
                    $this->renderer->renderError(
                        'Unexpected error occurred during history migration. Please see logs for details'
                    );

                    $this->logger->error($exception->getMessage(), ['error' => $exception]);
                }
            }

            $offset += self::BATCH_SIZE;
        }
    }
}
