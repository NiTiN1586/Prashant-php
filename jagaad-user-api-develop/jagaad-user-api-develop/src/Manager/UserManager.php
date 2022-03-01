<?php

declare(strict_types=1);

namespace Jagaad\UserApi\Manager;

use Doctrine\ORM\EntityManagerInterface;
use Google_Service_Oauth2_Userinfo as GoogleAccountDetails;
use Jagaad\UserApi\Entity\GoogleAccount;
use Jagaad\UserApi\Entity\User;
use Jagaad\UserApi\Enum\ContextGroup;
use Jagaad\UserApi\Exception\Authentication\IncompatibleEmailOwnerException;
use Jagaad\UserApi\Exception\Authentication\MissingUserEmailException;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Serializer;

class UserManager
{
    private EntityManagerInterface $entityManager;

    private GoogleAccountManager $googleAccountManager;

    /** @var Serializer|NormalizerInterface */
    private $serializer;

    public function __construct(
        EntityManagerInterface $entityManager,
        GoogleAccountManager $googleAccountManager,
        NormalizerInterface $serializer
    ) {
        $this->entityManager = $entityManager;
        $this->googleAccountManager = $googleAccountManager;
        $this->serializer = $serializer;
    }

    public function getUserByGoogleAccountDetails(GoogleAccountDetails $googleAccountDetails): User
    {
        $googleAccountEmail = $googleAccountDetails->getEmail();

        if (!$googleAccountEmail) {
            throw MissingUserEmailException::create();
        }

        $user = $this->entityManager->getRepository(User::class)->findOneBy([
            'invitationEmail' => $googleAccountEmail,
        ]);

        if (!$user instanceof User) {
            throw MissingUserEmailException::create('User with given email was not found');
        }

        return $user;
    }

    public function updatedUserWithGoogleAccount(User $user, GoogleAccountDetails $googleAccountDetails): void
    {
        $existingGoogleAccount = $this->googleAccountManager->getGoogleAccountByEmail(
            $googleAccountDetails->getEmail()
        );

        if (
            $existingGoogleAccount instanceof GoogleAccount
            && $existingGoogleAccount->getUser()->getId() !== $user->getId()
        ) {
            throw IncompatibleEmailOwnerException::create(sprintf('Provided google account (%s) belongs to a different user (%s).', $existingGoogleAccount->getUser()->getInvitationEmail(), $user->getInvitationEmail()));
        }

        $googleAccount = $this->googleAccountManager->updateGoogleAccountFromGoogleAccountDetails(
            $existingGoogleAccount ?? new GoogleAccount(),
            $googleAccountDetails
        );
        $user->setLastLogin(new \DateTime());
        $googleAccount->setUser($user);
        $this->entityManager->persist($googleAccount);
        $this->entityManager->flush();
    }

    /**
     * @return array|\ArrayObject|bool|\Countable|float|int|mixed|string|\Traversable|null
     *
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function normalizeUser(User $user)
    {
        return $this->serializer->normalize($user, null, [
            ContextGroup::USER_READ,
        ]);
    }
}
