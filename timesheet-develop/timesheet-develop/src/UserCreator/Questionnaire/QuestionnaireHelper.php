<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\UserCreator\Questionnaire;

use Jagaad\WitcherApi\Questionnaire\Interfaces\QuestionnaireHelperInterface;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

final class QuestionnaireHelper implements QuestionnaireHelperInterface
{
    private InputInterface $input;
    private OutputInterface $output;
    private QuestionHelper $questionHelper;

    public function __construct(InputInterface $input, OutputInterface $output, QuestionHelper $questionHelper)
    {
        $this->input = $input;
        $this->output = $output;
        $this->questionHelper = $questionHelper;
    }

    /**
     * {@inheritdoc}
     */
    public function ask(Question $question): array|string
    {
        return $this->questionHelper->ask($this->input, $this->output, $question);
    }
}
