<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Integration\Application\IssueTracker\Jira\Migration\HistoryMigrationStep;

use FOS\ElasticaBundle\Persister\ObjectPersisterInterface;
use Jagaad\WitcherApi\ElasticSearch\Integration\IssueTracker\Jira\DTO\Changelog;
use Jagaad\WitcherApi\ElasticSearch\Integration\IssueTracker\Jira\DTO\HistoryItem;
use Jagaad\WitcherApi\Entity\Task;
use Jagaad\WitcherApi\Entity\WitcherUser;
use Jagaad\WitcherApi\Enum\HistoryEntityType;
use Jagaad\WitcherApi\HistoryTransformer\TrackableFields\TaskTrackableFields;
use Jagaad\WitcherApi\Integration\Application\DTO\Request;
use Jagaad\WitcherApi\Integration\Application\Exception\IntegrationException;
use Jagaad\WitcherApi\Integration\Domain\IssueTracker\ChangeLogHistory;
use Jagaad\WitcherApi\Integration\Domain\IssueTracker\ChangeLogHistoryItem;
use Jagaad\WitcherApi\Integration\Domain\IssueTracker\IssueChangeLogHistorySearchResult;
use Jagaad\WitcherApi\Integration\Migration\Interfaces\HistoryMigrationStepInterface;
use Jagaad\WitcherApi\Integration\Repository\IssueTracker\IssueReadApiInterface;
use Jagaad\WitcherApi\Renderer\RendererInterface;
use Jagaad\WitcherApi\Repository\WitcherUserRepository;
use Psr\Log\LoggerInterface;

final class ChangelogPopulationStep implements HistoryMigrationStepInterface
{
    private IssueReadApiInterface $issueApiReadRepository;
    private WitcherUserRepository $witcherUserRepository;
    private ObjectPersisterInterface $elasticPersister;
    private LoggerInterface $logger;
    private RendererInterface $renderer;

    public function __construct(
        IssueReadApiInterface $issueApiReadRepository,
        WitcherUserRepository $witcherUserRepository,
        ObjectPersisterInterface $elasticPersister,
        LoggerInterface $logger,
        RendererInterface $renderer
    ) {
        $this->issueApiReadRepository = $issueApiReadRepository;
        $this->witcherUserRepository = $witcherUserRepository;
        $this->elasticPersister = $elasticPersister;
        $this->logger = $logger;
        $this->renderer = $renderer;
    }

    public function process(Request $request, Task $task): void
    {
        $documents = [];

        while ($changelogResponse = $this->issueApiReadRepository->getChangeLog($request)) {
            /** @var IssueChangeLogHistorySearchResult $changelogResponse */
            $historyList = $changelogResponse->getValues();
            $jiraAccountIds = $this->getJiraAccounts($historyList);
            $witcherUsers = $this->witcherUserRepository->findWitcherUserByJiraAccountIds($jiraAccountIds, false);

            foreach ($historyList as $history) {
                try {
                    if (0 === \count($witcherUsers)) {
                        break;
                    }

                    $changelog = $this->getChangelog($history->getItems(), $witcherUsers);

                    if (0 === \count($changelog)) {
                        continue;
                    }

                    $documents[] = $this->getDocument(
                        $task,
                        $witcherUsers,
                        $history,
                        $changelog
                    );

                    if (0 === \count($documents) % $request->getMaxResults()) {
                        $this->renderer->renderNotice('Persisting change logs into elasticsearch');
                        $this->elasticPersister->replaceMany($documents);
                        $documents = [];
                    }
                } catch (\Throwable $exception) {
                    $this->renderer->renderError('Exception occurred during migration. Please see logs for details');
                    $this->logger->error($exception->getMessage(), ['error' => $exception]);
                }
            }

            if ($changelogResponse->isLast()) {
                break;
            }

            $request->increaseStartAt();
        }

        if (\count($documents) > 0) {
            $this->elasticPersister->replaceMany($documents);
        }
    }

    /**
     * @param ChangeLogHistory[] $historyLogs
     *
     * @return string[]
     */
    private function getJiraAccounts(array $historyLogs): array
    {
        $accountIds = [];

        foreach ($historyLogs as $historyLog) {
            $accountId = $historyLog->getAuthor()->getAccountId();

            if (!\in_array($accountId, $accountIds, true)) {
                $accountIds[] = $accountId;
            }

            foreach ($historyLog->getItems() as $historyItem) {
                $field = \strtolower($historyItem->getField());

                if (TaskTrackableFields::ASSIGNEE !== $field) {
                    continue;
                }

                $from = $historyItem->getFrom();
                $to = $historyItem->getTo();

                if (null !== $from && !\in_array($from, $accountIds, true)) {
                    $accountIds[] = $from;
                }

                if (null !== $to && !\in_array($to, $accountIds, true)) {
                    $accountIds[] = $to;
                }
            }
        }

        return $accountIds;
    }

    /**
     * @param Changelog[] $changelog
     * @param WitcherUser[] $witcherUsers
     */
    private function getDocument(Task $task, array $witcherUsers, ChangeLogHistory $history, array $changelog): HistoryItem
    {
        $author = $history->getAuthor();
        $witcherUser = $witcherUsers[$author->getAccountId()] ?? null;

        if (null === $witcherUser) {
            throw IntegrationException::create('User is not found for current history log, please invoke user migration');
        }

        return HistoryItem::create(
            \sha1($history->getId(). 0),
            HistoryEntityType::TASK,
            $task->getId(),
            $task->getSlug(),
            $history->getCreated(),
            $witcherUser->getId(),
            $changelog,
            $history->getId(),
            null
        );
    }

    /**
     * @param ChangeLogHistoryItem[] $changelogItems
     * @param WitcherUser[] $witcherUsers
     *
     * @return Changelog[]
     */
    private function getChangelog(array $changelogItems, array $witcherUsers): array
    {
        $items = [];

        foreach ($changelogItems as $changelogItem) {
            $field = \strtolower($changelogItem->getField());

            if (!\in_array($field, [TaskTrackableFields::STATUS, TaskTrackableFields::ASSIGNEE], true)) {
                continue;
            }

            $fromString = null;
            $toString = null;

            if (TaskTrackableFields::STATUS === $field) {
                $fromString = $changelogItem->getFromString();
                $toString = $changelogItem->getToString();
            }

            if (TaskTrackableFields::ASSIGNEE === $field) {
                $from = $witcherUsers[$changelogItem->getFrom()] ?? null;
                $to = $witcherUsers[$changelogItem->getTo()] ?? null;

                $fromString = null !== $from ? (string) $from->getId() : null;
                $toString = null !== $to ? (string) $to->getId() : null;
            }

            if (null === $fromString && null === $toString) {
                continue;
            }

            $items[] = Changelog::create(
                \sha1($fromString.$toString),
                $field,
                $fromString,
                $toString
            );
        }

        return $items;
    }
}
