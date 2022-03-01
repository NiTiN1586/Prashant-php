<?php

declare(strict_types=1);

namespace App\Tests\Functional\Entity;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;
use Jagaad\WitcherApi\Entity\Activity;
use Jagaad\WitcherApi\Entity\Role;
use Jagaad\WitcherApi\Entity\Task;
use Jagaad\WitcherApi\Entity\Technology;
use Jagaad\WitcherApi\Enum\ContextGroup;
use Jagaad\WitcherApi\Repository\WitcherUserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * TODO: complete in WITCHER-336
 */
class ActivityTest extends AbstractApiResourceTest
{
    use RefreshDatabaseTrait;

    private Registry $registry;
    private WitcherUserRepository $witcherUserRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->registry = static::getContainer()->get('doctrine');
        $this->witcherUserRepository = static::getContainer()->get(WitcherUserRepository::class);
    }

    public function testAnonymousUserActivitiesGetCollectionFails(): void
    {
        $this->client->request(
            Request::METHOD_GET,
            '/api/activities'
        );

        $this->assertSame(Response::HTTP_UNAUTHORIZED, $this->client->getResponse()->getStatusCode());
    }

    public function testAnonymousUserActivityGetFails(): void
    {
        $this->client->request(
            Request::METHOD_GET,
            '/api/activities/1'
        );

        $this->assertSame(Response::HTTP_UNAUTHORIZED, $this->client->getResponse()->getStatusCode());
    }

    public function testAnonymousUserHasNoAccessToCreateActivity(): void
    {
        $activity = $this->createActivity();

        $this->client->request(
            Request::METHOD_POST,
            '/api/activities',
            [],
            [],
            [self::CONTENT_TYPE_HEADER => self::JSON_CONTENT_TYPE],
            \json_encode($activity, \JSON_THROW_ON_ERROR)
        );

        $this->assertSame(Response::HTTP_UNAUTHORIZED, $this->client->getResponse()->getStatusCode());

        $this->assertNull(
            $this->registry
                ->getRepository(Activity::class)
                ->findOneBy(['comment' => $activity['comment']])
        );
    }

    public function testAnonymousUserHasNoAccessToUpdateActivity(): void
    {
        $comment = 'newly created test';

        /** @var Activity $activity */
        $activity = $this->registry->getRepository(Activity::class)->findOneBy(['id' => 1]);

        $this->assertNotNull($activity);
        $this->assertNotEquals($comment, $activity->getComment());

        $this->client->request(
            Request::METHOD_PATCH,
            '/api/activities/1',
            [],
            [],
            [
                self::CONTENT_TYPE_HEADER => self::MERGE_PATCH_JSON,
                self::ACCEPT_HEADER => self::JSON_CONTENT_TYPE,
            ],
            \json_encode(['comment' => $comment], \JSON_THROW_ON_ERROR)
        );

        $response = $this->client->getResponse();
        $this->assertSame(Response::HTTP_UNAUTHORIZED, $response->getStatusCode());

        $this->assertNull(
            $this->registry
                ->getRepository(Activity::class)
                ->findOneBy(['comment' => $comment]
                )
        );
    }

    public function testAnonymousUserHasNoAccessToDeleteActivity(): void
    {
        /** @var Activity $activity */
        $activity = $this->registry
            ->getRepository(Activity::class)
            ->findOneBy(['id' => 1]);

        $this->assertNotNull($activity);

        $this->client->request(
            Request::METHOD_DELETE,
            '/api/activities/1'
        );

        $this->assertSame(Response::HTTP_UNAUTHORIZED, $this->client->getResponse()->getStatusCode());

        $activity = $this->registry
            ->getRepository(Activity::class)
            ->findOneBy(['id' => 1]);

        $this->assertNotNull($activity);
    }

    public function testAdminUserHasAccessToViewActivityCollection(): void
    {
        $adminUser = $this->witcherUserRepository->findWitcherUserByRole(Role::ADMIN);

        self::assertNotNull($adminUser);

        $this->loginUser($this->getSecurityModelUserFromWitcherUser($adminUser))->request(
            Request::METHOD_GET,
            '/api/activities'
        );

        $response = $this->client->getResponse();

        $this->assertSame(Response::HTTP_OK, $response->getStatusCode());

        $this->assertCount(
            2,
            $this->deserialize($response->getContent(), Activity::class.'[]')
        );
    }

    public function testAdminUserHasAccessToCreateActivity(): void
    {
        $adminUser = $this->witcherUserRepository->findWitcherUserByRole(Role::ADMIN);
        $activity = $this->createActivity();

        self::assertNotNull($adminUser);

        $this->loginUser($this->getSecurityModelUserFromWitcherUser($adminUser))->request(
            Request::METHOD_POST,
            '/api/activities',
            [],
            [],
            [self::CONTENT_TYPE_HEADER => self::JSON_CONTENT_TYPE],
            \json_encode($activity, \JSON_THROW_ON_ERROR)
        );

        $this->assertSame(Response::HTTP_CREATED, $this->client->getResponse()->getStatusCode());

        $this->assertNotNull(
            $this->registry
                ->getRepository(Activity::class)
                ->findOneBy(['comment' => $activity['comment']])
        );
    }

    public function testAdminUserHasAccessToRetrieveSingleActivity(): void
    {
        $adminUser = $this->witcherUserRepository->findWitcherUserByRole(Role::ADMIN);

        self::assertNotNull($adminUser);

        $this->loginUser($this->getSecurityModelUserFromWitcherUser($adminUser))->request(
            Request::METHOD_GET,
            '/api/activities/1'
        );

        $response = $this->client->getResponse();
        $this->assertTrue($response->isOk());

        $this->assertNotNull($this->deserialize($response->getContent(), Activity::class));
    }

    public function testAdminUserHasAccessToUpdateActivity(): void
    {
        $comment = 'Test patch method';

        $adminUser = $this->witcherUserRepository->findWitcherUserByRole(Role::ADMIN);

        self::assertNotNull($adminUser);

        /** @var Activity $activity */
        $activity = $this->registry->getRepository(Activity::class)->findOneBy(['id' => 1]);

        $this->assertNotNull($activity);
        $this->assertNotEquals($comment, $activity->getComment());

        $this->loginUser($this->getSecurityModelUserFromWitcherUser($adminUser))->request(
            Request::METHOD_PATCH,
            '/api/activities/1',
            [],
            [],
            [
                self::CONTENT_TYPE_HEADER => self::MERGE_PATCH_JSON,
                self::ACCEPT_HEADER => self::JSON_CONTENT_TYPE,
            ],
            \json_encode(['comment' => $comment], \JSON_THROW_ON_ERROR)
        );

        $response = $this->client->getResponse();
        $this->assertTrue($response->isOk());

        $activity = $this->registry
            ->getRepository(Activity::class)
            ->findOneBy(['comment' => $comment]);

        $this->assertNotNull($activity);
    }

    public function testAdminTriesToDeleteActivity(): void
    {
        $admin = $this->witcherUserRepository->findWitcherUserByRole(Role::ADMIN);

        self::assertNotNull($admin);

        /** @var Activity $activity */
        $activity = $this->registry->getRepository(Activity::class)->findOneBy(['id' => 1]);

        $this->assertNotNull($activity);
        $this->assertNotEquals($admin->getId(), $activity->getCreatedBy()->getId());

        $this->loginUser($this->getSecurityModelUserFromWitcherUser($admin))->request(
            Request::METHOD_DELETE,
            '/api/activities/1'
        );

        $this->assertSame(Response::HTTP_NO_CONTENT, $this->client->getResponse()->getStatusCode());

        /** @var Activity $activity */
        $activity = $this->registry->getRepository(Activity::class)->findOneBy(['id' => 1]);

        $this->assertNull($activity);
    }

    public function testDeveloperHasAccessToOwnedActivitiesOnly(): void
    {
        $developer = $this->witcherUserRepository->findWitcherUserByRole(Role::DEVELOPER);

        self::assertNotNull($developer);

        $this->loginUser($this->getSecurityModelUserFromWitcherUser($developer))->request(
            Request::METHOD_GET,
            '/api/activities'
        );

        $response = $this->client->getResponse();
        $this->assertTrue($response->isOk());

        /** @var Activity[] $activities */
        $activities = $this->deserialize(
            $response->getContent(),
            Activity::class.'[]',
            [ContextGroup::GROUP_GENERIC_TRAIT_READ]
        );

        $this->assertCount(2, $activities);
    }

    /**
     * @return array<string, mixed>
     */
    private function createActivity(): array
    {
        $task = $this->registry->getRepository(Task::class)->findOneBy(['id' => 1]);
        $activity = $this->registry->getRepository(Activity::class)->findOneBy(['id' => 1]);
        $technology = $this->registry->getRepository(Technology::class)->findOneBy(['id' => 1]);

        return [
            'duration' => 2,
            'task' => $task->getSlug(),
            'comment' => 'Test',
            'activityType' => $activity->getId(),
            'technology' => $technology->getId(),
            'activityAt' => (new \DateTimeImmutable())->format(\DateTimeImmutable::ATOM),
        ];
    }
}
