<?php

declare(strict_types=1);

namespace Jagaad\UserApi\Listener;

use Monolog\Handler\SlackWebhookHandler;
use Monolog\Logger;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class JagaadUserApiExceptionSlackListener
{
    private SlackWebhookHandler $slackWebhookHandler;

    private bool $slackNotification;

    private string $slackChannel;

    public function __construct(SlackWebhookHandler $slackWebhookHandler, bool $slackNotification, string $slackChannel)
    {
        $this->slackWebhookHandler = $slackWebhookHandler;
        $this->slackNotification = $slackNotification;
        $this->slackChannel = $slackChannel;
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        if (!$this->slackNotification) {
            return;
        }

        $this->sendMessageExceptionToSlack($event->getThrowable());
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
        $message = [
            'message' => '*'.$exception->getMessage().'*',
            'context' => [$exceptionDetails],
            'level' => Logger::CRITICAL,
            'level_name' => Logger::getLevelName(Logger::CRITICAL),
            'channel' => $this->slackChannel,
            'datetime' => $datetime,
            'extra' => [],
        ];

        $this->slackWebhookHandler->handle($message);
    }
}
