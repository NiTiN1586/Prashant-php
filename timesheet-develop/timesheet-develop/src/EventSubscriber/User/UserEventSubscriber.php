<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\EventSubscriber\User;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Jagaad\UserProviderBundle\Exception\User\UserNotFoundException;
use Jagaad\UserProviderBundle\Manager\UserManager;
use Jagaad\UserProviderBundle\Security\Model\User;
use Jagaad\WitcherApi\Cache\CacheKeys;
use Jagaad\WitcherApi\Entity\Permission;
use Jagaad\WitcherApi\Entity\WitcherUser;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class UserEventSubscriber implements EventSubscriber
{
    private UserManager $userManager;
    private CacheInterface $cache;

    public function __construct(UserManager $userManager, CacheInterface $cache)
    {
        $this->userManager = $userManager;
        $this->cache = $cache;
    }

    public function getSubscribedEvents(): array
    {
        return [
            Events::postLoad,
        ];
    }

    /**
     * TODO: should be refactored in WITCHER-292
     *
     * @throws UserNotFoundException
     */
    public function postLoad(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();

        if (!$entity instanceof WitcherUser || \PHP_SAPI === 'cli') {
            return;
        }

        // Get from cache the user
        $user = $this->cache->get(
            \sprintf(CacheKeys::USER_DATA_KEY, $entity->getUserId()),
            function (ItemInterface $item) use ($entity): ?User {
                // Set cache for 1 hour
                $item->expiresAfter(CacheKeys::USER_DATA_KEY_EXPIRE_1_HOUR);

                return $this->userManager->getUserById($entity->getUserId());
            }
        );

        if (null === $user) {
            throw new UserNotFoundException();
        }

        $role = $entity->getRole();

        $user->setRoles([$role->getHandle()]);
        $user->setPermissions(
            \array_map(
                static fn (Permission $permission) => $permission->getHandle(),
                $role->getPermissions()
            ),
        );

        $entity->setUser($user);
    }
}
