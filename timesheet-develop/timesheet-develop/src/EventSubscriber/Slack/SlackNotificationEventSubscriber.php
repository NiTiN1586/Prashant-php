<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\EventSubscriber\Slack;

use Jagaad\WitcherApi\Integration\Domain\DTO\EventContainer;
use Jagaad\WitcherApi\Integration\Domain\Interfaces\EventProcessorInterface;
use Jagaad\WitcherApi\Message\SlackNotificationEvent;
use Jagaad\WitcherApi\MessageHandler\SlackNotificationHandler;
use Monolog\Logger;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Messenger\Event\WorkerMessageFailedEvent;

class SlackNotificationEventSubscriber implements EventSubscriberInterface
{
    private bool $slackNotification;

    private string $slackChannel;

    private EventProcessorInterface $eventProcessor;

    public function __construct(bool $slackNotification, string $slackChannel, EventProcessorInterface $eventProcessor)
    {
        $this->slackNotification = $slackNotification;
        $this->slackChannel = $slackChannel;
        $this->eventProcessor = $eventProcessor;
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        if (!$this->slackNotification) {
            return;
        }

        // ignore all errors that are from 400 to 499
        if ($event->getThrowable()->getCode() >= 400 && $event->getThrowable()->getCode() <= 499) {
            return;
        }

        $this->sendMessageExceptionToSlack($event->getThrowable());
    }

    public function logFailedMessage(WorkerMessageFailedEvent $event): void
    {
        if ($event->willRetry()) {
            return;
        }

        $this->sendMessageExceptionToSlack($event->getThrowable());
    }

    /**
     * @return array<string, array<int|string, array<int|string,int|string>>>
     */
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => [
                ['onKernelException', 0],
            ],
            WorkerMessageFailedEvent::class => [
                ['logFailedMessage', 0],
            ],
        ];
    }

    private function sendMessageExceptionToSlack(\Throwable $exception): void
    {
        $exceptionDetails = [
            'exceptionClass' => \get_class($exception),
            'code' => $exception->getCode(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'traceAsString' => $exception->getTraceAsString(),
        ];

        /** @var \DateTimeImmutable $datetime */
        $datetime = \DateTimeImmutable::createFromFormat('U.u', \sprintf('%f', \microtime(true)));

        $eventContainer = EventContainer::create(
            new SlackNotificationEvent(
                $exception->getMessage(),
                [$exceptionDetails],
                Logger::CRITICAL,
                Logger::getLevelName(Logger::CRITICAL),
                $datetime,
                $this->slackChannel,
                []
            ),
            SlackNotificationHandler::SLACK_NOTIFICATION_EVENT
        );

        $this->eventProcessor->process($eventContainer);
    }
}
