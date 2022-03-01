<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\HistoryTransformer\TrackableFields;

use Jagaad\WitcherApi\Entity\Comment;
use Jagaad\WitcherApi\Enum\HistoryEntityType;

final class CommentTrackableFields implements TrackableFieldsInterface
{
    private const COMMENT = 'comment';

    public function getTrackableFields(): array
    {
        return [
            self::COMMENT,
        ];
    }

    public function getObjectTrackableFiledLog(object $trackableObject, string $trackableField): ?string
    {
        if (self::COMMENT === $trackableField && $trackableObject instanceof Comment) {
            return $trackableObject->getComment();
        }

        return null;
    }

    public function getAlias(): string
    {
        return HistoryEntityType::COMMENT;
    }
}
