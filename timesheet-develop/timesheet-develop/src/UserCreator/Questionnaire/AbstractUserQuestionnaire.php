<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\UserCreator\Questionnaire;

use Doctrine\ORM\EntityManagerInterface;
use Jagaad\WitcherApi\Questionnaire\Interfaces\QuestionnaireHelperInterface;
use Jagaad\WitcherApi\UserCreator\Questionnaire\Interfaces\QuestionnaireInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class AbstractUserQuestionnaire implements QuestionnaireInterface
{
    protected const QUESTION_PROMPT_REGEXP = 'Please choose user %s ';
    protected const QUESTION_ERROR_REGEXP = '%s(s) is/are invalid.';
    protected const QUESTION_VALIDATION_ERROR = 'Answer is incorrect. Please try again';
    protected const PRIORITY = 0;

    protected EntityManagerInterface $entityManager;
    protected ValidatorInterface $validator;

    protected string $className;
    protected string $errorPrompt;
    protected string $questionPrompt;

    public function __construct(EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {
        $this->entityManager = $entityManager;
        $this->validator = $validator;

        $classNamespace = \explode('\\', static::class);
        $this->className = \end($classNamespace);
        $this->questionPrompt = \sprintf(self::QUESTION_PROMPT_REGEXP, $this->className);
        $this->errorPrompt = \sprintf(self::QUESTION_ERROR_REGEXP, $this->className);
    }

    public static function getPriority(): int
    {
        return static::PRIORITY;
    }

    /**
     * @return string[]|string
     */
    protected function processQuestionChoiceType(
        QuestionnaireHelperInterface $questionnaireHelper,
        bool $isMultipleChoice = true,
        bool $isTrimmable = false
    ) {
        $questionSuffix = $isMultipleChoice ? '(Choose multiple): ' : '(Choose one): ';

        $question = new ChoiceQuestion(
            $this->questionPrompt.$questionSuffix,
            $this->getChoices()
        );

        $question->setErrorMessage($this->errorPrompt);
        $question->setTrimmable($isTrimmable);
        $question->setMultiselect($isMultipleChoice);
        $question->setMaxAttempts(3);

        return $questionnaireHelper->ask($question);
    }

    /**
     * @param Constraint[] $validators
     */
    protected function processQuestion(
        QuestionnaireHelperInterface $questionnaireHelper,
        array $validators = [],
        bool $isTrimmable = false,
        bool $isMultiline = false,
        bool $required = true
    ): string {
        $postfix = true === $required ? '(Required): ' : ': ';

        $question = new Question($this->questionPrompt.$postfix);
        $question->setTrimmable($isTrimmable);
        $question->setMaxAttempts(3);
        $question->setMultiline($isMultiline);

        if (\count($validators) > 0) {
            $question->setValidator(function (string $answer) use ($validators): string {
                $errors = $this->validator->validate($answer, $validators);

                if (\count($errors) > 0) {
                    throw new \RuntimeException(static::QUESTION_VALIDATION_ERROR);
                }

                return $answer;
            });
        }

        $response = $questionnaireHelper->ask($question);

        if (!\is_string($response)) {
            throw new \RuntimeException('Response type should be string');
        }

        return \trim($response);
    }

    /**
     * @return string[]
     */
    protected function getChoices(): array
    {
        return [];
    }
}
