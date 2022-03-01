<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\HistoryTransformer\TrackableFields;

use Jagaad\WitcherApi\Entity\Status;
use Jagaad\WitcherApi\Entity\WitcherUser;
use Jagaad\WitcherApi\Enum\HistoryEntityType;

final class TaskTrackableFields implements TrackableFieldsInterface
{
    public const ASSIGNEE = 'assignee';
    public const STATUS = 'status';

    public function getTrackableFields(): array
    {
        return [
            self::ASSIGNEE,
            self::STATUS,
        ];
    }

    public function getObjectTrackableFiledLog(object $trackableObject, string $trackableField): ?string
    {
        if (self::ASSIGNEE === $trackableField && $trackableObject instanceof WitcherUser) {
            return (string) $trackableObject->getId();
        }

        if (self::STATUS === $trackableField && $trackableObject instanceof Status) {
            return $trackableObject->getFriendlyName();
        }

        return null;
    }

    public function getAlias(): string
    {
        return HistoryEntityType::TASK;
    }
}
