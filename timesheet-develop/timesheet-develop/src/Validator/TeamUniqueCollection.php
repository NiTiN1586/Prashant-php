<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class TeamUniqueCollection extends Constraint
{
    public string $message = 'Found TeamLeader in TeamMember list. Please try again with set different teamLeader';
}
