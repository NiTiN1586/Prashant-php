<?php

declare(strict_types=1);

namespace App\Tests\Functional\Entity;

use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;
use Jagaad\WitcherApi\Entity\Role;
use Jagaad\WitcherApi\Entity\WitcherProject;
use Jagaad\WitcherApi\Repository\WitcherProjectRepository;
use Jagaad\WitcherApi\Repository\WitcherUserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class WitcherProjectTest extends AbstractApiResourceTest
{
    use RefreshDatabaseTrait;

    private WitcherUserRepository $witcherUserRepository;
    private WitcherProjectRepository $witcherProjectRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->witcherUserRepository = static::getContainer()->get(WitcherUserRepository::class);
        $this->witcherProjectRepository = static::getContainer()->get(WitcherProjectRepository::class);
    }

    public function testAnonymousUserProjectGetCollectionFails(): void
    {
        $this->client->request(
            Request::METHOD_GET,
            '/api/witcher_projects'
        );

        $this->assertSame(Response::HTTP_UNAUTHORIZED, $this->client->getResponse()->getStatusCode());
    }

    public function testAnonymousUserProjectGetFails(): void
    {
        $this->client->request(
            Request::METHOD_GET,
            '/api/witcher_projects/BUG'
        );

        $this->assertSame(Response::HTTP_UNAUTHORIZED, $this->client->getResponse()->getStatusCode());
    }

    /**
     * @dataProvider getCreateProjectData
     *
     * @param array<string, string> $witcherProject
     */
    public function testAnonymousUserHasNoAccessToCreateProject(array $witcherProject): void
    {
        $this->client->request(
            Request::METHOD_POST,
            '/api/witcher_projects',
            [],
            [],
            [self::CONTENT_TYPE_HEADER => self::JSON_CONTENT_TYPE],
            \json_encode($witcherProject, \JSON_THROW_ON_ERROR)
        );

        $this->assertSame(Response::HTTP_UNAUTHORIZED, $this->client->getResponse()->getStatusCode());

        $project = $this->witcherProjectRepository->findOneBy(['name' => $witcherProject['name']]);

        $this->assertNull($project);
    }

    public function testAnonymousUserHasNoAccessToUpdateProject(): void
    {
        $name = 'Test patch method';

        /** @var WitcherProject $project */
        $project = $this->witcherProjectRepository->findOneBy(['id' => 1]);

        $this->assertNotNull($project);
        $this->assertNotEquals($name, $project->getName());

        $updatedProject = ['name' => $name];

        $this->client->request(
            Request::METHOD_PATCH,
            '/api/witcher_projects/BUG',
            [],
            [],
            [
                self::CONTENT_TYPE_HEADER => self::MERGE_PATCH_JSON,
                self::ACCEPT_HEADER => self::JSON_CONTENT_TYPE,
            ],
            \json_encode($updatedProject, \JSON_THROW_ON_ERROR)
        );

        $response = $this->client->getResponse();
        $this->assertSame(Response::HTTP_UNAUTHORIZED, $response->getStatusCode());

        $project = $this->witcherProjectRepository->findOneBy(['name' => $updatedProject['name']]);
        $this->assertNull($project);
    }

    public function testAnonymousUserHasNoAccessToDeleteProject(): void
    {
        /** @var WitcherProject $project */
        $project = $this->witcherProjectRepository->findOneBy(['id' => 1]);

        $this->assertNotNull($project);

        $this->client->request(
            Request::METHOD_DELETE,
            '/api/witcher_projects/BUG'
        );

        $this->assertSame(Response::HTTP_UNAUTHORIZED, $this->client->getResponse()->getStatusCode());

        $project = $this->witcherProjectRepository->findOneBy(['id' => 1]);
        $this->assertNotNull($project);
    }

    public function testAdminUserHasAccessToViewProjectCollection(): void
    {
        $adminUser = $this->witcherUserRepository->findWitcherUserByRole(Role::ADMIN);

        self::assertNotNull($adminUser);

        $this->loginUser($this->getSecurityModelUserFromWitcherUser($adminUser))->request(
            Request::METHOD_GET,
            '/api/witcher_projects'
        );

        $response = $this->client->getResponse();

        $this->assertSame(Response::HTTP_OK, $response->getStatusCode());

        $this->assertCount(
            3,
            $this->deserialize($response->getContent(), WitcherProject::class.'[]')
        );
    }

    /**
     * @dataProvider getCreateProjectData
     *
     * @param array<string, string> $witcherProject
     */
    public function testAdminUserHasAccessToCreateProject(array $witcherProject): void
    {
        $adminUser = $this->witcherUserRepository->findWitcherUserByRole(Role::ADMIN);

        self::assertNotNull($adminUser);

        $this->loginUser($this->getSecurityModelUserFromWitcherUser($adminUser))->request(
            Request::METHOD_POST,
            '/api/witcher_projects',
            [],
            [],
            [self::CONTENT_TYPE_HEADER => self::JSON_CONTENT_TYPE],
            \json_encode($witcherProject, \JSON_THROW_ON_ERROR)
        );

        $this->assertSame(Response::HTTP_CREATED, $this->client->getResponse()->getStatusCode());

        $project = $this->witcherProjectRepository->findOneBy(['name' => $witcherProject['name']]);

        $this->assertNotNull($project);
    }

    public function testAdminUserHasAccessToRetrieveSingleProject(): void
    {
        $adminUser = $this->witcherUserRepository->findWitcherUserByRole(Role::ADMIN);

        self::assertNotNull($adminUser);

        $this->loginUser($this->getSecurityModelUserFromWitcherUser($adminUser))->request(
            Request::METHOD_GET,
            '/api/witcher_projects/BUG'
        );

        $response = $this->client->getResponse();
        $this->assertSame(Response::HTTP_OK, $response->getStatusCode());

        $this->assertNotNull($this->deserialize($response->getContent(), WitcherProject::class));
    }

    public function testAdminUserHasAccessToUpdateProject(): void
    {
        $name = 'Test patch method';

        $adminUser = $this->witcherUserRepository->findWitcherUserByRole(Role::ADMIN);

        self::assertNotNull($adminUser);

        /** @var WitcherProject $project */
        $project = $this->witcherProjectRepository->findOneBy(['id' => 1]);

        $this->assertNotNull($project);
        $this->assertNotEquals($name, $project->getName());

        $updatedProject = ['name' => $name];

        $this->loginUser($this->getSecurityModelUserFromWitcherUser($adminUser))->request(
            Request::METHOD_PATCH,
            '/api/witcher_projects/BUG',
            [],
            [],
            [
                self::CONTENT_TYPE_HEADER => self::MERGE_PATCH_JSON,
                self::ACCEPT_HEADER => self::JSON_CONTENT_TYPE,
            ],
            \json_encode($updatedProject, \JSON_THROW_ON_ERROR)
        );

        $response = $this->client->getResponse();
        $this->assertSame(Response::HTTP_OK, $response->getStatusCode());

        $project = $this->witcherProjectRepository->findOneBy(['name' => $updatedProject['name']]);
        $this->assertNotNull($project);
    }

    public function testAdminTriesToDeleteProject(): void
    {
        $admin = $this->witcherUserRepository->findWitcherUserByRole(Role::ADMIN);

        self::assertNotNull($admin);

        /** @var WitcherProject $project */
        $project = $this->witcherProjectRepository->findOneBy(['id' => 5]);

        $this->assertNotNull($project);
        $this->assertNotEquals($admin->getId(), $project->getCreatedBy()->getId());

        $this->loginUser($this->getSecurityModelUserFromWitcherUser($admin))->request(
            Request::METHOD_DELETE,
            '/api/witcher_projects/TASK'
        );

        $this->assertSame(Response::HTTP_NO_CONTENT, $this->client->getResponse()->getStatusCode());

        $project = $this->witcherProjectRepository->findOneBy(['id' => 5]);
        $this->assertNull($project);
    }

    public function testDeveloperHasAccessToOwnedProjectsOnly(): void
    {
        $developer = $this->witcherUserRepository->findWitcherUserByRole(Role::DEVELOPER);

        self::assertNotNull($developer);

        $this->loginUser($this->getSecurityModelUserFromWitcherUser($developer))->request(
            Request::METHOD_GET,
            '/api/witcher_projects'
        );

        $response = $this->client->getResponse();
        $this->assertSame(Response::HTTP_OK, $response->getStatusCode());
        $this->assertCount(2, $this->deserialize($response->getContent(), WitcherProject::class.'[]'));
    }

    public function testDeveloperTriesToAccessNotOwnedProject(): void
    {
        $developer = $this->witcherUserRepository->findWitcherUserByRole(Role::DEVELOPER);

        self::assertNotNull($developer);

        $this->loginUser($this->getSecurityModelUserFromWitcherUser($developer))->request(
            Request::METHOD_GET,
            '/api/witcher_projects/FEATURE'
        );

        $this->assertSame(Response::HTTP_FORBIDDEN, $this->client->getResponse()->getStatusCode());
    }

    /**
     * @dataProvider getCreateProjectData
     *
     * @param array<string, string> $witcherProject
     */
    public function testDeveloperTriesToCreateProject(array $witcherProject): void
    {
        $developer = $this->witcherUserRepository->findWitcherUserByRole(Role::DEVELOPER);

        self::assertNotNull($developer);

        $this->loginUser($this->getSecurityModelUserFromWitcherUser($developer))->request(
            Request::METHOD_POST,
            '/api/witcher_projects',
            [],
            [],
            [self::CONTENT_TYPE_HEADER => self::JSON_CONTENT_TYPE],
            \json_encode($witcherProject, \JSON_THROW_ON_ERROR)
        );

        $this->assertSame(Response::HTTP_FORBIDDEN, $this->client->getResponse()->getStatusCode());

        $project = $this->witcherProjectRepository->findOneBy(['name' => $witcherProject['name']]);

        $this->assertNull($project);
    }

    public function testDeveloperTriesToUpdateNotOwnedProject(): void
    {
        $developer = $this->witcherUserRepository->findWitcherUserByRole(Role::DEVELOPER);

        self::assertNotNull($developer);

        $name = 'Test patch method';

        /** @var WitcherProject $project */
        $project = $this->witcherProjectRepository->findOneBy(['id' => 2]);

        $this->assertNotNull($project);
        $this->assertNotEquals($developer, $project->getCreatedBy()->getId());

        $updatedProject = ['name' => $name];

        $this->loginUser($this->getSecurityModelUserFromWitcherUser($developer))->request(
            Request::METHOD_PATCH,
            '/api/witcher_projects/FEATURE',
            [],
            [],
            [
                self::CONTENT_TYPE_HEADER => self::MERGE_PATCH_JSON,
                self::ACCEPT_HEADER => self::JSON_CONTENT_TYPE,
            ],
            \json_encode($updatedProject, \JSON_THROW_ON_ERROR)
        );

        $response = $this->client->getResponse();
        $this->assertSame(Response::HTTP_FORBIDDEN, $response->getStatusCode());

        $project = $this->witcherProjectRepository->findOneBy(['name' => $updatedProject['name']]);
        $this->assertNull($project);
    }

    public function testDeveloperTriesToUpdateOwnedProject(): void
    {
        $developer = $this->witcherUserRepository->findWitcherUserByRole(Role::DEVELOPER);

        self::assertNotNull($developer);

        $name = 'Test patch method';

        /** @var WitcherProject $project */
        $project = $this->witcherProjectRepository->findOneBy(['id' => 5]);

        $this->assertNotNull($project);
        $this->assertEquals($developer->getId(), $project->getCreatedBy()->getId());

        $updatedProject = ['name' => $name];

        $this->loginUser($this->getSecurityModelUserFromWitcherUser($developer))->request(
            Request::METHOD_PATCH,
            '/api/witcher_projects/TASK',
            [],
            [],
            [
                self::CONTENT_TYPE_HEADER => self::MERGE_PATCH_JSON,
                self::ACCEPT_HEADER => self::JSON_CONTENT_TYPE,
            ],
            \json_encode($updatedProject, \JSON_THROW_ON_ERROR)
        );

        $response = $this->client->getResponse();
        $this->assertSame(Response::HTTP_OK, $response->getStatusCode());

        $project = $this->witcherProjectRepository->findOneBy(['name' => $updatedProject['name']]);
        $this->assertNotNull($project);
    }

    public function testDeveloperTriesToDeleteNotOwnedProject(): void
    {
        $developer = $this->witcherUserRepository->findWitcherUserByRole(Role::DEVELOPER);

        self::assertNotNull($developer);

        /** @var WitcherProject $project */
        $project = $this->witcherProjectRepository->findOneBy(['id' => 2]);

        $this->assertNotNull($project);
        $this->assertNotEquals($developer->getId(), $project->getCreatedBy()->getId());

        $this->loginUser($this->getSecurityModelUserFromWitcherUser($developer))->request(
            Request::METHOD_DELETE,
            '/api/witcher_projects/FEATURE'
        );

        $response = $this->client->getResponse();
        $this->assertSame(Response::HTTP_FORBIDDEN, $response->getStatusCode());

        $project = $this->witcherProjectRepository->findOneBy(['id' => 2]);
        $this->assertNotNull($project);
    }

    public function testDeveloperTriesToDeleteOwnedProject(): void
    {
        $developer = $this->witcherUserRepository->findWitcherUserByRole(Role::DEVELOPER);

        self::assertNotNull($developer);

        /** @var WitcherProject $project */
        $project = $this->witcherProjectRepository->findOneBy(['id' => 5]);

        $this->assertNotNull($project);
        $this->assertEquals($developer->getId(), $project->getCreatedBy()->getId());

        $this->loginUser($this->getSecurityModelUserFromWitcherUser($developer))->request(
            Request::METHOD_DELETE,
            '/api/witcher_projects/TASK'
        );

        $this->assertSame(Response::HTTP_NO_CONTENT, $this->client->getResponse()->getStatusCode());

        $project = $this->witcherProjectRepository->findOneBy(['id' => 5]);
        $this->assertNull($project);
    }

    public function testManagerHasAccessToAllProjects(): void
    {
        $manager = $this->witcherUserRepository->findWitcherUserByRole(Role::MANAGER);

        self::assertNotNull($manager);

        $this->loginUser($this->getSecurityModelUserFromWitcherUser($manager))->request(
            Request::METHOD_GET,
            '/api/witcher_projects'
        );

        $response = $this->client->getResponse();
        $this->assertSame(Response::HTTP_OK, $response->getStatusCode());
        $this->assertCount(3, $this->deserialize($response->getContent(), WitcherProject::class.'[]'));
    }

    public function testManagerHasAccessToSingleProject(): void
    {
        $manager = $this->witcherUserRepository->findWitcherUserByRole(Role::MANAGER);

        self::assertNotNull($manager);

        $this->loginUser($this->getSecurityModelUserFromWitcherUser($manager))->request(
            Request::METHOD_GET,
            '/api/witcher_projects/BUG'
        );

        $response = $this->client->getResponse();
        $this->assertSame(Response::HTTP_OK, $response->getStatusCode());
        $this->assertNotNull($this->deserialize($response->getContent(), WitcherProject::class));
    }

    public function testManagerHasAccessToUpdateProject(): void
    {
        $manager = $this->witcherUserRepository->findWitcherUserByRole(Role::MANAGER);

        self::assertNotNull($manager);

        $name = 'Test patch method';

        /** @var WitcherProject $project */
        $project = $this->witcherProjectRepository->findOneBy(['id' => 1]);

        $this->assertNotNull($project);

        $updatedProject = ['name' => $name];

        $this->loginUser($this->getSecurityModelUserFromWitcherUser($manager))->request(
            Request::METHOD_PATCH,
            '/api/witcher_projects/BUG',
            [],
            [],
            [
                self::CONTENT_TYPE_HEADER => self::MERGE_PATCH_JSON,
                self::ACCEPT_HEADER => self::JSON_CONTENT_TYPE,
            ],
            \json_encode($updatedProject, \JSON_THROW_ON_ERROR)
        );

        $response = $this->client->getResponse();
        $this->assertSame(Response::HTTP_OK, $response->getStatusCode());

        $project = $this->witcherProjectRepository->findOneBy(['name' => $updatedProject['name']]);
        $this->assertNotNull($project);
    }

    public function testManagerHasAccessToDeleteProject(): void
    {
        $manager = $this->witcherUserRepository->findWitcherUserByRole(Role::MANAGER);

        self::assertNotNull($manager);

        /** @var WitcherProject $project */
        $project = $this->witcherProjectRepository->findOneBy(['id' => 2]);

        $this->assertNotNull($project);

        $this->loginUser($this->getSecurityModelUserFromWitcherUser($manager))->request(
            Request::METHOD_DELETE,
            '/api/witcher_projects/FEATURE'
        );

        $response = $this->client->getResponse();
        $this->assertSame(Response::HTTP_NO_CONTENT, $response->getStatusCode());

        $project = $this->witcherProjectRepository->findOneBy(['id' => 2]);
        $this->assertNull($project);
    }

    /**
     * @return array<string, mixed>
     */
    public function getCreateProjectData(): array
    {
        return [
            [
                [
                    'name' => 'Verify user can create project',
                    'description' => 'Verify user can create project',
                    'slug' => 'SAMPLE',
                ],
            ],
        ];
    }
}
