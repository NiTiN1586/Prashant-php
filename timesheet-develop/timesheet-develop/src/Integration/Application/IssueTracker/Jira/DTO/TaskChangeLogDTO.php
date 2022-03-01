<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Integration\Application\IssueTracker\Jira\DTO;

use Jagaad\WitcherApi\Entity\Task;
use Jagaad\WitcherApi\Entity\WitcherUser;
use Jagaad\WitcherApi\Integration\Domain\IssueTracker\ChangeLogHistoryItem;

class TaskChangeLogDTO
{
    private ?WitcherUser $author;
    private \DateTime $createdDate;
    private int $externalId;
    private Task $task;
    private string $jiraAccountId;

    /** @var ChangeLogHistoryItem[] */
    private array $changeLogItems;

    /**
     * @param ChangeLogHistoryItem[] $changeLogItems
     */
    public function __construct(
        Task $task,
        \DateTime $createdDate,
        int $externalId,
        ?WitcherUser $author,
        array $changeLogItems,
        string $jiraAccountId
    ) {
        $this->author = $author;
        $this->createdDate = $createdDate;
        $this->externalId = $externalId;
        $this->task = $task;
        $this->changeLogItems = $changeLogItems;
        $this->jiraAccountId = $jiraAccountId;
    }

    public function getAuthor(): ?WitcherUser
    {
        return $this->author;
    }

    public function getCreatedDate(): \DateTime
    {
        return $this->createdDate;
    }

    public function getExternalId(): int
    {
        return $this->externalId;
    }

    public function getTask(): Task
    {
        return $this->task;
    }

    public function getJiraAccountId(): string
    {
        return $this->jiraAccountId;
    }

    /**
     * @return ChangeLogHistoryItem[]
     */
    public function getChangeLogItems(): array
    {
        return $this->changeLogItems;
    }
}
