<?php

declare(strict_types=1);

namespace App\Tests\Functional\Entity;

use Doctrine\ORM\EntityManager;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;
use Jagaad\WitcherApi\Entity\Permission;
use Jagaad\WitcherApi\Entity\Role;
use Jagaad\WitcherApi\Repository\WitcherUserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PermissionTest extends AbstractApiResourceTest
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

    public function testAnonymousUserHasNoAccessToViewPermissionCollection(): void
    {
        $this->client->request(
            Request::METHOD_GET,
            '/api/permissions'
        );

        $this->assertSame(Response::HTTP_UNAUTHORIZED, $this->client->getResponse()->getStatusCode());
    }

    public function testAnonymousUserHasNoAccessToViewPermission(): void
    {
        $this->client->request(
            Request::METHOD_GET,
            '/api/permissions/1'
        );

        $this->assertSame(Response::HTTP_UNAUTHORIZED, $this->client->getResponse()->getStatusCode());
    }

    /**
     * @dataProvider getCreatePermissionData
     *
     * @param array<string, string> $permission
     */
    public function testAnonymousUserHasNoAccessToCreatePermission(array $permission): void
    {
        $this->client->request(
            Request::METHOD_POST,
            '/api/permissions',
            [],
            [],
            [self::CONTENT_TYPE_HEADER => self::JSON_CONTENT_TYPE],
            \json_encode($permission, \JSON_THROW_ON_ERROR)
        );

        $this->assertSame(Response::HTTP_METHOD_NOT_ALLOWED, $this->client->getResponse()->getStatusCode());

        $role = $this->entityManager->getRepository(Permission::class)->findOneBy(['name' => $permission['name']]);

        $this->assertNull($role);
    }

    public function testAnonymousUserHasNoAccessToUpdatePermission(): void
    {
        $name = 'Test patch method';

        /** @var Permission $permission */
        $permission = $this->entityManager->getRepository(Permission::class)->findOneBy(['id' => 1]);

        $this->assertNotNull($permission);
        $this->assertNotEquals($name, $permission->getName());

        $updatedPermission = ['name' => $name];

        $this->client->request(
            Request::METHOD_PATCH,
            '/api/permissions/1',
            [],
            [],
            [
                self::CONTENT_TYPE_HEADER => self::MERGE_PATCH_JSON,
                self::ACCEPT_HEADER => self::JSON_CONTENT_TYPE,
            ],
            \json_encode($updatedPermission, \JSON_THROW_ON_ERROR)
        );

        $response = $this->client->getResponse();
        $this->assertSame(Response::HTTP_METHOD_NOT_ALLOWED, $response->getStatusCode());

        $project = $this->entityManager->getRepository(Role::class)->findOneBy(['name' => $updatedPermission['name']]);
        $this->assertNull($project);
    }

    public function testAnonymousUserHasNoAccessToDeletePermission(): void
    {
        $permission = $this->entityManager->getRepository(Permission::class)->findOneBy(['id' => 1]);

        $this->assertNotNull($permission);

        $this->client->request(
            Request::METHOD_DELETE,
            '/api/permissions/1'
        );

        $this->assertSame(Response::HTTP_METHOD_NOT_ALLOWED, $this->client->getResponse()->getStatusCode());

        $permission = $this->entityManager->getRepository(Permission::class)->findOneBy(['id' => 1]);
        $this->assertNotNull($permission);
    }

    public function testAdminUserHasAccessToViewPermissionCollection(): void
    {
        $adminUser = $this->witcherUserRepository->findWitcherUserByRole(Role::ADMIN);

        self::assertNotNull($adminUser);

        $this->loginUser($this->getSecurityModelUserFromWitcherUser($adminUser))->request(
            Request::METHOD_GET,
            '/api/permissions'
        );

        $response = $this->client->getResponse();

        $this->assertSame(Response::HTTP_OK, $response->getStatusCode());

        $this->assertCount(
            16,
            $this->deserialize($response->getContent(), Permission::class.'[]')
        );
    }

    /**
     * @dataProvider getCreatePermissionData
     *
     * @param array<string, string> $permission
     */
    public function testAdminUserHasNoAvailabilityToCreatePermission(array $permission): void
    {
        $adminUser = $this->witcherUserRepository->findWitcherUserByRole(Role::ADMIN);

        self::assertNotNull($adminUser);

        $this->loginUser($this->getSecurityModelUserFromWitcherUser($adminUser))->request(
            Request::METHOD_POST,
            '/api/permissions',
            [],
            [],
            [self::CONTENT_TYPE_HEADER => self::JSON_CONTENT_TYPE],
            \json_encode($permission, \JSON_THROW_ON_ERROR)
        );

        $this->assertSame(Response::HTTP_METHOD_NOT_ALLOWED, $this->client->getResponse()->getStatusCode());

        $permission = $this->entityManager->getRepository(Role::class)->findOneBy(['name' => $permission['name']]);

        $this->assertNull($permission);
    }

    public function testAdminUserHasAccessToRetrieveSinglePermission(): void
    {
        $adminUser = $this->witcherUserRepository->findWitcherUserByRole(Role::ADMIN);

        self::assertNotNull($adminUser);

        $this->loginUser($this->getSecurityModelUserFromWitcherUser($adminUser))->request(
            Request::METHOD_GET,
            '/api/permissions/1'
        );

        $response = $this->client->getResponse();
        $this->assertSame(Response::HTTP_OK, $response->getStatusCode());

        $this->assertNotNull($this->deserialize($response->getContent(), Permission::class));
    }

    public function testAdminUserHasNoAvailabilityToUpdatePermission(): void
    {
        $name = 'Test patch method';

        $adminUser = $this->witcherUserRepository->findWitcherUserByRole(Role::ADMIN);

        self::assertNotNull($adminUser);

        /** @var Permission $permission */
        $permission = $this->entityManager->getRepository(Permission::class)->findOneBy(['id' => 1]);

        $this->assertNotNull($permission);

        $updatedPermission = ['name' => $name];

        $this->loginUser($this->getSecurityModelUserFromWitcherUser($adminUser))->request(
            Request::METHOD_PATCH,
            '/api/permissions/1',
            [],
            [],
            [
                self::CONTENT_TYPE_HEADER => self::MERGE_PATCH_JSON,
                self::ACCEPT_HEADER => self::JSON_CONTENT_TYPE,
            ],
            \json_encode($updatedPermission, \JSON_THROW_ON_ERROR)
        );

        $response = $this->client->getResponse();
        $this->assertSame(Response::HTTP_METHOD_NOT_ALLOWED, $response->getStatusCode());

        $permission = $this->entityManager->getRepository(Role::class)->findOneBy(['name' => $updatedPermission['name']]);
        $this->assertNull($permission);
    }

    public function testAdminHasNoAvailabilityToDeletePermission(): void
    {
        $admin = $this->witcherUserRepository->findWitcherUserByRole(Role::ADMIN);

        self::assertNotNull($admin);

        /** @var Permission $permission */
        $permission = $this->entityManager->getRepository(Permission::class)->findOneBy(['id' => 1]);

        $this->assertNotNull($permission);

        $this->loginUser($this->getSecurityModelUserFromWitcherUser($admin))->request(
            Request::METHOD_DELETE,
            '/api/permissions/1'
        );

        $this->assertSame(Response::HTTP_METHOD_NOT_ALLOWED, $this->client->getResponse()->getStatusCode());

        $permission = $this->entityManager->getRepository(Permission::class)->findOneBy(['id' => 1]);
        $this->assertNotNull($permission);
    }

    public function testManagerUserHasAccessToViewPermissionCollection(): void
    {
        $manager = $this->witcherUserRepository->findWitcherUserByRole(Role::MANAGER);

        self::assertNotNull($manager);

        $this->loginUser($this->getSecurityModelUserFromWitcherUser($manager))->request(
            Request::METHOD_GET,
            '/api/permissions'
        );

        $response = $this->client->getResponse();

        $this->assertSame(Response::HTTP_OK, $response->getStatusCode());

        $this->assertCount(
            16,
            $this->deserialize($response->getContent(), Permission::class.'[]')
        );
    }

    /**
     * @dataProvider getCreatePermissionData
     *
     * @param array<string, string> $permission
     */
    public function testManagerUserHasNoAvailabilityToCreatePermission(array $permission): void
    {
        $manager = $this->witcherUserRepository->findWitcherUserByRole(Role::MANAGER);

        self::assertNotNull($manager);

        $this->loginUser($this->getSecurityModelUserFromWitcherUser($manager))->request(
            Request::METHOD_POST,
            '/api/permissions',
            [],
            [],
            [self::CONTENT_TYPE_HEADER => self::JSON_CONTENT_TYPE],
            \json_encode($permission, \JSON_THROW_ON_ERROR)
        );

        $this->assertSame(Response::HTTP_METHOD_NOT_ALLOWED, $this->client->getResponse()->getStatusCode());

        $permission = $this->entityManager->getRepository(Role::class)->findOneBy(['name' => $permission['name']]);

        $this->assertNull($permission);
    }

    public function testManagerUserHasAccessToRetrieveSinglePermission(): void
    {
        $manager = $this->witcherUserRepository->findWitcherUserByRole(Role::MANAGER);

        self::assertNotNull($manager);

        $this->loginUser($this->getSecurityModelUserFromWitcherUser($manager))->request(
            Request::METHOD_GET,
            '/api/permissions/1'
        );

        $response = $this->client->getResponse();
        $this->assertSame(Response::HTTP_OK, $response->getStatusCode());

        $this->assertNotNull($this->deserialize($response->getContent(), Permission::class));
    }

    public function testManagerUserHasNoAvailabilityToUpdatePermission(): void
    {
        $name = 'Test patch method';

        $manager = $this->witcherUserRepository->findWitcherUserByRole(Role::MANAGER);

        self::assertNotNull($manager);

        /** @var Permission $permission */
        $permission = $this->entityManager->getRepository(Permission::class)->findOneBy(['id' => 1]);

        $this->assertNotNull($permission);

        $updatedPermission = ['name' => $name];

        $this->loginUser($this->getSecurityModelUserFromWitcherUser($manager))->request(
            Request::METHOD_PATCH,
            '/api/permissions/1',
            [],
            [],
            [
                self::CONTENT_TYPE_HEADER => self::MERGE_PATCH_JSON,
                self::ACCEPT_HEADER => self::JSON_CONTENT_TYPE,
            ],
            \json_encode($updatedPermission, \JSON_THROW_ON_ERROR)
        );

        $response = $this->client->getResponse();
        $this->assertSame(Response::HTTP_METHOD_NOT_ALLOWED, $response->getStatusCode());

        $permission = $this->entityManager->getRepository(Role::class)->findOneBy(['name' => $updatedPermission['name']]);
        $this->assertNull($permission);
    }

    public function testManagerHasNoAvailabilityToDeletePermission(): void
    {
        $manager = $this->witcherUserRepository->findWitcherUserByRole(Role::MANAGER);

        self::assertNotNull($manager);

        /** @var Permission $permission */
        $permission = $this->entityManager->getRepository(Permission::class)->findOneBy(['id' => 1]);

        $this->assertNotNull($permission);

        $this->loginUser($this->getSecurityModelUserFromWitcherUser($manager))->request(
            Request::METHOD_DELETE,
            '/api/permissions/1'
        );

        $this->assertSame(Response::HTTP_METHOD_NOT_ALLOWED, $this->client->getResponse()->getStatusCode());

        $permission = $this->entityManager->getRepository(Permission::class)->findOneBy(['id' => 1]);
        $this->assertNotNull($permission);
    }

    public function testDeveloperUserHasAccessToViewPermissionCollection(): void
    {
        $developer = $this->witcherUserRepository->findWitcherUserByRole(Role::DEVELOPER);

        self::assertNotNull($developer);

        $this->loginUser($this->getSecurityModelUserFromWitcherUser($developer))->request(
            Request::METHOD_GET,
            '/api/permissions'
        );

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    /**
     * @dataProvider getCreatePermissionData
     *
     * @param array<string, string> $permission
     */
    public function testDeveloperUserHasNoAvailabilityToCreatePermission(array $permission): void
    {
        $developer = $this->witcherUserRepository->findWitcherUserByRole(Role::DEVELOPER);

        self::assertNotNull($developer);

        $this->loginUser($this->getSecurityModelUserFromWitcherUser($developer))->request(
            Request::METHOD_POST,
            '/api/permissions',
            [],
            [],
            [self::CONTENT_TYPE_HEADER => self::JSON_CONTENT_TYPE],
            \json_encode($permission, \JSON_THROW_ON_ERROR)
        );

        $this->assertSame(Response::HTTP_METHOD_NOT_ALLOWED, $this->client->getResponse()->getStatusCode());

        $permission = $this->entityManager->getRepository(Role::class)->findOneBy(['name' => $permission['name']]);

        $this->assertNull($permission);
    }

    public function testDeveloperUserHasAccessToRetrieveSinglePermission(): void
    {
        $developer = $this->witcherUserRepository->findWitcherUserByRole(Role::DEVELOPER);

        self::assertNotNull($developer);

        $this->loginUser($this->getSecurityModelUserFromWitcherUser($developer))->request(
            Request::METHOD_GET,
            '/api/permissions/1'
        );

        $response = $this->client->getResponse();
        $this->assertSame(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testDeveloperUserHasNoAvailabilityToUpdatePermission(): void
    {
        $name = 'Test patch method';

        $developer = $this->witcherUserRepository->findWitcherUserByRole(Role::DEVELOPER);

        self::assertNotNull($developer);

        /** @var Permission $permission */
        $permission = $this->entityManager->getRepository(Permission::class)->findOneBy(['id' => 1]);

        $this->assertNotNull($permission);

        $updatedPermission = ['name' => $name];

        $this->loginUser($this->getSecurityModelUserFromWitcherUser($developer))->request(
            Request::METHOD_PATCH,
            '/api/permissions/1',
            [],
            [],
            [
                self::CONTENT_TYPE_HEADER => self::MERGE_PATCH_JSON,
                self::ACCEPT_HEADER => self::JSON_CONTENT_TYPE,
            ],
            \json_encode($updatedPermission, \JSON_THROW_ON_ERROR)
        );

        $response = $this->client->getResponse();
        $this->assertSame(Response::HTTP_METHOD_NOT_ALLOWED, $response->getStatusCode());

        $permission = $this->entityManager->getRepository(Role::class)->findOneBy(['name' => $updatedPermission['name']]);
        $this->assertNull($permission);
    }

    public function testDeveloperHasNoAvailabilityToDeletePermission(): void
    {
        $developer = $this->witcherUserRepository->findWitcherUserByRole(Role::DEVELOPER);

        self::assertNotNull($developer);

        /** @var Permission $permission */
        $permission = $this->entityManager->getRepository(Permission::class)->findOneBy(['id' => 1]);

        $this->assertNotNull($permission);

        $this->loginUser($this->getSecurityModelUserFromWitcherUser($developer))->request(
            Request::METHOD_DELETE,
            '/api/permissions/1'
        );

        $this->assertSame(Response::HTTP_METHOD_NOT_ALLOWED, $this->client->getResponse()->getStatusCode());

        $permission = $this->entityManager->getRepository(Permission::class)->findOneBy(['id' => 1]);
        $this->assertNotNull($permission);
    }

    /**
     * @return array<string, mixed>
     */
    public function getCreatePermissionData(): array
    {
        return [
            [
                [
                    'handle' => 'Sample role',
                    'name' => 'Sample role',
                    'description' => 'Sample role',
                ],
            ],
        ];
    }
}
