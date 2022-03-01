<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Questionnaire\Interfaces;

use Symfony\Component\Console\Question\Question;

interface QuestionnaireHelperInterface
{
    /**
     * @return string[]|string
     */
    public function ask(Question $question): array|string;
}
