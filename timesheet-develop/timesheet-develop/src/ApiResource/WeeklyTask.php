<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\ApiResource;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Jagaad\WitcherApi\Enum\ContextGroup;
use Jagaad\WitcherApi\Security\ReadableResourceInterface;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     attributes={"pagination_enabled": false},
 *     normalizationContext={"groups"={ContextGroup::GROUP_WEEKLY_TASK_READ}},
 *     itemOperations={
 *         "get"={
 *             "path"="/tasks/{id}/weekly",
 *         },
 *     },
 *     collectionOperations={},
 *     graphql={"item_query"}
 * )
 */
class WeeklyTask implements ReadableResourceInterface
{
    /**
     * @ApiProperty(identifier=true)
     *
     * @Groups({ContextGroup::GROUP_WEEKLY_TASK_READ})
     */
    private string $task;

    /**
     * @Groups({ContextGroup::GROUP_WEEKLY_TASK_READ})
     */
    private int $weeklyWorkTime;

    /**
     * @Groups({ContextGroup::GROUP_WEEKLY_TASK_READ})
     */
    private int $weeklyWorkSp;

    /**
     * @Groups({ContextGroup::GROUP_WEEKLY_TASK_READ})
     */
    private int $todayWorkTime;

    /**
     * @Groups({ContextGroup::GROUP_WEEKLY_TASK_READ})
     */
    private int $todayWorkSp;

    /**
     * @Groups({ContextGroup::GROUP_WEEKLY_TASK_READ})
     */
    private int $totalTaskTime;

    /**
     * @Groups({ContextGroup::GROUP_WEEKLY_TASK_READ})
     */
    private int $totalTaskSp;

    /**
     * @Groups({ContextGroup::GROUP_WEEKLY_TASK_READ})
     */
    private int $taskEstimationTime;

    /**
     * @Groups({ContextGroup::GROUP_WEEKLY_TASK_READ})
     */
    private int $taskEstimationSp;

    /**
     * @Groups({ContextGroup::GROUP_WEEKLY_TASK_READ})
     */
    private ?string $efficiencyRate;

    /**
     * @Groups({ContextGroup::GROUP_WEEKLY_TASK_READ})
     */
    private ?string $efficiencyRateSp;

    public function __construct(
        string $task,
        int $weeklyWorkTime,
        int $weeklyWorkSp,
        int $todayWorkTime,
        int $todayWorkSp,
        int $totalTaskTime,
        int $totalTaskSp,
        int $taskEstimationTime,
        int $taskEstimationSp
    ) {
        $this->task = $task;
        $this->weeklyWorkTime = $weeklyWorkTime;
        $this->weeklyWorkSp = $weeklyWorkSp;

        $this->todayWorkTime = $todayWorkTime;
        $this->todayWorkSp = $todayWorkSp;

        $this->totalTaskTime = $totalTaskTime;
        $this->totalTaskSp = $totalTaskSp;

        $this->taskEstimationTime = $taskEstimationTime;
        $this->taskEstimationSp = $taskEstimationSp;

        $this->efficiencyRate = $taskEstimationTime > 0
            ? \number_format(($totalTaskTime * 100) / $taskEstimationTime, 2)
            : null;

        $this->efficiencyRateSp = $taskEstimationSp > 0
            ? \number_format(($totalTaskSp * 100) / $taskEstimationSp, 2)
            : null;
    }

    public static function create(
        string $task,
        int $weeklyWorkTime,
        int $weeklyWorkSp,
        int $todayWorkTime,
        int $todayWorkSp,
        int $totalTaskTime,
        int $totalTaskSp,
        int $taskEstimationTime,
        int $taskEstimationSp
    ): self {
        return new self(
            $task,
            $weeklyWorkTime,
            $weeklyWorkSp,
            $todayWorkTime,
            $todayWorkSp,
            $totalTaskTime,
            $totalTaskSp,
            $taskEstimationTime,
            $taskEstimationSp
        );
    }

    public function getTask(): string
    {
        return $this->task;
    }

    public function getWeeklyWorkTime(): int
    {
        return $this->weeklyWorkTime;
    }

    public function getWeeklyWorkSp(): int
    {
        return $this->weeklyWorkSp;
    }

    public function getTodayWorkTime(): int
    {
        return $this->todayWorkTime;
    }

    public function getTodayWorkSp(): int
    {
        return $this->todayWorkSp;
    }

    public function getTotalTaskTime(): int
    {
        return $this->totalTaskTime;
    }

    public function getTotalTaskSp(): int
    {
        return $this->totalTaskSp;
    }

    public function getTaskEstimationTime(): int
    {
        return $this->taskEstimationTime;
    }

    public function getTaskEstimationSp(): int
    {
        return $this->taskEstimationSp;
    }

    public function getEfficiencyRate(): ?string
    {
        return $this->efficiencyRate;
    }

    public function getEfficiencyRateSp(): ?string
    {
        return $this->efficiencyRateSp;
    }
}
