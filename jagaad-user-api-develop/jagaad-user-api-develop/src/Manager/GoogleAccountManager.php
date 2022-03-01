<?php

declare(strict_types=1);

namespace Jagaad\UserApi\Manager;

use Doctrine\ORM\EntityManagerInterface;
use Google_Service_Oauth2_Userinfo as GoogleAccountDetails;
use Jagaad\UserApi\Entity\GoogleAccount;

class GoogleAccountManager
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getGoogleAccountByEmail(string $email): ?GoogleAccount
    {
        return $this->entityManager->getRepository(GoogleAccount::class)->findOneBy([
            'email' => $email,
        ]);
    }

    public function updateGoogleAccountFromGoogleAccountDetails(
        GoogleAccount $googleAccount,
        GoogleAccountDetails $googleAccountDetails
    ): GoogleAccount {
        return $googleAccount
            ->setEmail($googleAccountDetails->getEmail())
            ->setFirstName($googleAccountDetails->getGivenName())
            ->setLastName($googleAccountDetails->getFamilyName())
            ->setGoogleAccountId($googleAccountDetails->getId())
            ->setAvatarUrl($googleAccountDetails->getPicture());
    }
}
