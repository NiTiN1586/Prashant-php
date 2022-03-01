<?php

declare(strict_types=1);

namespace Jagaad\UserProviderBundle\Controller;

use Jagaad\UserProviderBundle\DataClass\AuthenticationResult\FailedAuthenticationResult;
use Jagaad\UserProviderBundle\DataClass\AuthenticationResult\SuccessfulAuthenticationResult;
use Jagaad\UserProviderBundle\Exception\Authentication\UserProviderAuthenticationException;
use Jagaad\UserProviderBundle\Manager\AuthenticationManager;
use Jagaad\UserProviderBundle\Manager\AuthenticationResultManager;
use Jagaad\UserProviderBundle\Security\Model\User;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Security;

class UserProviderAuthenticationController
{
    private string $postLoginRedirectRoute;
    
    private string $loginRoute;
    
    private RouterInterface $router;

    private AuthenticationResultManager $authenticationResultManager;

    private AuthenticationManager $authenticationManager;

    private Security $security;

    public function __construct(
        string $postLoginRedirectRoute,
        string $loginRoute,
        RouterInterface $router,
        AuthenticationResultManager $authenticationResultManager,
        AuthenticationManager $authenticationManager,
        Security $security
    ) {
        $this->postLoginRedirectRoute = $postLoginRedirectRoute;
        $this->loginRoute = $loginRoute;
        $this->router = $router;
        $this->authenticationResultManager = $authenticationResultManager;
        $this->authenticationManager = $authenticationManager;
        $this->security = $security;
    }

    public function callback(Request $request): RedirectResponse
    {
        return new RedirectResponse(
            $request->query->get('postLoginRedirectUrl')
                ?? $this->router->generate($this->postLoginRedirectRoute, [], RouterInterface::ABSOLUTE_URL)
        );
    }

    public function logout(): RedirectResponse
    {
        $this->authenticationResultManager->invaildateLatestAuthenticationResult();

        return new RedirectResponse($this->router->generate($this->loginRoute, [], RouterInterface::ABSOLUTE_URL));
    }

    public function googleAuthenticationUrl(Request $request): JsonResponse
    {
        try {
            return new JsonResponse([
                'success' => true,
                'data' => [
                    'google_auth_url' => $this->authenticationManager->getGoogleAuthenticationUrl(
                        $request->query->get('postLoginRedirectUrl')
                    ),
                ],
            ]);
        } catch (\Exception $exception) {
            return new JsonResponse([
                'success' => false,
                'message' => $exception->getMessage(),
            ]);
        }
    }

    public function lastAuthenticationResult(): JsonResponse
    {
        try {
            $lastAuthenticationResult = $this->authenticationResultManager->getLatestAuthenticationResult();

            if (null === $lastAuthenticationResult) {
                return new JsonResponse([
                    'message' => 'No authentication result details found',
                ], Response::HTTP_NOT_FOUND);
            }

            if ($lastAuthenticationResult instanceof SuccessfulAuthenticationResult) {
                $authenticatedUser = $this->security->getUser();

                if (!$authenticatedUser instanceof User) {
                    throw UserProviderAuthenticationException::create('Failed to fetch authenticated user');
                }

                return new JsonResponse([
                    'success' => true,
                    'message' => sprintf('User successfully authenticated as %s', $authenticatedUser->getUsername()),
                ]);
            }

            if ($lastAuthenticationResult instanceof FailedAuthenticationResult) {
                return new JsonResponse([
                    'success' => false,
                    'message' => $lastAuthenticationResult->getAuthenticationExceptionMessage(),
                ]);
            }
        } catch (\Exception $exception) {
            return new JsonResponse([
                'success' => false,
                'message' => $exception->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
