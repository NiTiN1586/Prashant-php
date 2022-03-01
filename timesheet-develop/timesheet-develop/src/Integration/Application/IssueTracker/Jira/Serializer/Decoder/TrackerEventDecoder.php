<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Integration\Application\IssueTracker\Jira\Serializer\Decoder;

use Jagaad\WitcherApi\Integration\Domain\IssueTracker\Message\JiraTimeTrackerEvent;
use Symfony\Component\Serializer\Encoder\DecoderInterface;

final class TrackerEventDecoder implements DecoderInterface
{
    private const SUPPORTING_FORMAT = 'tracker_event';

    /**
     * @param array<mixed> $context
     *
     * @return array<string, mixed>
     *
     * @throws \JsonException
     */
    public function decode(string $data, string $format, array $context = []): array
    {
        $data = \json_decode($data, true, 512, \JSON_THROW_ON_ERROR);
        $webhookEvent = \explode(':', $data['webhookEvent'] ?? []);
        $webhookEvent = \end($webhookEvent);

        return $this->getEventData($webhookEvent, $data);
    }

    public function supportsDecoding(string $format): bool
    {
        return self::SUPPORTING_FORMAT === $format;
    }

    /**
     * @param array<string, mixed> $data
     *
     * @return array<string, mixed>
     */
    private function getEventData(string $webhookEvent, array $data): array
    {
        $eventData = [
            'event' => $webhookEvent,
            'timestamp' => $data['timestamp'] ?? null,
            'handle' => '',
            'params' => [],
        ];

        switch (true) {
            case \in_array($webhookEvent, JiraTimeTrackerEvent::AVAILABLE_ISSUE_EVENT_TYPES, true):
                $eventData['handle'] = $data['issue']['key'] ?? '';

                return $eventData;

            case \in_array($webhookEvent, JiraTimeTrackerEvent::AVAILABLE_PROJECT_EVENT_TYPES, true):
                $eventData['handle'] = $data['project']['key'] ?? '';

                return $eventData;

            case \in_array($webhookEvent, JiraTimeTrackerEvent::AVAILABLE_USER_EVENT_TYPES, true):
                $eventData['handle'] = $data['user']['accountId'] ?? '';

                return $eventData;

            case \in_array($webhookEvent, JiraTimeTrackerEvent::AVAILABLE_SPRINT_EVENT_TYPES, true):
                $eventData['handle'] = $data['sprint']['name'] ?? '';
                $eventData['params'] = [
                    'board' => $data['sprint']['originBoardId'] ?? null,
                    'sprint' => $data['sprint']['id'] ?? null,
                ];

                return $eventData;

            default:
                return $eventData;
        }
    }
}
