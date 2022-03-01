<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\UserCreator\Questionnaire;

use Assert\Assertion;
use Jagaad\WitcherApi\Entity\Department as DepartmentEntity;
use Jagaad\WitcherApi\Entity\WitcherUser;
use Jagaad\WitcherApi\Questionnaire\Interfaces\QuestionnaireHelperInterface;
use Jagaad\WitcherApi\UserCreator\Questionnaire\Exception\QuestionnaireException;

final class Department extends AbstractUserQuestionnaire
{
    /**
     * @throws QuestionnaireException
     */
    public function process(QuestionnaireHelperInterface $questionnaireHelper, WitcherUser $witcherUser): void
    {
        /** @var string[] */
        $departments = $this->processQuestionChoiceType($questionnaireHelper);

        Assertion::allString(
            $departments,
            'Departments should be of type array'
        );

        /** @var array<int, DepartmentEntity> */
        $entities = $this->entityManager
            ->getRepository(DepartmentEntity::class)
            ->findBy(['name' => $departments]);

        if (0 === \count($entities)) {
            throw QuestionnaireException::create(\sprintf('Such \'%s\' was(were) not found in database', $this->className));
        }

        $witcherUser->setDepartments($entities);
    }

    /**
     * @return string[]
     */
    protected function getChoices(): array
    {
        /** @var DepartmentEntity[] */
        $departments = $this->entityManager->getRepository(DepartmentEntity::class)->findAll();

        return \array_map(static function (DepartmentEntity $department): string {
            return $department->getName();
        }, $departments);
    }
}
