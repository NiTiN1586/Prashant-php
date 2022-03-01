<?php

declare(strict_types=1);

namespace Jagaad\UserApi\Security\Authentication\Provider;

use Google\Client as GoogleClient;

class GoogleClientProvider
{
    private GoogleClient $googleClient;

    public function __construct(GoogleClient $googleClient)
    {
        $this->configureGoogleClient($googleClient);
        $this->googleClient = $googleClient;
    }

    public function getGoogleClient(): GoogleClient
    {
        return $this->googleClient;
    }

    private function configureGoogleClient(GoogleClient $googleClient): void
    {
        $googleClient->setAccessType('offline');
        $googleClient->setScopes([
            \Google_Service_Oauth2::USERINFO_PROFILE,
            \Google_Service_Oauth2::USERINFO_EMAIL,
        ]);
    }
}
