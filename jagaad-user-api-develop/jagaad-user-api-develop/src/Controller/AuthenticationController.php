<?php

declare(strict_types=1);

namespace Jagaad\UserApi\Controller;

use Jagaad\UserApi\Exception\Authentication\IncompatibleEmailOwnerException;
use Jagaad\UserApi\Exception\Authentication\InvalidGoogleAuthenticationCodeException;
use Jagaad\UserApi\Exception\Authentication\MissingCallbackUrlException;
use Jagaad\UserApi\Exception\Authentication\MissingGoogleAuthenticationCodeException;
use Jagaad\UserApi\Exception\Authentication\MissingUserEmailException;
use Jagaad\UserApi\Manager\UserManager;
use Jagaad\UserApi\Security\Authentication\GoogleAuthenticationProvider;
use Jagaad\UserApi\Traits\Response\JsonResponseTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/auth/google", name="auth_google_")
 */
class AuthenticationController
{
    use JsonResponseTrait;

    private GoogleAuthenticationProvider $googleAuthenticationProvider;

    private UserManager $userManager;

    public function __construct(
        GoogleAuthenticationProvider $googleAuthenticationProvider,
        UserManager $userManager
    ) {
        $this->googleAuthenticationProvider = $googleAuthenticationProvider;
        $this->userManager = $userManager;
    }

    /**
     * @Route("/auth-url", name="auth_url", methods={Request::METHOD_GET})
     */
    public function getAuthenticationUrl(Request $request): Response
    {
        try {
            $callbackUrl = $request->query->get('callbackUrl');

            if (null === $callbackUrl) {
                throw MissingCallbackUrlException::create();
            }

            return self::createSuccessfulJsonResponse([
                'authenticationUrl' => $this->googleAuthenticationProvider->getGoogleAuthenticationLink($callbackUrl),
            ]);
        } catch (\Exception $exception) {
            return self::createErrorJsonResponse($exception->getMessage());
        }
    }

    /**
     * @Route("/authenticate-user", name="authnenticate_user", methods={Request::METHOD_POST})
     */
    public function authenticateGoogleUser(Request $request): Response
    {
        try {
            /** @var string|null $authenticationCode */
            $authenticationCode = $request->request->get('googleAuthenticationCode');

            if (null === $authenticationCode) {
                throw MissingGoogleAuthenticationCodeException::create();
            }

            $redirectUrl = $request->request->get('redirectUrl');

            if (null === $redirectUrl) {
                throw MissingCallbackUrlException::create();
            }

            $googleAccountDetails = $this->googleAuthenticationProvider->getGoogleAccountDetails(
                $authenticationCode,
                $redirectUrl
            );
            $user = $this->userManager->getUserByGoogleAccountDetails($googleAccountDetails);
            $this->userManager->updatedUserWithGoogleAccount($user, $googleAccountDetails);

            return self::createSuccessfulJsonResponse(['user' => $this->userManager->normalizeUser($user)]);
        } catch (MissingGoogleAuthenticationCodeException $exception) {
            return self::createErrorJsonResponse($exception->getMessage(), [], Response::HTTP_BAD_REQUEST);
        } catch (
            InvalidGoogleAuthenticationCodeException
            | MissingUserEmailException
            | MissingCallbackUrlException
            | IncompatibleEmailOwnerException $exception
        ) {
            return self::createErrorJsonResponse($exception->getMessage(), [], Response::HTTP_FORBIDDEN);
        } catch (\Exception $exception) {
            return self::createErrorJsonResponse($exception->getMessage());
        }
    }
}
