<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Integration\Application\GitManagement\MessageHandler;

use Jagaad\WitcherApi\Entity\GitBranch;
use Jagaad\WitcherApi\Integration\Application\DTO\Request;
use Jagaad\WitcherApi\Integration\Application\Exception\InvalidEventTypeException;
use Jagaad\WitcherApi\Integration\Domain\GitManagement\Message\GitEvent;
use Jagaad\WitcherApi\Integration\Migration\Interfaces\MigrationRegistryInterface;
use Jagaad\WitcherApi\Repository\GitBranchRepository;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class GitlabEventHandler implements MessageHandlerInterface
{
    public const GITLAB_EVENT = 'gitlab.events';

    private GitBranchRepository $gitBranchRepository;
    private MigrationRegistryInterface $migrationRegistry;
    private LoggerInterface $logger;

    public function __construct(
        GitBranchRepository $gitBranchRepository,
        MigrationRegistryInterface $migrationRegistry,
        LoggerInterface $logger
    ) {
        $this->migrationRegistry = $migrationRegistry;
        $this->logger = $logger;
        $this->gitBranchRepository = $gitBranchRepository;
    }

    public function __invoke(GitEvent $event): void
    {
        /** @var GitBranch|null $entity */
        $entity = $this->gitBranchRepository->findOneByBranchAndProject($event->getBranch(), $event->getProject(), false);

        if (null !== $entity && (GitEvent::BRANCH_DELETED !== $event->getEvent() || null !== $entity->getDeletedAt())) {
            return;
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
                            'branch' => $event->getBranch(),
                            'action' => $event->getEvent(),
                            'project' => $event->getProject(),
                        ]
                    )
                );
        } catch (\Throwable $exception) {
            $this->logger->error((string) $exception, ['error' => $exception]);
        }
    }
}
