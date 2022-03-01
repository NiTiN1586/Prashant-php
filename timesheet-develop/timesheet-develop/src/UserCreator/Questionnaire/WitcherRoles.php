<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\UserCreator\Questionnaire;

use Jagaad\WitcherApi\Entity\Role;
use Jagaad\WitcherApi\Entity\WitcherUser;
use Jagaad\WitcherApi\Questionnaire\Interfaces\QuestionnaireHelperInterface;
use Jagaad\WitcherApi\UserCreator\Questionnaire\Exception\QuestionnaireException;

final class WitcherRoles extends AbstractUserQuestionnaire
{
    public function process(QuestionnaireHelperInterface $questionnaireHelper, WitcherUser $witcherUser): void
    {
        /** @var string $role */
        $role = $this->processQuestionChoiceType($questionnaireHelper, false);

        $entity = $this->entityManager
            ->getRepository(Role::class)
            ->findOneBy(['name' => $role]);

        if (!$entity instanceof Role) {
            throw QuestionnaireException::create(\sprintf('Role %s was not found', $role));
        }

        $witcherUser->setRole($entity);
    }

    /**
     * @return string[];
     */
    protected function getChoices(): array
    {
        /** @var Role[] $roles */
        $roles = $this->entityManager->getRepository(Role::class)->findAll();

        return \array_map(static function (Role $role): string {
            return $role->getName();
        }, $roles);
    }
}
