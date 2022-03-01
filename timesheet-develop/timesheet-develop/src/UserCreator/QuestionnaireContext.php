<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\UserCreator;

use Doctrine\ORM\EntityManagerInterface;
use Jagaad\WitcherApi\Entity\WitcherUser;
use Jagaad\WitcherApi\Questionnaire\Interfaces\QuestionnaireHelperInterface;
use Jagaad\WitcherApi\UserCreator\Questionnaire\AbstractUserQuestionnaire;
use Jagaad\WitcherApi\UserCreator\Questionnaire\Exception\QuestionnaireException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class QuestionnaireContext
{
    /**
     * @var AbstractUserQuestionnaire[]
     */
    private iterable $questionnaire;

    private ValidatorInterface $validator;

    private EntityManagerInterface $entityManager;

    /**
     * @param AbstractUserQuestionnaire[] $questionnaire
     */
    public function __construct(
        iterable $questionnaire,
        ValidatorInterface $validator,
        EntityManagerInterface $entityManager
    ) {
        $this->questionnaire = $questionnaire;
        $this->validator = $validator;
        $this->entityManager = $entityManager;
    }

    public function start(QuestionnaireHelperInterface $helper): void
    {
        $witcherUser = new WitcherUser();

        foreach ($this->questionnaire as $question) {
            $question->process($helper, $witcherUser);
        }

        $errors = $this->validator->validate($witcherUser);

        if (\count($errors) > 0) {
            throw QuestionnaireException::create(\implode(\PHP_EOL, (array) $errors));
        }

        $this->entityManager->persist($witcherUser);
        $this->entityManager->flush();
    }
}
