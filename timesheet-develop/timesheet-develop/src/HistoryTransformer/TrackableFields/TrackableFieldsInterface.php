<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\HistoryTransformer\TrackableFields;

interface TrackableFieldsInterface
{
    /**
     * @return string[]
     */
    public function getTrackableFields(): array;

    public function getObjectTrackableFiledLog(object $trackableObject, string $trackableField): ?string;

    public function getAlias(): string;
}
