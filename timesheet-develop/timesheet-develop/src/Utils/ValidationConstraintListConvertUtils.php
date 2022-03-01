<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Utils;

use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationListInterface;

final class ValidationConstraintListConvertUtils
{
    public static function convertConstraintListToString(ConstraintViolationListInterface $errors): string
    {
        if (0 === $errors->count()) {
            return '';
        }

        $message = '';

        /** @var ConstraintViolation[] $errors */
        foreach ($errors as $error) {
            $message .= $error->getMessage().\PHP_EOL;
        }

        return \trim($message);
    }
}
