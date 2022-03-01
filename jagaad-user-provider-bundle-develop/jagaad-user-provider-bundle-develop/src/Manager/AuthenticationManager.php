<?php

declare(strict_types=1);

namespace Jagaad\UserProviderBundle\Manager;

use Jagaad\UserProviderBundle\ApiClient\UserManager\UserManagerApiClient;
use Jagaad\UserProviderBundle\Security\Model\User;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

class AuthenticationManager
{
    private UserManagerApiClient $userManagerApiClient;
    
    private Serializer $serializer;

    private RouterInterface $router;
    
    private string $googleAuthenticationLoginRoute;

    public function __construct(
        string $googleAuthenticationLoginRoute,
        UserManagerApiClient $userManagerApiClient, 
        SerializerInterface $serializer,
        RouterInterface $router
    ) {
        $this->userManagerApiClient = $userManagerApiClient;  
        $this->serializer = $serializer;
        $this->router = $router;
        $this->googleAuthenticationLoginRoute = $googleAuthenticationLoginRoute;
    }
    
    public function getGoogleAuthenticationUrl(?string $postLoginRedirectUrl = null): string
    {
        return $this->userManagerApiClient->getGoogleAuthenticationUrl(
            $this->getGoogleAuthenticationCallbackUrl($postLoginRedirectUrl)
        );
    }

    public function getAuthenticatedUserByAuthenticationCode(
        string $googleAuthenticationCode,
        ?string $postLoginRedirectUrl = null
    ): User {
        $userData = $this
            ->userManagerApiClient
            ->authenticateUserWithGoogleAuthenticationCode(
                $googleAuthenticationCode,
                $this->getGoogleAuthenticationCallbackUrl($postLoginRedirectUrl)
            );
        
        return $this->serializer->denormalize($userData, User::class);
    }


    private function getGoogleAuthenticationCallbackUrl(?string $postLoginRedirectUrl = null): string
    {
        return $this->router->generate(
            $this->googleAuthenticationLoginRoute,
            [
                'postLoginRedirectUrl' => $postLoginRedirectUrl,
            ],
            UrlGeneratorInterface::ABSOLUTE_URL
        );
    }
}
