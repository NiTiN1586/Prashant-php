<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\EventSubscriber\EventProcessor;

use Doctrine\ORM\Events;
use Jagaad\UserProviderBundle\Security\Model\User;
use Jagaad\WitcherApi\Entity\EntityAuthorInterface;
use Jagaad\WitcherApi\Entity\WitcherUser;
use Jagaad\WitcherApi\Repository\WitcherUserRepository;
use Symfony\Component\Security\Core\Security;

final class EntityAuthorEventProcessor implements EventProcessorInterface
{
    private Security $security;
    private WitcherUserRepository $witcherUserRepository;

    public function __construct(Security $security, WitcherUserRepository $witcherUserRepository)
    {
        $this->security = $security;
        $this->witcherUserRepository = $witcherUserRepository;
    }

    public function process(object $entity, string $lifecycleEvent): void
    {
        $user = $this->security->getUser();

        if (!$entity instanceof EntityAuthorInterface
            || !$user instanceof User
            || $this->invalidForProcessing($entity, $lifecycleEvent, $user)
        ) {
            return;
        }

        /** @var WitcherUser|null $witcherUser */
        $witcherUser = $this->witcherUserRepository->findOneBy(['userId' => $user->getId()]);

        if (null === $witcherUser) {
            return;
        }

        if (Events::prePersist === $lifecycleEvent) {
            $entity->setCreatedBy($witcherUser);

            return;
        }

        if (Events::preUpdate === $lifecycleEvent) {
            $entity->setUpdatedBy($witcherUser);
        }
    }

    private function invalidForProcessing(EntityAuthorInterface $entity, string $lifecycleEvent, User $user): bool
    {
        return (Events::prePersist === $lifecycleEvent && $entity->isCreatedByPopulated())
            || (
                Events::preUpdate === $lifecycleEvent
                && null !== $entity->getUpdatedBy()
                && $entity->getUpdatedBy()->getUserId() === $user->getId()
            );
    }
}
