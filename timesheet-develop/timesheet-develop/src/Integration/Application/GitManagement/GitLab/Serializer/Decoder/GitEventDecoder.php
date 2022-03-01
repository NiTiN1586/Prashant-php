<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Integration\Application\GitManagement\GitLab\Serializer\Decoder;

use Jagaad\WitcherApi\Integration\Domain\GitManagement\Message\GitEvent;
use Symfony\Component\Serializer\Encoder\DecoderInterface;

final class GitEventDecoder implements DecoderInterface
{
    private const SUPPORTING_FORMAT = 'git_event';
    private const EVENT_TYPE_MARKER = '0000000000000000000000000000000000000000';
    private const EVENT = 'push';

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

        if (!isset($data['event_name'], $data['before'], $data['after'])
            || self::EVENT !== $data['event_name']
            || (self::EVENT_TYPE_MARKER !== $data['before'] && self::EVENT_TYPE_MARKER !== $data['after'])
        ) {
            return [];
        }

        $webhookEvent = self::EVENT_TYPE_MARKER === $data['before'] ? GitEvent::BRANCH_CREATED : GitEvent::BRANCH_DELETED;
        $branchReference = \explode('/', $data['ref'] ?? []);

        return [
            'event' => $webhookEvent,
            'branch' => \end($branchReference),
            'project' => $data['project_id'] ?? '',
        ];
    }

    public function supportsDecoding(string $format): bool
    {
        return self::SUPPORTING_FORMAT === $format;
    }
}
