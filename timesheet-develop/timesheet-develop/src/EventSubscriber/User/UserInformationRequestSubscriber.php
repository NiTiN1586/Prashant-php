<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\EventSubscriber\User;

use ApiPlatform\Core\EventListener\EventPriorities;
use Jagaad\UserProviderBundle\Security\Model\User;
use Jagaad\WitcherApi\Repository\WitcherUserRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

final class UserInformationRequestSubscriber implements EventSubscriberInterface
{
    private TokenStorageInterface $tokenStorage;
    private WitcherUserRepository $witcherUserRepository;

    public function __construct(TokenStorageInterface $tokenStorage, WitcherUserRepository $witcherUserRepository)
    {
        $this->tokenStorage = $tokenStorage;
        $this->witcherUserRepository = $witcherUserRepository;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => ['authenticatedUserInfo', EventPriorities::PRE_READ],
        ];
    }

    public function authenticatedUserInfo(RequestEvent $responseEvent): void
    {
        $request = $responseEvent->getRequest();

        if ('api_witcher_users_get_item' !== $request->attributes->get('_route')) {
            return;
        }

        if (Request::METHOD_GET !== $request->getMethod() || 'me' !== $request->attributes->get('id')) {
            return;
        }

        $user = null !== $this->tokenStorage->getToken() ? $this->tokenStorage->getToken()->getUser() : null;

        if (!$user instanceof User) {
            return;
        }

        $witcherUser = $this->witcherUserRepository->findOneByUserId($user->getId());

        if (null === $witcherUser) {
            return;
        }

        $request->attributes->set('id', $witcherUser->getId());
    }
}
