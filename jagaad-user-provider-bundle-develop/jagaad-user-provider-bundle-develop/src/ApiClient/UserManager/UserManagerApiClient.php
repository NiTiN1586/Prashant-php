<?php

declare(strict_types=1);

namespace Jagaad\UserProviderBundle\ApiClient\UserManager;

use Assert\Assertion;
use Jagaad\UserProviderBundle\Enum\ApiEndpoint;
use Jagaad\UserProviderBundle\Exception\Authentication\UserProviderAuthenticationException;
use Jagaad\UserProviderBundle\Exception\UserProviderException;
use Jagaad\UserProviderBundle\HttpRequest\HttpHeader;
use Jagaad\UserProviderBundle\Security\Model\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class UserManagerApiClient
{
    private const CLIENT_API_TOKEN = 'CLIENT-API-TOKEN';
    private const USER_API_EXCEPTION = 'API request failed';

    private string $userApiBaseUrl;
    
    private HttpClientInterface $client;
    private string $clientJagaadUserApiToken;

    public function __construct(
        string $userApiBaseUrl,
        string $clientJagaadUserApiToken,
        HttpClientInterface $client
    ) {
        $this->userApiBaseUrl = $userApiBaseUrl;
        $this->client = $client;
        $this->clientJagaadUserApiToken = $clientJagaadUserApiToken;
    }

    public function getGoogleAuthenticationUrl(string $googleAuthenticationCallbackUrl): string
    {
        $getGoogleAuthUrlResponse = $this->client->request(
            Request::METHOD_GET,
            \sprintf(ApiEndpoint::URL_TEMPLATE_GET_AUTHENTICATION_URL, $this->userApiBaseUrl), [
                'query' => [
                    'callbackUrl' => $googleAuthenticationCallbackUrl,
                ]
            ]
        );

        $getGoogleAuthUrlResponseContent = $getGoogleAuthUrlResponse->toArray(false);
        
        if (Response::HTTP_OK !== $getGoogleAuthUrlResponse->getStatusCode()) {
            throw UserProviderAuthenticationException::create(
                $getGoogleAuthUrlResponseContent['message']
                ?? 'Failed to fetch Google authentication url',
                $getGoogleAuthUrlResponse->getStatusCode()
            );
        }
        
        return $getGoogleAuthUrlResponseContent['data']['authenticationUrl'];
    }
    
    public function authenticateUserWithGoogleAuthenticationCode(string $authenticationCode, string $googleAuthenticationCallbackUrl): array
    {
        $authenticateUserResponse = $this->client->request(
            Request::METHOD_POST,
            \sprintf(ApiEndpoint::URL_TEMPLATE_AUTHENTICATE_USER, $this->userApiBaseUrl), [
                'body' => [
                    'googleAuthenticationCode' => $authenticationCode,
                    'redirectUrl' => $googleAuthenticationCallbackUrl,
                ],
            ]
        );

        $authenticateUserResponseData = $authenticateUserResponse->toArray(false);
        
        if (empty($authenticateUserResponseData) || false === $authenticateUserResponseData['success'] ?? false) {
            throw UserProviderAuthenticationException::create(
                $authenticateUserResponseData['message']
                ?? 'Failed to authenticate user'
            );
        }

        if (!($authenticateUserResponseData['data']['user'] ?? null)) {
            throw UserProviderAuthenticationException::create('Missing user details information');
        }

        return $authenticateUserResponseData['data']['user'];
    }

    /**
     * @return array<int, mixed>
    */
    public function getUserDataById(int $userId): array
    {
        return $this->makeRequest(\sprintf('/%d', $userId));
    }

    /**
     * @param string[]
     *
     * @return array<int, mixed>
     */
    public function getUserListByEmails(array $emails): array
    {
        return $this->makeRequest(
            '?' . \http_build_query($emails, User::SEARCH_FIELD_EMAIL . '[]')
        );
    }

    /**
     * @param array<string, mixed> $data
    */
    public function createUser(array $data): string
    {
        $userDataResponse = $this->client->request(
            Request::METHOD_POST,
            \sprintf(
                ApiEndpoint::BASE_URL_TEMPLATE_POST_USER,
                $this->userApiBaseUrl
            ),
            [
                'json' => $data,
                'headers' => [self::CLIENT_API_TOKEN => $this->clientJagaadUserApiToken],
            ]
        );

        if (Response::HTTP_CREATED !== $userDataResponse->getStatusCode()) {
            throw UserProviderException::create(
                self::USER_API_EXCEPTION,
                $userDataResponse->getStatusCode()
            );
        }

        return $userDataResponse->getContent(false);
    }

    /**
     * @param array<string, mixed> $data
    */
    public function updateUser(int $id, array $data): int
    {
        Assertion::greaterThan($id, 0, 'User Id parameter is incorrect');

        $userDataResponse = $this->client->request(
            Request::METHOD_PATCH,
            \sprintf(
                ApiEndpoint::URL_TEMPLATE_GET_USER_BY_ID,
                $this->userApiBaseUrl,
                '/' . $id
            ),
            [
                'json' => $data,
                'headers' => [
                    self::CLIENT_API_TOKEN => $this->clientJagaadUserApiToken,
                    HttpHeader::CONTENT_TYPE_HEADER => HttpHeader::PATCH_JSON_CONTENT_TYPE,
                ],
            ]
        );

        return $userDataResponse->getStatusCode();
    }

    /**
     * @param int[]
     *
     * @return array<int, mixed>
     */
    public function getUserListByIds(array $userIds): array
    {
        return $this->makeRequest(
            '?' . \http_build_query($userIds, User::SEARCH_FIELD_USER_ID . '[]')
        );
    }

    /**
     * @param string $query
     * @return array<int, mixed>
    */
    private function makeRequest(string $query): array
    {
        if ('' === \trim($query)) {
            throw new \InvalidArgumentException('Incorrect query passed.');
        }

        $userDataResponse = $this->client->request(
            Request::METHOD_GET,
            \sprintf(
                ApiEndpoint::URL_TEMPLATE_GET_USER_BY_ID,
                $this->userApiBaseUrl,
                $query
            ),
            [
                'headers' => [self::CLIENT_API_TOKEN => $this->clientJagaadUserApiToken],
            ]
        );

        if (Response::HTTP_OK !== $userDataResponse->getStatusCode()) {
            throw UserProviderException::create(
                self::USER_API_EXCEPTION,
                $userDataResponse->getStatusCode()
            );
        }

        return $userDataResponse->toArray(false);
    }
}
