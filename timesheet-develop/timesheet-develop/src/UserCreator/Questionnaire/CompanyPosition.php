<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\UserCreator\Questionnaire;

use Jagaad\WitcherApi\Entity\CompanyPosition as CompanyPositionEntity;
use Jagaad\WitcherApi\Entity\WitcherUser;
use Jagaad\WitcherApi\Questionnaire\Interfaces\QuestionnaireHelperInterface;
use Jagaad\WitcherApi\UserCreator\Questionnaire\Exception\QuestionnaireException;

final class CompanyPosition extends AbstractUserQuestionnaire
{
    /**
     * @throws Exception\QuestionnaireException
     */
    public function process(QuestionnaireHelperInterface $questionnaireHelper, WitcherUser $witcherUser): void
    {
        $companyPosition = $this->processQuestionChoiceType($questionnaireHelper, false);

        if (!\is_string($companyPosition)) {
            throw new \TypeError('Incorrect type for company position returned');
        }

        $entity = $this->entityManager
            ->getRepository(CompanyPositionEntity::class)
            ->findOneBy(['name' => $companyPosition]);

        if (null === $entity) {
            throw QuestionnaireException::create(\sprintf('Such \'%s\' was(were) not found in database', $this->className));
        }

        $witcherUser->setCompanyPosition($entity);
    }

    /**
     * @return string[]
     */
    protected function getChoices(): array
    {
        /** @var CompanyPositionEntity[] */
        $companyPositions = $this->entityManager->getRepository(CompanyPositionEntity::class)->findAll();

        return \array_map(static function (CompanyPositionEntity $companyPosition): string {
            return $companyPosition->getName();
        }, $companyPositions);
    }
}
