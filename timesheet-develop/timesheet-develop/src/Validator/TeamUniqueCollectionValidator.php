<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Validator;

use Jagaad\WitcherApi\Entity\WitcherUser;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class TeamUniqueCollectionValidator extends ConstraintValidator
{
    private PropertyAccessor $propertyAccessor;

    public function __construct()
    {
        $this->propertyAccessor = PropertyAccess::createPropertyAccessor();
    }

    /**
     * @param mixed $collections
     * @param Constraint $constraint
     */
    public function validate($collections, Constraint $constraint): void
    {
        if (!$constraint instanceof TeamUniqueCollection) {
            throw new UnexpectedTypeException($constraint, TeamUniqueCollection::class);
        }

        if (null === $collections) {
            return;
        }

        if (null === $this->context->getRoot()->getTeamLeader()) {
            return;
        }

        $teamLeaderValue = $this->context->getRoot()->getTeamLeader()->getId();

        $propertyValues = [];
        /** @var WitcherUser $witcherObj */
        foreach ($collections as $witcherObj) {
            $propertyValue = $this->propertyAccessor->getValue($witcherObj, 'id');

            $propertyValues[] = $propertyValue;
        }

        if (\in_array($teamLeaderValue, $propertyValues, true)) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}
