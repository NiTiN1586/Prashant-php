<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Integration\Application\Processor;

use Jagaad\WitcherApi\Integration\Domain\DTO\EventContainer;
use Jagaad\WitcherApi\Integration\Domain\Interfaces\EventProcessorInterface;
use Symfony\Component\Messenger\Bridge\Amqp\Transport\AmqpStamp;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Validator\Exception\ValidationFailedException;

final class WebhookEventProcessor implements EventProcessorInterface
{
    private MessageBusInterface $messageBus;

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    /**
     * @throws ValidationFailedException
     * @throws \Throwable
     */
    public function process(EventContainer $eventContainer): void
    {
        try {
            $this->messageBus->dispatch(
                $eventContainer->getMessage(),
                [
                    new AmqpStamp($eventContainer->getRoute()),
                ]
            );
        } catch (\Throwable $exception) {
            throw $exception;
        }
    }
}
