<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Constraint\TrackerTaskType;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
final class ContainsAllowedTrackerTaskType extends Constraint
{
    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }

    public function validatedBy(): string
    {
        return ContainsAllowedTrackerTaskTypeValidator::class;
    }
}
