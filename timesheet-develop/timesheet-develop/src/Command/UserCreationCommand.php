<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Command;

use Jagaad\WitcherApi\UserCreator\Questionnaire\QuestionnaireHelper;
use Jagaad\WitcherApi\UserCreator\QuestionnaireContext;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class UserCreationCommand extends Command
{
    private QuestionnaireContext $questionnaireContext;

    protected static $defaultName = 'user:create';

    public function __construct(QuestionnaireContext $questionnaireContext, string $name = null)
    {
        parent::__construct($name);

        $this->questionnaireContext = $questionnaireContext;
    }

    protected function configure(): void
    {
        $this->setDescription('Creates user in Jagaad-User-Api and Witcher, if user is valid and does not exist');
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $helper = $this->getHelper('question');

        if (!$helper instanceof QuestionHelper) {
            throw new \LogicException('Invalid helper type returned');
        }

        $this->questionnaireContext->start(
            new QuestionnaireHelper($input, $output, $helper)
        );

        return self::SUCCESS;
    }
}
