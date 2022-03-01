<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\UserCreator\Questionnaire\Exception;

use Jagaad\WitcherApi\Exception\BaseWitcherException;

final class QuestionnaireException extends BaseWitcherException
{
    protected static string $defaultMessage = 'Questionnaire error occurred.';
}
