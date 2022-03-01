<?php

declare(strict_types=1);

namespace Jagaad\UserProviderBundle\Security;

use Jagaad\UserProviderBundle\Exception\Authentication\UserProviderAuthenticationException;
use Jagaad\UserProviderBundle\Manager\AuthenticationManager;
use Jagaad\UserProviderBundle\Manager\AuthenticationResultManager;
use Jagaad\UserProviderBundle\Security\Model\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;

class UserProviderAuthenticator extends AbstractFormLoginAuthenticator
{
    private string $googleAuthenticationLoginRoute;

    private string $loginRoute;

    private AuthenticationManager $authenticationManager;

    private UrlGeneratorInterface $urlGenerator;
    
    private AuthenticationResultManager $authenticationResultManager;

    private AuthenticatedUserProcessorInterface $authenticatedUserProcessor;

    private Request $request;

    public function __construct(
        string $googleAuthenticationLoginRoute,
        string $loginRoute,
        AuthenticationManager $authenticationManager,
        UrlGeneratorInterface $urlGenerator,
        AuthenticationResultManager $authenticationResultManager,
        AuthenticatedUserProcessorInterface $authenticatedUserProcessor,
        RequestStack $requestStack
    ) {
        $this->googleAuthenticationLoginRoute = $googleAuthenticationLoginRoute;
        $this->loginRoute = $loginRoute;
        $this->authenticationManager = $authenticationManager;
        $this->urlGenerator = $urlGenerator;
        $this->authenticationResultManager = $authenticationResultManager;
        $this->authenticatedUserProcessor = $authenticatedUserProcessor;
        $this->request = $requestStack->getCurrentRequest();
    }

    public function supports(Request $request): bool
    {
        return $this->googleAuthenticationLoginRoute === $request->attributes->get('_route')
            && $request->isMethod(Request::METHOD_GET);
    }

    public function getCredentials(Request $request): array
    {
        return [
            'googleAuthenticationCode' => $request->query->get('code'),
        ];
    }
    
    public function getUser($credentials, UserProviderInterface $userProvider): ?User
    {
        try {
            $authenticationCode = $credentials['googleAuthenticationCode'] ?? null;

            if (null === $authenticationCode) {
                throw new UserProviderAuthenticationException('Authentication code is missing in a callback payload');
            }

            $authenticatedUser = $this
                ->authenticationManager
                ->getAuthenticatedUserByAuthenticationCode(
                    $authenticationCode,
                    $this->request->query->get('postLoginRedirectUrl')
                );

            // Decorate "jagaad.user_processor" to apply your custom logic and validation for aan authenticated user
            $this->authenticatedUserProcessor->process($authenticatedUser);

            $this->authenticationResultManager->storeSuccessfulAuthenticationResult($authenticatedUser);
            
            return $authenticatedUser;
        } catch (\Exception $exception) {
            $this->authenticationResultManager->storeFailedAuthenticationResult($exception);

            return null;
        }
    }

    public function checkCredentials($credentials, UserInterface $user): bool
    {
        return true;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey): ?Response
    {
        return null;
    }

    protected function getLoginUrl(): string
    {
        return $this->urlGenerator->generate($this->loginRoute);
    }
}
