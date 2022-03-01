<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\EventSubscriber\EventProcessor;

use Doctrine\ORM\Events;
use Jagaad\WitcherApi\Entity\Role;
use Jagaad\WitcherApi\Utils\StringUtils;

final class RoleEventProcessor implements EventProcessorInterface
{
    public function process(object $entity, string $lifecycleEvent): void
    {
        if (Events::prePersist === $lifecycleEvent && $entity instanceof Role) {
            $entity->setHandle(StringUtils::convertRoleNameToHandle($entity->getName()));
        }
    }
}
