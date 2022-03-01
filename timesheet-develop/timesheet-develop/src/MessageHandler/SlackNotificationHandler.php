<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\MessageHandler;

use Jagaad\WitcherApi\Message\SlackNotificationEvent;
use Monolog\Handler\SlackWebhookHandler;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class SlackNotificationHandler implements MessageHandlerInterface
{
    public const SLACK_NOTIFICATION_EVENT = 'slack.notification.events';

    private SlackWebhookHandler $slackWebhookHandler;

    private LoggerInterface $logger;

    public function __construct(
        SlackWebhookHandler $slackWebhookHandler,
        LoggerInterface $logger
    ) {
        $this->slackWebhookHandler = $slackWebhookHandler;
        $this->logger = $logger;
    }

    public function __invoke(SlackNotificationEvent $event): void
    {
        try {
            $message = [
                'message' => '*'.$event->getMessage().'*',
                'context' => $event->getContext(),
                'level' => $event->getLevel(),
                'level_name' => $event->getLevelName(),
                'channel' => $event->getChannel(),
                'datetime' => $event->getDatetime(),
                'extra' => $event->getExtra(),
            ];

            /** @phpstan-ignore-next-line */
            $this->slackWebhookHandler->handle($message);
        } catch (\Throwable $exception) {
            $this->logger->error((string) $exception, ['error' => $exception]);
        }
    }
}
