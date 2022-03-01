<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Integration\Application\IssueTracker\Jira\Migration\IssueMigrationStep;

use Jagaad\WitcherApi\Entity\Task;
use Jagaad\WitcherApi\Integration\Application\IssueTracker\Jira\DTO\IssueMigrationStepStorage;
use Jagaad\WitcherApi\Integration\Domain\IssueTracker\Issue;
use Jagaad\WitcherApi\Integration\Domain\IssueTracker\IssueField;
use Jagaad\WitcherApi\Integration\Migration\Interfaces\IssueMigrationStepInterface;
use Jagaad\WitcherApi\Repository\WitcherUserRepository;

final class TrackerUserPopulationStep implements IssueMigrationStepInterface
{
    private WitcherUserRepository $witcherUserRepository;

    public function __construct(WitcherUserRepository $witcherUserRepository)
    {
        $this->witcherUserRepository = $witcherUserRepository;
    }

    public static function getPriority(): int
    {
        return \count(self::ISSUE_STEP_PRIORITY) - \array_search(self::STEP_TRACKER_USER, self::ISSUE_STEP_PRIORITY, true);
    }

    public function process(
        IssueMigrationStepStorage $migrationDTO,
        Issue $trackerIssue,
        Task $task
    ): void {
        /** @var IssueField $responseFields */
        $responseFields = $trackerIssue->getFields();
        $issueStatus = $migrationDTO->getStatus()[$responseFields->getStatusName()] ?? null;

        if (null === $issueStatus) {
            throw new \LogicException(\sprintf('Issue Status \'%s\' doesn\'t exist. Run Status migration', $responseFields->getStatusName()));
        }

        $jiraAccountIds = [];
        $jiraAccountIds[] = $responseFields->getAssigneeAccountId();
        $jiraAccountIds[] = $responseFields->getReporterAccountId();
        $jiraAccountIds[] = $responseFields->getCreatorAccountId();

        $jiraAccountIds = \array_unique(
            \array_filter($jiraAccountIds)
        );

        $this->populateWitcherUserAssignedTask($task, $responseFields, $jiraAccountIds);
    }

    /**
     * @param string[] $jiraAccountIds
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    private function populateWitcherUserAssignedTask(Task $destination, IssueField $responseFields, array $jiraAccountIds): void
    {
        $witcherUsers = $this->witcherUserRepository->findWitcherUserByJiraAccountIds($jiraAccountIds, false);

        $createdBy = $witcherUsers[$responseFields->getCreatorAccountId()] ?? null;

        if (null === $createdBy) {
            throw new \LogicException('Creator user should be set');
        }

        $destination->setReporter($witcherUsers[$responseFields->getReporterAccountId()] ?? null);
        $destination->setAssignee($witcherUsers[$responseFields->getAssigneeAccountId()] ?? null);
        $destination->setCreatedBy($createdBy);
    }
}
