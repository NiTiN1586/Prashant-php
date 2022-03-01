<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Integration\Application\IssueTracker\MessageHandler;

use Jagaad\WitcherApi\Entity\Task;
use Jagaad\WitcherApi\Entity\WitcherProject;
use Jagaad\WitcherApi\Entity\WitcherUser;
use Jagaad\WitcherApi\Integration\Application\DTO\Request;
use Jagaad\WitcherApi\Integration\Application\Exception\InvalidEventTypeException;
use Jagaad\WitcherApi\Integration\Domain\IssueTracker\Message\JiraTimeTrackerEvent;
use Jagaad\WitcherApi\Integration\Domain\IssueTracker\Sprint;
use Jagaad\WitcherApi\Integration\Migration\Interfaces\MigrationRegistryInterface;
use Jagaad\WitcherApi\Repository\SprintRepository;
use Jagaad\WitcherApi\Repository\TaskRepository;
use Jagaad\WitcherApi\Repository\WitcherProjectRepository;
use Jagaad\WitcherApi\Repository\WitcherUserRepository;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class JiraTimeTrackerEventHandler implements MessageHandlerInterface
{
    public const JIRA_ISSUE_TRACKER_EVENT = 'jira.issue_tracker.events';
    private const MILLISECONDS = 1000;

    public function __construct(
        private TaskRepository $taskRepository,
        private WitcherProjectRepository $witcherProjectRepository,
        private MigrationRegistryInterface $migrationRegistry,
        private WitcherUserRepository $witcherUserRepository,
        private SprintRepository $sprintRepository,
        private LoggerInterface $logger
    ) {
    }

    public function __invoke(JiraTimeTrackerEvent $event): void
    {
        /** @var WitcherUser|WitcherProject|Task|Sprint|null $entity */
        $entity = $this->getEventEntity($event);

        if (null !== $entity) {
            $createdAt = (new \DateTimeImmutable())->setTimestamp(
                (int) \floor($event->getTimestamp() / self::MILLISECONDS)
            );

            if ($entity->getUpdatedAt() > $createdAt || $entity->isDeleted()) {
                return;
            }
        }

        try {
            $type = \strtok($event->getEvent(), '_');

            if (false === $type) {
                throw InvalidEventTypeException::create(\sprintf('Invalid webhook event type \'%s\' passed', $event->getEvent()));
            }

            $this->migrationRegistry->getMigration(\strtolower($type))
                ->migrate(
                    new Request(
                        [
                            'action' => $event->getEvent(),
                            'sprint' => $event->getParams()['sprint'] ?? null,
                            'board' => $event->getParams()['board'] ?? null,
                        ]
                    )
                );
        } catch (\Throwable $exception) {
            $this->logger->error((string) $exception, ['error' => $exception]);
        }
    }

    private function getEventEntity(JiraTimeTrackerEvent $event): ?object
    {
        switch (true) {
            case \in_array($event->getEvent(), JiraTimeTrackerEvent::AVAILABLE_PROJECT_EVENT_TYPES, true):
                return $this->witcherProjectRepository->findOneByExternalKey($event->getHandle(), false);
            case \in_array($event->getEvent(), JiraTimeTrackerEvent::AVAILABLE_ISSUE_EVENT_TYPES, true):
                return $this->taskRepository->findOneByExternalKey($event->getHandle(), false);
            case \in_array($event->getEvent(), JiraTimeTrackerEvent::AVAILABLE_USER_EVENT_TYPES, true):
                return $this->witcherUserRepository->findOneByJiraAccountId($event->getHandle(), false);
            case \in_array($event->getEvent(), JiraTimeTrackerEvent::AVAILABLE_SPRINT_EVENT_TYPES, true):
                $sprintId = $event->getParams()['sprint'] ?? null;

                if (null === $sprintId || $sprintId <= 0) {
                    return null;
                }

                return $this->sprintRepository->findOnyByExternalId($sprintId, false);
            default:
                return null;
        }
    }
}
