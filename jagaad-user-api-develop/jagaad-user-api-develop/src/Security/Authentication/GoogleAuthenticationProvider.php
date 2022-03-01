<?php

declare(strict_types=1);

namespace Jagaad\UserApi\Security\Authentication;

use Google\Client as GoogleClient;
use Google_Service_Oauth2 as Oauth2GoogleService;
use Google_Service_Oauth2_Userinfo as GoogleAccountDetails;
use Jagaad\UserApi\Exception\Authentication\InvalidGoogleAuthenticationCodeException;
use Jagaad\UserApi\Security\Authentication\Provider\GoogleClientProvider;

class GoogleAuthenticationProvider
{
    private GoogleClient $googleClient;

    private Oauth2GoogleService $oauth2GoogleService;

    public function __construct(GoogleClientProvider $googleClientProvider)
    {
        $this->googleClient = $googleClientProvider->getGoogleClient();
        $this->oauth2GoogleService = new Oauth2GoogleService($this->googleClient);
    }

    public function getGoogleAuthenticationLink(string $callbackUrl): string
    {
        $this->googleClient->setRedirectUri($callbackUrl);

        return $this->googleClient->createAuthUrl();
    }

    public function getGoogleAccountDetails(string $authenticationCode, string $redirectUrl): GoogleAccountDetails
    {
        $this->googleClient->setRedirectUri($redirectUrl);
        $accessTokenData = $this->googleClient->fetchAccessTokenWithAuthCode($authenticationCode);

        if (!isset($accessTokenData['access_token'])) {
            throw InvalidGoogleAuthenticationCodeException::create(null);
        }

        $this->googleClient->setAccessToken($accessTokenData);

        return $this->oauth2GoogleService->userinfo->get();
    }
}
