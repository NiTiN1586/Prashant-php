<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Integration\Application\IssueTracker\Jira\Migration;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\ORMException;
use Jagaad\WitcherApi\Entity\Priority;
use Jagaad\WitcherApi\Integration\Application\DTO\Request;
use Jagaad\WitcherApi\Integration\Domain\IssueTracker\Priority as PriorityDTO;
use Jagaad\WitcherApi\Integration\Migration\Interfaces\MigrationInterface;
use Jagaad\WitcherApi\Integration\Repository\IssueTracker\PriorityReadApiInterface;
use Jagaad\WitcherApi\Renderer\RendererInterface;
use Jagaad\WitcherApi\Repository\PriorityRepository;
use Jagaad\WitcherApi\Utils\StringUtils;
use Psr\Log\LoggerInterface;

class PriorityMigration implements MigrationInterface
{
    private PriorityRepository $priorityRepository;
    private PriorityReadApiInterface $priorityReadApiRepository;
    private LoggerInterface $logger;
    private RendererInterface $renderer;

    public function __construct(
        PriorityReadApiInterface $priorityReadApiRepository,
        PriorityRepository $priorityRepository,
        LoggerInterface $logger,
        RendererInterface $renderer
    ) {
        $this->priorityReadApiRepository = $priorityReadApiRepository;
        $this->priorityRepository = $priorityRepository;
        $this->logger = $logger;
        $this->renderer = $renderer;
    }

    public static function getPriority(): int
    {
        return \count(self::MIGRATION_PRIORITY) - \array_search(self::MIGRATE_PRIORITY, self::MIGRATION_PRIORITY, true);
    }

    /**
     * {@inheritdoc}
     */
    public function migrate(Request $request): void
    {
        $this->renderer->renderNotice('Only migration for all priorities is available.');
        $this->process();
    }

    /**
     * {@inheritdoc}
     */
    public function migrateAll(Request $request): void
    {
        $this->process();
    }

    public function getAlias(): string
    {
        return self::MIGRATE_PRIORITY;
    }

    private function process(): void
    {
        $existingPriorities = $this->priorityRepository->findAll(false);
        /** @var PriorityDTO[] $priorityResponse */
        $priorityResponse = $this->priorityReadApiRepository->getAll();
        $priorityResponse = $this->filterDuplicatedPriorities($priorityResponse);

        $savedPriorities = [];
        $buffer = 1;

        while ($prioritiesBatch = \array_splice($priorityResponse, 0, self::BATCH_SIZE)) {
            try {
                /* @var PriorityDTO $priority */
                foreach ($prioritiesBatch as $priority) {
                    if (\array_key_exists($priority->getName(), $existingPriorities)
                        || \in_array($priority->getName(), $savedPriorities, true)
                    ) {
                        $this->renderer->renderError(
                            \sprintf('\'%s\' already exists.', $priority->getName())
                        );

                        continue;
                    }

                    $this->save($priority);

                    $savedPriorities[] = $priority->getName();

                    if (0 === $buffer % self::BATCH_SIZE) {
                        $this->priorityRepository->flush();
                        $buffer = 0;
                    }

                    ++$buffer;
                }
            } catch (ORMException|UniqueConstraintViolationException $exception) {
                $this->priorityRepository->restoreEntityManager();
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

        $this->priorityRepository->flush();
    }

    /**
     * @param PriorityDTO[] $priorities
     *
     * @return PriorityDTO[]
     */
    private function filterDuplicatedPriorities(array $priorities): array
    {
        $result = [];

        foreach ($priorities as $priority) {
            if (!\array_key_exists($priority->getName(), $result)) {
                $result[$priority->getName()] = $priority;
            }
        }

        return \array_values($result);
    }

    /**
     * @param PriorityDTO $priority
     */
    private function save(PriorityDTO $priority): void
    {
        $this->renderer->renderNotice(\sprintf('Migrating \'%s\'', $priority->getName()));

        $this->priorityRepository->save(
            Priority::createFromParams(
                $priority->getName(),
                StringUtils::convertNameToHandle($priority->getName()),
                $priority->getStatusColor(),
                $priority->getDescription()
            ),
            false
        );
    }
}
