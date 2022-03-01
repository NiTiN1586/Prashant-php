<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Manager;

use Assert\Assertion;
use Jagaad\UserProviderBundle\ApiClient\UserManager\UserManagerApiClient;
use Jagaad\UserProviderBundle\Security\Model\User;
use Jagaad\WitcherApi\Integration\Application\Exception\InvalidApiResponseException;
use Predis\Client;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

final class UserManager implements UserManagerApiInterface
{
    private const EMAIL_REGEX = '/(?=@).+$/';

    private UserManagerApiClient $userManager;
    private Client $sncRedisClient;
    private SerializerInterface $serializer;

    public function __construct(UserManagerApiClient $userManager, Client $sncRedisClient, SerializerInterface $serializer)
    {
        $this->userManager = $userManager;
        $this->sncRedisClient = $sncRedisClient;
        $this->serializer = $serializer;
    }

    /**
     * {@inheritdoc}
     */
    public function findUsersByEmails(array $emails): array
    {
        Assertion::allString($emails, 'emails argument should be array of strings');

        $cacheClient = $this->sncRedisClient;

        $results = [];

        $emails = \array_column(
            \array_map(static fn (string $email): array => ['key' => \preg_filter(self::EMAIL_REGEX, '', $email), 'value' => $email], $emails),
            'value',
            'key'
        );

        $newlyAddedEmails = \array_filter(
            $emails,
            static fn (string $cacheKey): bool => !(bool) $cacheClient->hexists($cacheKey, 'id'),
            \ARRAY_FILTER_USE_KEY
        );

        if (\count($newlyAddedEmails) > 0) {
            $notCachedUsers = $this->userManager->getUserListByEmails(\array_values($newlyAddedEmails));

            foreach ($notCachedUsers as $user) {
                $this->sncRedisClient->hmset(
                    \preg_filter(self::EMAIL_REGEX, '', $user['invitationEmail']),
                    [
                        'id' => (int) $user['id'],
                        'invitationEmail' => $user['invitationEmail'],
                        'active' => (bool) $user['active'],
                    ]
                );
            }
        }

        /** @var array<string, string> $emails */
        foreach ($emails as $cacheKey => $email) {
            $cacheResult = $this->sncRedisClient->hgetall($cacheKey);

            if (\count($cacheResult) > 0) {
                $results[$email] = $cacheResult;
            }
        }

        /** @var array<string, array{id: integer, invitationEmail: string, active: bool}> $results */
        return $results;
    }

    /**
     * {@inheritdoc}
     */
    public function createUser(array $data): int
    {
        /** @var User $user */
        $user = $this->serializer->deserialize(
            $this->userManager->createUser($data),
            User::class,
            'json'
        );

        $this->sncRedisClient->hmset(
            \preg_filter(self::EMAIL_REGEX, '', $user->getInvitationEmail()),
            [
                'id' => $user->getId(),
                'invitationEmail' => $user->getInvitationEmail(),
                'active' => $user->isActive(),
            ]
        );

        return $user->getId();
    }

    /**
     * {@inheritdoc}
     */
    public function updateUser(int $userId, array $data): void
    {
        $response = $this->userManager->updateUser($userId, $data);

        if (Response::HTTP_OK !== $response) {
            throw InvalidApiResponseException::create(\sprintf('User update failed with status code %d', $response));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function deactivateUserById(int $userId): void
    {
        $response = $this->userManager->updateUser($userId, ['active' => false]);

        if (Response::HTTP_OK !== $response) {
            throw InvalidApiResponseException::create(\sprintf('User de-activation failed with status code %d', $response));
        }
    }
}
