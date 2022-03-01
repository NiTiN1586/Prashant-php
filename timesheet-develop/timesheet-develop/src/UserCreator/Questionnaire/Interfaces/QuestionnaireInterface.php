<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\UserCreator\Questionnaire\Interfaces;

use Jagaad\WitcherApi\Entity\WitcherUser;
use Jagaad\WitcherApi\Questionnaire\Interfaces\QuestionnaireHelperInterface;

interface QuestionnaireInterface
{
    public function process(QuestionnaireHelperInterface $questionnaireHelper, WitcherUser $witcherUser): void;

    public static function getPriority(): int;
}
