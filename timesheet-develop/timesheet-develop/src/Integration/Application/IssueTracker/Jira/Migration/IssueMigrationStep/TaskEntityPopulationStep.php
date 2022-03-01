<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Integration\Application\IssueTracker\Jira\Migration\IssueMigrationStep;

use Jagaad\WitcherApi\Entity\Task;
use Jagaad\WitcherApi\Integration\Application\IssueTracker\Jira\Converter\Interfaces\JiraDescriptionConverterInterface;
use Jagaad\WitcherApi\Integration\Application\IssueTracker\Jira\DTO\IssueMigrationStepStorage;
use Jagaad\WitcherApi\Integration\Domain\IssueTracker\Issue;
use Jagaad\WitcherApi\Integration\Domain\IssueTracker\IssueField;
use Jagaad\WitcherApi\Integration\Migration\Interfaces\IssueMigrationStepInterface;

final class TaskEntityPopulationStep implements IssueMigrationStepInterface
{
    private string $issueTrackerHost;
    private JiraDescriptionConverterInterface $descriptionConverter;

    public function __construct(JiraDescriptionConverterInterface $descriptionConverter, string $issueTrackerHost)
    {
        $this->issueTrackerHost = $issueTrackerHost;
        $this->descriptionConverter = $descriptionConverter;
    }

    public static function getPriority(): int
    {
        return \count(self::ISSUE_STEP_PRIORITY) - \array_search(self::STEP_TASK_ENTITY_POPULATION, self::ISSUE_STEP_PRIORITY, true);
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

        $task->setWitcherProject($migrationDTO->getProject());

        $task->setExternalKey($trackerIssue->getKey());
        $task->setSummary(\trim($responseFields->getSummary()));

        if (null !== $responseFields->getPriority()) {
            $task->setPriority(
                $migrationDTO->getPriorities()[$responseFields->getPriorityName()] ?? null
            );
        }

        $task->setStatus($issueStatus);
        $task->setCreatedAt(\DateTime::createFromImmutable($responseFields->getCreated()));

        $task->setExternalTrackerLink(
            \sprintf(
                '%s/browse/%s',
                \rtrim($this->issueTrackerHost, '/'),
                $trackerIssue->getKey()
            )
        );

        if (null !== $responseFields->getDescription()) {
            $task->setDescription(
                \trim($this->descriptionConverter->convert($responseFields->getDescription()))
            );
        }
    }
}
