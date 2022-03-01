<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Integration\Application\GitManagement\GitLab\Serializer\Normalizer;

use Jagaad\WitcherApi\ApiResource\GitEvent;
use Symfony\Component\Serializer\Normalizer\ContextAwareDenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

final class GitlabEventDenormalizer implements ContextAwareDenormalizerInterface
{
    // Action types
    private const PUSHED_TO = 'pushed to';
    private const DELETED = 'deleted';

    private ObjectNormalizer $normalizer;

    public function __construct(ObjectNormalizer $normalizer)
    {
        $this->normalizer = $normalizer;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsDenormalization($data, string $type, string $format = null, array $context = []): bool
    {
        return GitEvent::class === $format;
    }

    /**
     * @return GitEvent
     */
    public function denormalize($data, string $type, string $format = null, array $context = []): GitEvent
    {
        $event = [
            'id' => $data['id'] ?? null,
            'projectId' => $data['project_id'] ?? null,
            'createdAt' => $data['created_at'] ?? null,
            'author' => $data['author'] ?? null,
            'action' => $data['action_name'],
            'title' => $data['target_title'] ?? '',
            'targetType' => $data['target_type'] ?? '',
        ];

        if (\in_array($data['action_name'], [self::PUSHED_TO, self::DELETED], true)) {
            $event['content'] = $data['note']['body'] ?? null;
            $event['title'] = $data['push_data']['commit_title'] ?? '';
            $event['targetType'] = $data['push_data']['ref'] ?? '';
        }

        return $this->normalizer->denormalize($event, $type, $format, $context);
    }
}
