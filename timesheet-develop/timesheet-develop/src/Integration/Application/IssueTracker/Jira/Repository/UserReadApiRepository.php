<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Integration\Application\IssueTracker\Jira\Repository;

use Assert\Assertion;
use Jagaad\WitcherApi\Integration\Application\Exception\IntegrationApiRequestException;
use Jagaad\WitcherApi\Integration\Domain\ApiClient\ApiClientInterface;
use Jagaad\WitcherApi\Integration\Domain\IssueTracker\User;
use Jagaad\WitcherApi\Integration\Repository\IssueTracker\UserReadApiInterface;
use Symfony\Component\HttpFoundation\Request;

final class UserReadApiRepository implements UserReadApiInterface
{
    private ApiClientInterface $apiClient;

    public function __construct(ApiClientInterface $apiClient)
    {
        $this->apiClient = $apiClient;
    }

    /**
     * @throws \Throwable
     */
    public function getUserByAccountId(string $accountId): User
    {
        try {
            $user = $this->apiClient->request(
                '/user',
                User::class,
                null,
                [
                    'query' => ['accountId' => $accountId],
                ]
            );

            if (!$user instanceof User) {
                throw new \LogicException('User API returned unexpected value type');
            }

            return $user;
        } catch (\LogicException $exception) {
            throw $exception;
        } catch (\Throwable $exception) {
            throw IntegrationApiRequestException::create($exception->getMessage(), 0, $exception);
        }
    }

    /**
     * @return User[]
     */
    public function getAll(int $startAt = 0, int $maxResults = 20, bool $onlyActive = true): array
    {
        try {
            /** @var User[] $users */
            $users = $this->apiClient->request(
                '/users/search',
                User::class,
                null,
                [
                    'query' => [
                        'query' => '.',
                        'startAt' => $startAt,
                        'maxResults' => $maxResults,
                        'includeInactive' => !$onlyActive,
                    ],
                ],
                Request::METHOD_GET,
                true
            );

            Assertion::allIsInstanceOf($users, User::class, 'API returned incorrect response value');

            return $users;
        } catch (\LogicException $exception) {
            throw $exception;
        } catch (\Throwable $exception) {
            throw IntegrationApiRequestException::create($exception->getMessage(), 0, $exception);
        }
    }
}
