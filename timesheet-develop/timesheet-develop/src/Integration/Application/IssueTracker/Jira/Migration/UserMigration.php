<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Integration\Application\IssueTracker\Jira\Migration;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\ORMException;
use Jagaad\WitcherApi\Entity\WitcherUser;
use Jagaad\WitcherApi\Integration\Application\DTO\Request;
use Jagaad\WitcherApi\Integration\Application\Exception\ValidationException;
use Jagaad\WitcherApi\Integration\Application\IssueTracker\Jira\DTO\UserMigrationStepStorage;
use Jagaad\WitcherApi\Integration\Domain\IssueTracker\Message\JiraTimeTrackerEvent;
use Jagaad\WitcherApi\Integration\Domain\IssueTracker\User;
use Jagaad\WitcherApi\Integration\Migration\Interfaces\MigrationInterface;
use Jagaad\WitcherApi\Integration\Migration\UserMigrationFlowHandlerInterface;
use Jagaad\WitcherApi\Integration\Repository\IssueTracker\UserReadApiInterface;
use Jagaad\WitcherApi\Manager\UserManagerApiInterface;
use Jagaad\WitcherApi\Renderer\RendererInterface;
use Jagaad\WitcherApi\Repository\RoleRepository;
use Jagaad\WitcherApi\Repository\WitcherUserRepository;
use Psr\Log\LoggerInterface;

final class UserMigration implements MigrationInterface
{
    private UserReadApiInterface $userReadApi;
    private UserManagerApiInterface $userManagerApi;
    private WitcherUserRepository $witcherUserRepository;
    private LoggerInterface $logger;
    private RendererInterface $renderer;
    private UserMigrationFlowHandlerInterface $userMigrationFlowHandler;
    private RoleRepository $roleRepository;

    public function __construct(
        UserReadApiInterface $userReadApi,
        UserManagerApiInterface $userManagerApi,
        WitcherUserRepository $witcherUserRepository,
        LoggerInterface $logger,
        RendererInterface $renderer,
        RoleRepository $roleRepository,
        UserMigrationFlowHandlerInterface $userMigrationFlowHandler
    ) {
        $this->userReadApi = $userReadApi;
        $this->userManagerApi = $userManagerApi;
        $this->witcherUserRepository = $witcherUserRepository;
        $this->logger = $logger;
        $this->renderer = $renderer;
        $this->userMigrationFlowHandler = $userMigrationFlowHandler;
        $this->roleRepository = $roleRepository;
    }

    public static function getPriority(): int
    {
        return \count(self::MIGRATION_PRIORITY) - \array_search(self::MIGRATE_USER, self::MIGRATION_PRIORITY, true);
    }

    public function getAlias(): string
    {
        return self::MIGRATE_USER;
    }

    public function migrate(Request $request): void
    {
        $jiraAccount = $request->getRequestParam(Request::KEYS_PARAM);
        $jiraAccount = \is_array($jiraAccount) ? \current($jiraAccount) : $jiraAccount;
        $action = $request->getRequestParam('action');

        if (null === $jiraAccount) {
            $this->renderer->renderNotice('Jira account is required parameter');

            return;
        }

        if (JiraTimeTrackerEvent::USER_DELETED === $action) {
            $this->deactivateUser($jiraAccount);

            return;
        }

        /** @var User $jiraUser */
        $jiraUser = $this->userReadApi->getUserByAccountId($jiraAccount);

        if (\in_array($jiraUser->getEmailAddress(), [null, ''], true)) {
            $this->renderer->renderNotice('Email is required');

            return;
        }

        $jiraApiResults = $this->userManagerApi->findUsersByEmails([$jiraUser->getEmailAddress()]);

        $this->process([$jiraUser], $jiraApiResults, $this->setup());
    }

    public function migrateAll(Request $request): void
    {
        $userMigrationStorage = $this->setup();

        while ($jiraUsers = $this->userReadApi->getAll($request->getStartAt(), self::BATCH_SIZE, false)) {
            try {
                $emails = \array_unique(
                    \array_map(static fn (User $user): ?string => $user->getEmailAddress(), $jiraUsers)
                );

                /** @var User[] $jiraUsers */
                $jiraUsers = \array_filter(
                    $jiraUsers,
                    static fn (User $user): bool => null !== $user->getEmailAddress() && '' !== $user->getEmailAddress()
                );

                $this->renderer->renderNotice('Verifying users in User API');

                $userApiResults = $this->userManagerApi->findUsersByEmails(\array_filter($emails));
                $this->process($jiraUsers, $userApiResults, $userMigrationStorage);
                $request->increaseStartAt(self::BATCH_SIZE);
            } catch (\Throwable $exception) {
                $this->renderer->renderError($exception->getMessage());
            }
        }

        $this->witcherUserRepository->flush();
    }

    /**
     * @param User[] $jiraUsers
     * @param array<string, array{id: integer, invitationEmail: string, active: bool}> $userApiResults
     */
    private function process(array $jiraUsers, array $userApiResults, UserMigrationStepStorage $userMigrationStorage): void
    {
        $witcherUsers = $this->witcherUserRepository->findWitcherUserByJiraAccountIds(
            \array_unique(\array_map(static fn (User $user): string => $user->getAccountId(), $jiraUsers)),
            false
        );

        foreach ($jiraUsers as $jiraUser) {
            try {
                $existingUser = $witcherUsers[$jiraUser->getAccountId()] ?? null;
                $user = $userApiResults[$jiraUser->getEmailAddress()] ?? null;

                if (null === $user && \in_array($jiraUser->getEmailAddress(), [null, ''], true)) {
                    throw new \LogicException('User email can\'t be empty');
                }

                $userMigrationStorage->setUser($user);
                $this->renderer->renderNotice(
                    \sprintf(
                        '%s \'%s\' user',
                        null !== $existingUser ? 'Updating' : 'Persisting',
                        $jiraUser->getEmailAddress())
                );

                $this->userMigrationFlowHandler->process(
                    $userMigrationStorage,
                    $existingUser ?? new WitcherUser(),
                    $jiraUser
                );

                if (0 === $userMigrationStorage->getBuffer() % self::BATCH_SIZE) {
                    $this->witcherUserRepository->flush();
                    $userMigrationStorage->resetBuffer();
                }
            } catch (ValidationException $exception) {
                $this->renderer->renderError($exception->getMessage());
            } catch (ORMException|UniqueConstraintViolationException $exception) {
                $this->witcherUserRepository->restoreEntityManager();
                $this->renderer->renderError('Exception occurred during migration. Please see logs for details');
                $this->logger->error($exception->getMessage(), ['error' => $exception]);
            } catch (\Throwable $exception) {
                $this->logger->error($exception->getMessage(), ['error' => $exception]);
                $this->renderer->renderError('Exception occurred during migration. Please see logs for details');
            }
        }
    }

    private function setup(): UserMigrationStepStorage
    {
        return new UserMigrationStepStorage($this->roleRepository->findAll());
    }

    private function deactivateUser(string $jiraAccount): void
    {
        if ('' === $jiraAccount) {
            $this->renderer->renderNotice('Jira account parameter is required and can\'t be empty');

            return;
        }

        $witcherUser = $this->witcherUserRepository->findOneBy(['jiraAccount' => $jiraAccount]);

        if (null === $witcherUser) {
            $this->renderer->renderNotice(\sprintf('User with account %s was not found', $jiraAccount));

            return;
        }

        $this->userManagerApi->deactivateUserById($witcherUser->getUserId());
        $this->witcherUserRepository->remove($witcherUser, true);
    }
}
