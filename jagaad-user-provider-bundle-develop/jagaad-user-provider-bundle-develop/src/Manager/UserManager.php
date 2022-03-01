<?php

declare(strict_types=1);

namespace Jagaad\UserProviderBundle\Manager;

use Jagaad\UserProviderBundle\ApiClient\UserManager\UserManagerApiClient;
use Jagaad\UserProviderBundle\Exception\UserProviderException;
use Jagaad\UserProviderBundle\Security\Model\User;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

class UserManager
{
    private UserManagerApiClient $userManagerApiClient;
    
    private Serializer $serializer;

    public function __construct(
        UserManagerApiClient $userManagerApiClient, 
        SerializerInterface $serializer
    ) {
        $this->userManagerApiClient = $userManagerApiClient;  
        $this->serializer = $serializer;
    }
    
    public function getUserById(int $userId): ?User
    {
        try {
            return $this->serializer->denormalize(
                $this->userManagerApiClient->getUserDataById($userId),
                User::class
            );
        } catch (\Exception $exception) {
            return null;
        }
    }

    /**
     * @param string[]
     *
     * @return User[]
    */
    public function getUserListByEmails(array $emails): array
    {
        try {
            return $this->serializer->denormalize(
                $this->userManagerApiClient->getUserListByEmails($emails),
                User::class . '[]'
            );
        } catch (\Exception $exception) {
            return [];
        }
    }

    /**
     * @param array<string, mixed> $data
    */
    public function getOrCreateUserByEmail(string $email, array $data): User
    {
        $users = $this->serializer->denormalize(
            $this->userManagerApiClient->getUserListByEmails([$email]),
            User::class . '[]'
        );

        if (\count($users) > 0) {
            return $users[0];
        }

        $data['invitationEmail'] = $email;

        return $this->serializer->deserialize(
            $this->userManagerApiClient->createUser($data),
            User::class,
            'json'
        );
    }

    /**
     * @param array<string, mixed> $data
    */
    public function updateUser(int $userId, array $data): int
    {
        return $this->userManagerApiClient->updateUser($userId, $data);
    }

    /**
     * @param int[]
     *
     * @return User[]
     */
    public function getUserListByIds(array $userIds): array
    {
        try {
            return $this->serializer->denormalize(
                $this->userManagerApiClient->getUserListByIds($userIds),
                User::class . '[]'
            );
        } catch (\Exception $exception) {
            return [];
        }
    }
}
