<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Integration\Application\IssueTracker\Jira\Migration;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\ORMException;
use Jagaad\WitcherApi\Entity\Sprint as SprintEntity;
use Jagaad\WitcherApi\Entity\WitcherProject;
use Jagaad\WitcherApi\Integration\Application\DTO\Request;
use Jagaad\WitcherApi\Integration\Domain\IssueTracker\Board;
use Jagaad\WitcherApi\Integration\Domain\IssueTracker\Message\JiraTimeTrackerEvent;
use Jagaad\WitcherApi\Integration\Domain\IssueTracker\Sprint;
use Jagaad\WitcherApi\Integration\Domain\IssueTracker\SprintSearchResult;
use Jagaad\WitcherApi\Integration\Migration\Interfaces\MigrationInterface;
use Jagaad\WitcherApi\Integration\Repository\IssueTracker\BoardReadApiInterface;
use Jagaad\WitcherApi\Integration\Repository\IssueTracker\SprintReadApiInterface;
use Jagaad\WitcherApi\Renderer\RendererInterface;
use Jagaad\WitcherApi\Repository\SprintRepository;
use Jagaad\WitcherApi\Repository\WitcherProjectRepository;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;
use Webmozart\Assert\Assert;

final class SprintMigration implements MigrationInterface
{
    public function __construct(
        private BoardReadApiInterface $boardReadApi,
        private SprintReadApiInterface $sprintReadApi,
        private WitcherProjectRepository $witcherProjectRepository,
        private SprintRepository $sprintRepository,
        private LoggerInterface $logger,
        private RendererInterface $renderer
    ) {
    }

    public function getAlias(): string
    {
        return self::MIGRATE_SPRINT;
    }

    public static function getPriority(): int
    {
        return \count(self::MIGRATION_PRIORITY) - \array_search(self::MIGRATE_SPRINT, self::MIGRATION_PRIORITY, true);
    }

    public function migrate(Request $request): void
    {
        $boardId = $request->getRequestParam('board');
        $sprintId = $request->getRequestParam('sprint');
        $event = $request->getRequestParam('action');

        Assert::positiveInteger($boardId, 'Board id should be positive');
        Assert::positiveInteger($sprintId, 'Sprint id should be positive');

        $existingSprint = $this->sprintRepository->findOnyByExternalId($sprintId, false);

        if (JiraTimeTrackerEvent::SPRINT_DELETED === $event) {
            if (null !== $existingSprint) {
                $this->sprintRepository->remove($existingSprint);
            }

            return;
        }

        /** @var Board $board */
        $board = $this->boardReadApi->getBoard($boardId);

        /** @var Sprint $sprint */
        $sprint = $this->sprintReadApi->getSprint($sprintId);

        $existingProject = $this->witcherProjectRepository->findOneByExternalKey($board->getProjectKey(), false);

        if (JiraTimeTrackerEvent::SPRINT_UPDATED === $event && null !== $existingSprint) {
            $this->sprintRepository->save($existingSprint->updateFromDTO($sprint), true);
        }

        if (JiraTimeTrackerEvent::SPRINT_CREATED === $event && null !== $existingProject) {
            $this->sprintRepository->save(
                SprintEntity::createFromDTO($sprint, $existingProject),
                true
            );
        }
    }

    public function migrateAll(Request $request): void
    {
        $offset = 0;

        while ($witcherProjects = $this->witcherProjectRepository->getExternalProjectsPaginated($offset, self::BATCH_SIZE)) {
            foreach ($witcherProjects as $witcherProject) {
                try {
                    $this->paginateProjectBoards($witcherProject);
                } catch (\Throwable $exception) {
                    $this->renderer->renderError(
                        \sprintf(
                            'Project \'%s\' sprint migration failed. Please see logs for details',
                            $witcherProject->getExternalKey()
                        )
                    );

                    $this->logger->error($exception->getMessage(), ['error' => $exception]);
                }
            }

            $offset += self::BATCH_SIZE;
        }
    }

