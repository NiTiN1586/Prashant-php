<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Widget;

use Jagaad\UserProviderBundle\Security\Model\User;
use Jagaad\WitcherApi\ApiResource\WeeklyTask;
use Jagaad\WitcherApi\Entity\Task;
use Jagaad\WitcherApi\Repository\TaskRepository;
use Symfony\Component\Security\Core\Security;
use Webmozart\Assert\Assert;

final class WeeklyTaskWidget implements WidgetCalculationInterface
{
    private const DATE_FORMAT = 'Y-m-d';

    public function __construct(private Security $security, private TaskRepository $taskRepository)
    {
    }

    public function getCalculatedWidget(WidgetRequest $request): ?WeeklyTask
    {
        $slug = $request->getParam('slug');
        $user = $this->security->getUser();

        Assert::stringNotEmpty($slug);

        if (!$user instanceof User) {
            throw new \LogicException('Session contains incorrect user');
        }

        /** @var Task|null $task */
        $task = $this->taskRepository->findOneBy(['slug' => $slug]);

        Assert::isInstanceOf($task, Task::class, \sprintf('Task \'%s\' does not exist', $slug));

        $calculations = $this->taskRepository->getUserTaskCalculation($slug, $user->getId());

        $startOfWeek = (new \DateTime())->modify('Monday this week')->setTime(0, 0);
        $endOfWeek = (new \DateTime())->modify('Sunday this week')->setTime(0, 0);
        $today = (new \DateTime())->setTime(0, 0);

        $totalTaskTime = 0;
        $totalTaskSp = 0;

        $weeklyWorkTime = 0;
        $weeklyWorkSp = 0;

        $todayWorkTime = 0;
        $todayWorkSp = 0;

        foreach ($calculations as $date => $item) {
            $taskActivityDate = \DateTime::createFromFormat(self::DATE_FORMAT, $date)
                ->setTime(0, 0);

            if ($taskActivityDate >= $startOfWeek && $taskActivityDate <= $endOfWeek) {
                $weeklyWorkTime += $item['activityEstimationTime'];
                $weeklyWorkSp += $item['activityEstimationSp'];
            }

            if ($taskActivityDate == $today) {
                $todayWorkTime += $item['activityEstimationTime'];
                $todayWorkSp += $item['activityEstimationSp'];
            }

            $totalTaskTime += $item['activityEstimationTime'];
            $totalTaskSp += $item['activityEstimationSp'];
        }

        return WeeklyTask::create(
            $slug,
            $weeklyWorkTime,
            $weeklyWorkSp,
            $todayWorkTime,
            $todayWorkSp,
            $totalTaskTime,
            $totalTaskSp,
            $task->getEstimationTime(),
            $task->getEstimationSp()
        );
    }
}
