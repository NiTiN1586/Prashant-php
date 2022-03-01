<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\HistoryTransformer\TrackableFields;

use Jagaad\WitcherApi\Enum\HistoryEntityType;

final class ActivityTrackableFields implements TrackableFieldsInterface
{
    public function getTrackableFields(): array
    {
        return [
            'comment',
            'duration',
        ];
    }

    public function getObjectTrackableFiledLog(object $trackableObject, string $trackableField): ?string
    {
        // Trackable fields for this entity may only be string

        return null;
    }

    public function getAlias(): string
    {
        return HistoryEntityType::ACTIVITY;
    }
}
