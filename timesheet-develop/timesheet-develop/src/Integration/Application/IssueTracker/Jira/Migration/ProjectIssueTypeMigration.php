<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Integration\Application\IssueTracker\Jira\Migration;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\ORMException;
use Jagaad\WitcherApi\Entity\TrackerTaskType;
use Jagaad\WitcherApi\Integration\Application\DTO\Request;
use Jagaad\WitcherApi\Integration\Domain\IssueTracker\IssueType;
use Jagaad\WitcherApi\Integration\Migration\Interfaces\MigrationInterface;
use Jagaad\WitcherApi\Integration\Repository\IssueTracker\IssueTypeReadApiInterface;
use Jagaad\WitcherApi\Renderer\RendererInterface;
use Jagaad\WitcherApi\Repository\TrackerTaskTypeRepository;
use Jagaad\WitcherApi\Utils\StringUtils;
use Psr\Log\LoggerInterface;

class ProjectIssueTypeMigration implements MigrationInterface
{
    private IssueTypeReadApiInterface $issueTypeApiReadRepository;
    private TrackerTaskTypeRepository $trackerTaskTypeRepository;
    private LoggerInterface $logger;
    private RendererInterface $renderer;

    public function __construct(
        IssueTypeReadApiInterface $issueTypeApiReadRepository,
        TrackerTaskTypeRepository $trackerTaskTypeRepository,
        LoggerInterface $logger,
        RendererInterface $renderer
    ) {
        $this->issueTypeApiReadRepository = $issueTypeApiReadRepository;
        $this->logger = $logger;
        $this->trackerTaskTypeRepository = $trackerTaskTypeRepository;
        $this->renderer = $renderer;
    }

    public static function getPriority(): int
    {
        return \count(self::MIGRATION_PRIORITY) - \array_search(self::MIGRATE_ISSUE_TYPES, self::MIGRATION_PRIORITY, true);
    }

    public function getAlias(): string
    {
        return self::MIGRATE_ISSUE_TYPES;
    }

    public function migrate(Request $request): void
    {
        $this->renderer->renderNotice('Only all issueType migration is available.');
        $this->migrateAll(new Request());
    }

    public function migrateAll(Request $request): void
    {
        $issueTypes = $this->issueTypeApiReadRepository->findAll();
        $existingIssueTypes = $this->trackerTaskTypeRepository->findAll(false);

        if (0 === \count($issueTypes)) {
            $this->renderer->renderNotice('There were no issue types found');

            return;
        }

        $issueTypes = \array_column(
            \array_map(static fn (IssueType $issueType): array => ['key' => $issueType->getName(), 'value' => $issueType], $issueTypes),
            'value',
            'key'
        );

        $issueTypesToCreate = \array_filter($issueTypes, static function (IssueType $issueType) use ($existingIssueTypes): bool {
            return !isset($existingIssueTypes[$issueType->getName()]);
        });

        if (0 === \count($issueTypesToCreate)) {
            $this->renderer->renderNotice('All Issue types were migrated previously. No actions needed');

            return;
        }

        $this->process($issueTypesToCreate);
        $this->trackerTaskTypeRepository->flush();
    }

    /**
     * @param IssueType[] $issueTypes
     */
    private function process(array $issueTypes): void
    {
        $buffer = 1;

        foreach ($issueTypes as $issueType) {
            try {
                $this->renderer->renderNotice(\sprintf('Persisting issueType \'%s\'', $issueType->getName()));

                $this->trackerTaskTypeRepository->save(
                    TrackerTaskType::create(
                        StringUtils::convertNameToHandle($issueType->getName()),
                        $issueType->getName()
                    ),
                    false
                );

                if (0 === $buffer % self::BATCH_SIZE) {
                    $this->trackerTaskTypeRepository->flush();
                    $buffer = 0;
                }

                ++$buffer;
            } catch (ORMException|UniqueConstraintViolationException $exception) {
                $this->trackerTaskTypeRepository->restoreEntityManager();

                $this->renderer->renderError(
                    \sprintf('Exception occurred during migration of \'%s\'. Please see logs for details', $issueType->getName())
                );

                $this->logger->error($exception->getMessage(), ['error' => $exception]);
            } catch (\Throwable $exception) {
                $this->logger->error($exception->getMessage(), ['error' => $exception]);

                $this->renderer->renderError(
                    \sprintf('Exception occurred during migration of \'%s\'. Please see logs for details', $issueType->getName())
                );
            }
        }
    }
}