    private function paginateProjectBoards(WitcherProject $witcherProject): void
    {
        $boardOffset = 0;

        while ($board = $this->boardReadApi->getProjectBoards($witcherProject->getExternalKey(), $boardOffset, self::BATCH_SIZE)) {
            foreach ($board->getValues() as $response) {
                try {
                    $this->paginateBoardSprints($witcherProject, $response);
                } catch (ORMException|UniqueConstraintViolationException $exception) {
                    $this->sprintRepository->restoreEntityManager();

                    $this->renderer->renderError(
                        \sprintf(
                            'Project \'%s\' sprint migration failed. Please see logs for details',
                            $witcherProject->getExternalKey()
                        )
                    );

                    $this->logger->error($exception->getMessage(), ['error' => $exception]);
                } catch (\Throwable $exception) {
                    if (Response::HTTP_BAD_REQUEST !== $exception->getCode()) {
                        $this->renderer->renderError(
                            \sprintf(
                                'Project \'%s\' sprint migration failed. Please see logs for details',
                                $witcherProject->getExternalKey()
                            )
                        );

                        $this->logger->error($exception->getMessage(), ['error' => $exception]);
                    }
                }
            }

            if ($board->isLast()) {
                break;
            }

            $boardOffset += self::BATCH_SIZE;
        }
    }

    private function paginateBoardSprints(WitcherProject $witcherProject, Board $board): void
    {
        $sprintOffset = 0;
        $buffer = 0;

        while ($sprintResponse = $this->boardReadApi->getBoardSprints($board->getId(), $sprintOffset, self::BATCH_SIZE)) {
            /** @var SprintSearchResult $sprintResponse */
            $sprintResponseValues = $sprintResponse->getValues();

            if (0 === \count($sprintResponseValues)) {
                break;
            }

            $existingSprints = $this->sprintRepository->findByExternalIds(
                \array_map(static fn (Sprint $sprint): int => $sprint->getId(), $sprintResponseValues)
            );

            $this->process($witcherProject, $sprintResponseValues, $existingSprints, $buffer);

            if ($sprintResponse->isLast()) {
                break;
            }

            $sprintOffset += self::BATCH_SIZE;
        }

        $this->sprintRepository->flush();
    }

    /**
     * @param Sprint[] $sprints
     * @param SprintEntity[] $existingSprints
     */
    private function process(WitcherProject $witcherProject, array $sprints, array $existingSprints, int &$buffer): void
    {
        foreach ($sprints as $sprint) {
            try {
                if (isset($existingSprints[$sprint->getId()])) {
                    $this->renderer->renderNotice(
                        \sprintf('Sprint %s already exists. Skipping...', $sprint->getName())
                    );

                    continue;
                }

                $this->sprintRepository->save(
                    SprintEntity::create(
                        $sprint->getName(),
                        $witcherProject,
                        $sprint->getStartDate(),
                        $sprint->getEndDate(),
                        $sprint->getCompleteDate(),
                        $sprint->getId(),
                        $sprint->getGoal(),
                        $sprint->isClosed()
                    ),
                    false
                );

                ++$buffer;

                if (0 === $buffer % self::BATCH_SIZE) {
                    $this->sprintRepository->flush();
                    $buffer = 0;
                }
            } catch (ORMException|UniqueConstraintViolationException $exception) {
                $this->sprintRepository->restoreEntityManager();

                $this->renderer->renderError(
                    \sprintf('Sprint \'%s\' migration failed. Please see logs for details', $sprint->getName())
                );

                $this->logger->error($exception->getMessage(), ['error' => $exception]);
            } catch (\Throwable $exception) {
                $this->renderer->renderError(
                    \sprintf('Sprint \'%s\' migration failed. Please see logs for details', $sprint->getName())
                );

                $this->logger->error($exception->getMessage(), ['error' => $exception]);
            }
        }
    }
}
