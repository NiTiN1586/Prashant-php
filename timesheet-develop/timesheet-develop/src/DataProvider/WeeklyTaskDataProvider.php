<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\DataProvider;

use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use ApiPlatform\Core\DataProvider\SerializerAwareDataProviderInterface;
use ApiPlatform\Core\DataProvider\SerializerAwareDataProviderTrait;
use Doctrine\ORM\ORMException;
use Jagaad\WitcherApi\ApiResource\WeeklyTask;
use Jagaad\WitcherApi\Integration\Application\Exception\DataProviderException;
use Jagaad\WitcherApi\Widget\WidgetCalculationInterface;
use Jagaad\WitcherApi\Widget\WidgetRequest;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;
use Webmozart\Assert\Assert;

final class WeeklyTaskDataProvider implements ItemDataProviderInterface, RestrictedDataProviderInterface, SerializerAwareDataProviderInterface
{
    use SerializerAwareDataProviderTrait;

    public function __construct(private WidgetCalculationInterface $widgetCalculation, private LoggerInterface $logger)
    {
    }

    /**
     * @param array<mixed>|int|string $id
     * @param array<mixed> $context
     */
    public function getItem(string $resourceClass, $id, string $operationName = null, array $context = []): ?WeeklyTask
    {
        try {
            Assert::stringNotEmpty($id, 'task slug should be passed');

            $widget = $this->widgetCalculation->getCalculatedWidget(WidgetRequest::fromParams(['slug' => $id]));

            if (!$widget instanceof WeeklyTask) {
                throw new \LogicException('Incorrect widget service used');
            }

            return $widget;
        } catch (\LogicException|ORMException $exception) {
            $this->logger->error($exception->getMessage(), ['error' => $exception]);
            throw DataProviderException::create('Weekly task data can\'t be fetched', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param array<mixed> $context
     */
    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return WeeklyTask::class === $resourceClass;
    }
}
