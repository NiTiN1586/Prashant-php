<?php

declare(strict_types=1);

namespace App\Tests\Functional\Entity;

use Jagaad\UserProviderBundle\DataClass\AuthenticationResult\SuccessfulAuthenticationResult;
use Jagaad\UserProviderBundle\Enum\SessionKey;
use Jagaad\UserProviderBundle\Security\Model\User;
use Jagaad\WitcherApi\Entity\Permission;
use Jagaad\WitcherApi\Entity\WitcherUser;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Serializer\SerializerInterface;

abstract class AbstractApiResourceTest extends WebTestCase
{
    private const FIREWALL = 'jagaad_user_provider';
    protected const CONTENT_TYPE_HEADER = 'CONTENT_TYPE';
    protected const JSON_CONTENT_TYPE = 'application/json';
    protected const MERGE_PATCH_JSON = 'application/merge-patch+json';
    protected const ACCEPT_HEADER = 'ACCEPT';

    private SerializerInterface $serializer;
    protected KernelBrowser $client;

    protected function setUp(): void
    {
        $this->client = self::createClient([], ['HTTP_HOST' => $_ENV['API_HOST']]);
        $this->serializer = static::getContainer()->get(SerializerInterface::class);
    }

    protected function loginUser(User $user): KernelBrowser
    {
        $client = $this->client->loginUser($user, self::FIREWALL);
        self::getContainer()->get('session')->set(SessionKey::SESSION_KEY_AUTHENTICATION_RESULT, new SuccessfulAuthenticationResult($user));

        return $client;
    }

    protected function getSecurityModelUserFromWitcherUser(WitcherUser $witcherUser): User
    {
        $user = new User();

        $permissions = \array_map(
            static fn (Permission $permission) => $permission->getHandle(),
            $witcherUser->getRole()->getPermissions()
        );

        $user->setRoles([$witcherUser->getRole()->getHandle()]);
        $user->setPermissions($permissions);
        $user->setId($witcherUser->getUserId());
        $user->setCreatedAt($witcherUser->getCreatedAt());
        $user->setActive(true);
        $user->setInvitationEmail('test.user@jagaad.it');

        return $user;
    }

    /**
     * @return object[]|object
     */
    protected function deserialize(string $data, string $class, array $groups = ['default']): array|object
    {
        return $this->serializer->deserialize($data, $class, 'json', ['groups' => $groups]);
    }
}
