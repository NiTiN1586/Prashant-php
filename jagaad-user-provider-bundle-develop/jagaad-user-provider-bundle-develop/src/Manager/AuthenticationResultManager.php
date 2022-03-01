<?php

declare(strict_types=1);

namespace Jagaad\UserProviderBundle\Manager;

use Jagaad\UserProviderBundle\DataClass\AuthenticationResult\AuthenticationResultInterface;
use Jagaad\UserProviderBundle\DataClass\AuthenticationResult\FailedAuthenticationResult;
use Jagaad\UserProviderBundle\DataClass\AuthenticationResult\SuccessfulAuthenticationResult;
use Jagaad\UserProviderBundle\Enum\SessionKey;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class AuthenticationResultManager
{
    private SessionInterface $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    public function storeSuccessfulAuthenticationResult(UserInterface $user): void
    {
        $this->session->set(SessionKey::SESSION_KEY_AUTHENTICATION_RESULT, new SuccessfulAuthenticationResult($user));
    }

    public function storeFailedAuthenticationResult(\Exception $exception): void
    {
        $this->session->set(SessionKey::SESSION_KEY_AUTHENTICATION_RESULT, new FailedAuthenticationResult($exception));
    }
    
    public function getLatestAuthenticationResult(): ?AuthenticationResultInterface
    {
        return $this->session->get(SessionKey::SESSION_KEY_AUTHENTICATION_RESULT); 
    }

    public function invaildateLatestAuthenticationResult(): void
    {
        $this->session->remove(SessionKey::SESSION_KEY_AUTHENTICATION_RESULT);
    }
}
