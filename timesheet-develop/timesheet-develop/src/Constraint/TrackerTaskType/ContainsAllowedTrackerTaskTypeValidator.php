<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Constraint\TrackerTaskType;

use Doctrine\Common\Collections\Collection;
use Jagaad\WitcherApi\Entity\Task;
use Jagaad\WitcherApi\Entity\TrackerTaskType;
use Jagaad\WitcherApi\Entity\WitcherProjectTrackerTaskType;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

final class ContainsAllowedTrackerTaskTypeValidator extends ConstraintValidator
{
    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$value instanceof Task) {
            return;
        }

        $witcherProjectTaskTypes = $value->getWitcherProject()->getWitcherProjectTrackerTaskTypes();
        $trackerTaskType = $value->getTrackerTaskType();

        $matchedWitcherProjectTaskType = $this->getMatchedWitcherProjectTaskType(
            $witcherProjectTaskTypes,
            $trackerTaskType
        );

        if (null === $matchedWitcherProjectTaskType) {
            $this->context->buildViolation('trackerTaskType \'{{ taskType }}\' is not assigned to project.')
                ->setParameter('{{ taskType }}', $trackerTaskType->getFriendlyName())
                ->addViolation();

            return;
        }

        if ($matchedWitcherProjectTaskType->isDeleted()) {
            $this->context->buildViolation('\'{{ taskType }}\' is not active in project relation.')
                ->setParameter('{{ taskType }}', $trackerTaskType->getFriendlyName())
                ->addViolation();
        }
    }

    /**
     * @param Collection<int, WitcherProjectTrackerTaskType> $witcherProjectTrackerTaskTypes
     */
    private function getMatchedWitcherProjectTaskType(Collection $witcherProjectTrackerTaskTypes, TrackerTaskType $taskType): ?WitcherProjectTrackerTaskType
    {
        /** @var WitcherProjectTrackerTaskType $witcherProjectTrackerTaskType */
        foreach ($witcherProjectTrackerTaskTypes as $witcherProjectTrackerTaskType) {
            if ($witcherProjectTrackerTaskType->getTrackerTaskType()->getFriendlyName() === $taskType->getFriendlyName()) {
                return $witcherProjectTrackerTaskType;
            }
        }

        return null;
    }
}
