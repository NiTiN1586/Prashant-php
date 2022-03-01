<?php

declare(strict_types=1);

namespace App\Tests\Functional\Entity;

use Doctrine\ORM\EntityManager;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;
use Jagaad\WitcherApi\Entity\Role;
use Jagaad\WitcherApi\Repository\WitcherUserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleTest extends AbstractApiResourceTest
{
    use RefreshDatabaseTrait;

    private WitcherUserRepository $witcherUserRepository;
    private EntityManager $entityManager;

    protected function setUp(): void
    {
        parent::setUp();

        $this->witcherUserRepository = static::getContainer()->get(WitcherUserRepository::class);
        $this->entityManager = static::getContainer()->get('doctrine')->getManager();
    }

    public function testAnonymousUserHasNoAccessToViewRolesCollection(): void
    {
        $this->client->request(
            Request::METHOD_GET,
            '/api/roles'
        );

        $this->assertSame(Response::HTTP_UNAUTHORIZED, $this->client->getResponse()->getStatusCode());
    }

    public function testAnonymousUserHasNoAccessToViewRole(): void
    {
        $this->client->request(
            Request::METHOD_GET,
            '/api/roles/1'
        );

        $this->assertSame(Response::HTTP_UNAUTHORIZED, $this->client->getResponse()->getStatusCode());
    }

    /**
     * @dataProvider getCreateRoleData
     *
     * @param array<string, string> $role
     */
    public function testAnonymousUserHasNoAccessToCreateRole(array $role): void
    {
        $this->client->request(
            Request::METHOD_POST,
            '/api/roles',
            [],
            [],
            [self::CONTENT_TYPE_HEADER => self::JSON_CONTENT_TYPE],
            \json_encode($role, \JSON_THROW_ON_ERROR)
        );

        $this->assertSame(Response::HTTP_UNAUTHORIZED, $this->client->getResponse()->getStatusCode());

        $role = $this->entityManager->getRepository(Role::class)->findOneBy(['name' => $role['name']]);

        $this->assertNull($role);
    }

    public function testAnonymousUserHasNoAccessToUpdateRole(): void
    {
        $name = 'Test patch method';

        /** @var Role $role */
        $role = $this->entityManager->getRepository(Role::class)->findOneBy(['id' => 1]);

        $this->assertNotNull($role);
        $this->assertNotEquals($name, $role->getName());

        $updatedRole = ['name' => $name];

        $this->client->request(
            Request::METHOD_PATCH,
            '/api/roles/1',
            [],
            [],
            [
                self::CONTENT_TYPE_HEADER => self::MERGE_PATCH_JSON,
                self::ACCEPT_HEADER => self::JSON_CONTENT_TYPE,
            ],
            \json_encode($updatedRole, \JSON_THROW_ON_ERROR)
        );

        $response = $this->client->getResponse();
        $this->assertSame(Response::HTTP_UNAUTHORIZED, $response->getStatusCode());

        $project = $this->entityManager->getRepository(Role::class)->findOneBy(['name' => $updatedRole['name']]);
        $this->assertNull($project);
    }

    public function testAnonymousUserHasNoAccessToDeleteRole(): void
    {
        $role = $this->entityManager->getRepository(Role::class)->findOneBy(['id' => 1]);

        $this->assertNotNull($role);

        $this->client->request(
            Request::METHOD_DELETE,
            '/api/roles/1'
        );

        $this->assertSame(Response::HTTP_METHOD_NOT_ALLOWED, $this->client->getResponse()->getStatusCode());

        $role = $this->entityManager->getRepository(Role::class)->findOneBy(['id' => 1]);
        $this->assertNotNull($role);
    }

    public function testAdminUserHasAccessToViewRoleCollection(): void
    {
        $adminUser = $this->witcherUserRepository->findWitcherUserByRole(Role::ADMIN);

        self::assertNotNull($adminUser);

        $this->loginUser($this->getSecurityModelUserFromWitcherUser($adminUser))->request(
            Request::METHOD_GET,
            '/api/roles'
        );

        $response = $this->client->getResponse();

        $this->assertSame(Response::HTTP_OK, $response->getStatusCode());

        $this->assertCount(
            3,
            $this->deserialize($response->getContent(), Role::class.'[]')
        );
    }

    /**
     * @dataProvider getCreateRoleData
     *
     * @param array<string, string> $role
     */
    public function testAdminUserHasAccessToCreateRole(array $role): void
    {
        $adminUser = $this->witcherUserRepository->findWitcherUserByRole(Role::ADMIN);

        self::assertNotNull($adminUser);

        $this->loginUser($this->getSecurityModelUserFromWitcherUser($adminUser))->request(
            Request::METHOD_POST,
            '/api/roles',
            [],
            [],
            [self::CONTENT_TYPE_HEADER => self::JSON_CONTENT_TYPE],
            \json_encode($role, \JSON_THROW_ON_ERROR)
        );

        $this->assertSame(Response::HTTP_CREATED, $this->client->getResponse()->getStatusCode());

        $role = $this->entityManager->getRepository(Role::class)->findOneBy(['name' => $role['name']]);

        $this->assertNotNull($role);
    }

    public function testAdminUserHasAccessToRetrieveSingleRole(): void
    {
        $adminUser = $this->witcherUserRepository->findWitcherUserByRole(Role::ADMIN);

        self::assertNotNull($adminUser);

        $this->loginUser($this->getSecurityModelUserFromWitcherUser($adminUser))->request(
            Request::METHOD_GET,
            '/api/roles/1'
        );

        $response = $this->client->getResponse();
        $this->assertSame(Response::HTTP_OK, $response->getStatusCode());

        $this->assertNotNull($this->deserialize($response->getContent(), Role::class));
    }

    public function testAdminUserHasAccessToUpdateRole(): void
    {
        $name = 'Test patch method';

        $adminUser = $this->witcherUserRepository->findWitcherUserByRole(Role::ADMIN);

        self::assertNotNull($adminUser);

        /** @var Role $role */
        $role = $this->entityManager->getRepository(Role::class)->findOneBy(['id' => 1]);

        $this->assertNotNull($role);
        $this->assertNotEquals($name, $role->getName());

        $updatedRole = ['name' => $name];

        $this->loginUser($this->getSecurityModelUserFromWitcherUser($adminUser))->request(
            Request::METHOD_PATCH,
            '/api/roles/1',
            [],
            [],
            [
                self::CONTENT_TYPE_HEADER => self::MERGE_PATCH_JSON,
                self::ACCEPT_HEADER => self::JSON_CONTENT_TYPE,
            ],
            \json_encode($updatedRole, \JSON_THROW_ON_ERROR)
        );

        $response = $this->client->getResponse();
        $this->assertSame(Response::HTTP_OK, $response->getStatusCode());

        $project = $this->entityManager->getRepository(Role::class)->findOneBy(['name' => $updatedRole['name']]);
        $this->assertNotNull($project);
    }

    public function testAdminTriesToDeleteRole(): void
    {
        $admin = $this->witcherUserRepository->findWitcherUserByRole(Role::ADMIN);

        self::assertNotNull($admin);

        /** @var Role $role */
        $role = $this->entityManager->getRepository(Role::class)->findOneBy(['id' => 1]);

        $this->assertNotNull($role);

        $this->loginUser($this->getSecurityModelUserFromWitcherUser($admin))->request(
            Request::METHOD_DELETE,
            '/api/roles/1'
        );

        $this->assertSame(Response::HTTP_METHOD_NOT_ALLOWED, $this->client->getResponse()->getStatusCode());

        $role = $this->entityManager->getRepository(Role::class)->findOneBy(['id' => 1]);
        $this->assertNotNull($role);
    }

    /**
     * @dataProvider getCreateRoleData
     *
     * @param array<string, string> $role
     */
    public function testManagerUserHasAccessToCreateRole(array $role): void
    {
        $manager = $this->witcherUserRepository->findWitcherUserByRole(Role::MANAGER);

        self::assertNotNull($manager);

        $this->loginUser($this->getSecurityModelUserFromWitcherUser($manager))->request(
            Request::METHOD_POST,
            '/api/roles',
            [],
            [],
            [self::CONTENT_TYPE_HEADER => self::JSON_CONTENT_TYPE],
            \json_encode($role, \JSON_THROW_ON_ERROR)
        );

        $this->assertSame(Response::HTTP_CREATED, $this->client->getResponse()->getStatusCode());

        $role = $this->entityManager->getRepository(Role::class)->findOneBy(['name' => $role['name']]);

        $this->assertNotNull($role);
    }

    public function testManagerUserHasAccessToRetrieveSingleRole(): void
    {
        $manager = $this->witcherUserRepository->findWitcherUserByRole(Role::MANAGER);

        self::assertNotNull($manager);

        $this->loginUser($this->getSecurityModelUserFromWitcherUser($manager))->request(
            Request::METHOD_GET,
            '/api/roles/1'
        );

        $response = $this->client->getResponse();
        $this->assertSame(Response::HTTP_OK, $response->getStatusCode());

        $this->assertNotNull($this->deserialize($response->getContent(), Role::class));
    }

    public function testManagerUserHasAccessToUpdateRole(): void
    {
        $name = 'Test patch method';

        $manager = $this->witcherUserRepository->findWitcherUserByRole(Role::MANAGER);

        self::assertNotNull($manager);

        /** @var Role $role */
        $role = $this->entityManager->getRepository(Role::class)->findOneBy(['id' => 1]);

        $this->assertNotNull($role);
        $this->assertNotEquals($name, $role->getName());

        $updatedRole = ['name' => $name];

        $this->loginUser($this->getSecurityModelUserFromWitcherUser($manager))->request(
            Request::METHOD_PATCH,
            '/api/roles/1',
            [],
            [],
            [
                self::CONTENT_TYPE_HEADER => self::MERGE_PATCH_JSON,
                self::ACCEPT_HEADER => self::JSON_CONTENT_TYPE,
            ],
            \json_encode($updatedRole, \JSON_THROW_ON_ERROR)
        );

        $response = $this->client->getResponse();
        $this->assertSame(Response::HTTP_OK, $response->getStatusCode());

        $project = $this->entityManager->getRepository(Role::class)->findOneBy(['name' => $updatedRole['name']]);
        $this->assertNotNull($project);
    }

    public function testManagerTriesToDeleteRole(): void
    {
        $manager = $this->witcherUserRepository->findWitcherUserByRole(Role::MANAGER);

        self::assertNotNull($manager);

        /** @var Role $role */
        $role = $this->entityManager->getRepository(Role::class)->findOneBy(['id' => 1]);

        $this->assertNotNull($role);

        $this->loginUser($this->getSecurityModelUserFromWitcherUser($manager))->request(
            Request::METHOD_DELETE,
            '/api/roles/1'
        );

        $this->assertSame(Response::HTTP_METHOD_NOT_ALLOWED, $this->client->getResponse()->getStatusCode());

        $role = $this->entityManager->getRepository(Role::class)->findOneBy(['id' => 1]);
        $this->assertNotNull($role);
    }

    public function testManagerUserHasAccessToViewRoleCollection(): void
    {
        $manager = $this->witcherUserRepository->findWitcherUserByRole(Role::MANAGER);

        self::assertNotNull($manager);

        $this->loginUser($this->getSecurityModelUserFromWitcherUser($manager))->request(
            Request::METHOD_GET,
            '/api/roles'
        );

        $response = $this->client->getResponse();

        $this->assertSame(Response::HTTP_OK, $response->getStatusCode());

        $this->assertCount(
            3,
            $this->deserialize($response->getContent(), Role::class.'[]')
        );
    }

    /**
     * @dataProvider getCreateRoleData
     *
     * @param array<string, string> $role
     */
    public function testDeveloperUserHasNoAccessToCreateRole(array $role): void
    {
        $developer = $this->witcherUserRepository->findWitcherUserByRole(Role::DEVELOPER);

        self::assertNotNull($developer);

        $this->loginUser($this->getSecurityModelUserFromWitcherUser($developer))->request(
            Request::METHOD_POST,
            '/api/roles',
            [],
            [],
            [self::CONTENT_TYPE_HEADER => self::JSON_CONTENT_TYPE],
            \json_encode($role, \JSON_THROW_ON_ERROR)
        );

        $this->assertSame(Response::HTTP_FORBIDDEN, $this->client->getResponse()->getStatusCode());

        $role = $this->entityManager->getRepository(Role::class)->findOneBy(['name' => $role['name']]);

        $this->assertNull($role);
    }

    public function testDeveloperUserHasAccessToRetrieveSingleRole(): void
    {
        $developer = $this->witcherUserRepository->findWitcherUserByRole(Role::DEVELOPER);

        self::assertNotNull($developer);

        $this->loginUser($this->getSecurityModelUserFromWitcherUser($developer))->request(
            Request::METHOD_GET,
            '/api/roles/1'
        );

        $response = $this->client->getResponse();
        $this->assertSame(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testDeveloperUserHasNoAccessToUpdateRole(): void
    {
        $name = 'Test patch method';

        $developer = $this->witcherUserRepository->findWitcherUserByRole(Role::DEVELOPER);

        self::assertNotNull($developer);

        /** @var Role $role */
        $role = $this->entityManager->getRepository(Role::class)->findOneBy(['id' => 1]);

        $this->assertNotNull($role);
        $this->assertNotEquals($name, $role->getName());

        $updatedRole = ['name' => $name];

        $this->loginUser($this->getSecurityModelUserFromWitcherUser($developer))->request(
            Request::METHOD_PATCH,
            '/api/roles/1',
            [],
            [],
            [
                self::CONTENT_TYPE_HEADER => self::MERGE_PATCH_JSON,
                self::ACCEPT_HEADER => self::JSON_CONTENT_TYPE,
            ],
            \json_encode($updatedRole, \JSON_THROW_ON_ERROR)
        );

        $response = $this->client->getResponse();
        $this->assertSame(Response::HTTP_FORBIDDEN, $response->getStatusCode());
    }

    public function testDeveloperHasNoAccessToDeleteRole(): void
    {
        $developer = $this->witcherUserRepository->findWitcherUserByRole(Role::DEVELOPER);

        self::assertNotNull($developer);

        /** @var Role $role */
        $role = $this->entityManager->getRepository(Role::class)->findOneBy(['id' => 1]);

        $this->assertNotNull($role);

        $this->loginUser($this->getSecurityModelUserFromWitcherUser($developer))->request(
            Request::METHOD_DELETE,
            '/api/roles/1'
        );

        $this->assertSame(Response::HTTP_METHOD_NOT_ALLOWED, $this->client->getResponse()->getStatusCode());
    }

    public function testDeveloperUserHasAccessToViewRoleCollection(): void
    {
        $developer = $this->witcherUserRepository->findWitcherUserByRole(Role::DEVELOPER);

        self::assertNotNull($developer);

        $this->loginUser($this->getSecurityModelUserFromWitcherUser($developer))->request(
            Request::METHOD_GET,
            '/api/roles'
        );

        $response = $this->client->getResponse();

        $this->assertSame(Response::HTTP_OK, $response->getStatusCode());
    }

    /**
     * @return array<string, mixed>
     */
    public function getCreateRoleData(): array
    {
        return [
            [
                [
                    'name' => 'Sample role',
                    'description' => 'Sample role',
                ],
            ],
        ];
    }
}
