<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Integration\Application\IssueTracker\Jira\Migration;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\ORMException;
use Jagaad\WitcherApi\Entity\Status;
use Jagaad\WitcherApi\Integration\Application\DTO\Request;
use Jagaad\WitcherApi\Integration\Domain\IssueTracker\Status as StatusDTO;
use Jagaad\WitcherApi\Integration\Migration\Interfaces\MigrationInterface;
use Jagaad\WitcherApi\Integration\Repository\IssueTracker\StatusReadApiInterface;
use Jagaad\WitcherApi\Renderer\RendererInterface;
use Jagaad\WitcherApi\Repository\StatusRepository;
use Jagaad\WitcherApi\Utils\StringUtils;
use Psr\Log\LoggerInterface;

class StatusMigration implements MigrationInterface
{
    private StatusReadApiInterface $statusReadApiRepository;
    private StatusRepository $statusRepository;
    private LoggerInterface $logger;
    private RendererInterface $renderer;

    public function __construct(
        StatusReadApiInterface $statusReadApiRepository,
        StatusRepository $statusRepository,
        LoggerInterface $consoleNotifier,
        RendererInterface $renderer
    ) {
        $this->statusReadApiRepository = $statusReadApiRepository;
        $this->logger = $consoleNotifier;
        $this->statusRepository = $statusRepository;
        $this->renderer = $renderer;
    }

    public static function getPriority(): int
    {
        return \count(self::MIGRATION_PRIORITY) - \array_search(self::MIGRATE_STATUS, self::MIGRATION_PRIORITY, true);
    }

    /**
     * {@inheritdoc}
     */
    public function migrate(Request $request): void
    {
        $this->renderer->renderNotice('Only migration for all statuses is available');
        $this->migrateAll($request);
    }

    /**
     * {@inheritdoc}
     */
    public function migrateAll(Request $request): void
    {
        /** @var StatusDTO[] $statuses */
        $statuses = $this->statusReadApiRepository->getAll();
        $statuses = $this->filterDuplicatedStatuses($statuses);
        $existingStatuses = $this->statusRepository->findAll(false);

        $savedStatuses = [];
        $buffer = 1;

        while ($statusesBatch = \array_splice($statuses, 0, self::BATCH_SIZE)) {
            try {
                /** @var StatusDTO[] $statusesBatch */
                foreach ($statusesBatch as $status) {
                    if (\array_key_exists($status->getName(), $existingStatuses)
                        || \in_array($status->getName(), $savedStatuses, true)
                    ) {
                        $this->renderer->renderError(
                            \sprintf('\'%s\' already exists.', $status->getName())
                        );

                        continue;
                    }

                    $this->save($status);
                    $savedStatuses[] = $status->getName();

                    if (0 === $buffer % self::BATCH_SIZE) {
                        $this->statusRepository->flush();
                        $buffer = 0;
                    }

                    ++$buffer;
                }
            } catch (ORMException|UniqueConstraintViolationException $exception) {
                $this->statusRepository->restoreEntityManager();
                $this->logger->error($exception->getMessage(), ['error' => $exception]);

                $this->renderer->renderError(
                    'Migration error occurred. Please see logs for details'
                );
            } catch (\Throwable $exception) {
                $this->logger->error($exception->getMessage(), ['error' => $exception]);

                $this->renderer->renderError(
                    'Migration error occurred. Please see logs for details'
                );
            }
        }

        $this->statusRepository->flush();
    }

    public function getAlias(): string
    {
        return self::MIGRATE_STATUS;
    }

    /**
     * @param StatusDTO[] $statuses
     *
     * @return StatusDTO[]
     */
    private function filterDuplicatedStatuses(array $statuses): array
    {
        $result = [];

        foreach ($statuses as $status) {
            if (!\array_key_exists($status->getName(), $result)) {
                $result[$status->getName()] = $status;
            }
        }

        return \array_values($result);
    }

    /**
     * @param StatusDTO $status
     */
    private function save(StatusDTO $status): void
    {
        $this->renderer->renderNotice(\sprintf('Migrating \'%s\'', $status->getName()));

        $this->statusRepository->save(
            Status::createFromParams(
                $status->getName(),
                StringUtils::convertNameToHandle($status->getName()),
                $status->getDescription()
            ),
            false
        );
    }
}
