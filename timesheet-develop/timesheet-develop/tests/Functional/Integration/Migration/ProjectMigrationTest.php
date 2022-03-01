<?php

declare(strict_types=1);

namespace App\Tests\Functional\Integration\Migration;

use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;
use Jagaad\WitcherApi\Integration\Application\DTO\Request;
use Jagaad\WitcherApi\Integration\Application\IssueTracker\Jira\Migration\ProjectMigration;
use Jagaad\WitcherApi\Integration\Domain\IssueTracker\IssueType;
use Jagaad\WitcherApi\Integration\Domain\IssueTracker\Message\JiraTimeTrackerEvent;
use Jagaad\WitcherApi\Integration\Domain\IssueTracker\PaginatedProjects;
use Jagaad\WitcherApi\Integration\Domain\IssueTracker\Project;
use Jagaad\WitcherApi\Integration\Domain\IssueTracker\User;
use Jagaad\WitcherApi\Integration\Migration\ProjectMigrationFlowHandlerInterface;
use Jagaad\WitcherApi\Integration\Repository\IssueTracker\ProjectReadApiInterface;
use Jagaad\WitcherApi\Renderer\RendererInterface;
use Jagaad\WitcherApi\Repository\TrackerTaskTypeRepository;
use Jagaad\WitcherApi\Repository\WitcherProjectRepository;
use Jagaad\WitcherApi\Repository\WitcherUserRepository;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ProjectMigrationTest extends KernelTestCase
{
    use ProphecyTrait;
    use ReloadDatabaseTrait;

    private LoggerInterface|ObjectProphecy $logger;
    private RendererInterface|ObjectProphecy $renderer;

    private WitcherProjectRepository $projectRepository;
    private ProjectReadApiInterface|ObjectProphecy $projectReadApiRepository;
    private WitcherUserRepository $witcherUserRepository;

    private TrackerTaskTypeRepository $trackerTaskTypeRepository;
    private ProjectMigrationFlowHandlerInterface $migrationFlowHandler;

    protected function setUp(): void
    {
        self::bootKernel(['environment' => 'test']);

        $container = static::getContainer();

        $this->logger = $this->prophesize(LoggerInterface::class);
        $this->renderer = $this->prophesize(RendererInterface::class);
        $this->projectReadApiRepository = $this->prophesize(ProjectReadApiInterface::class);

        $this->projectRepository = $container->get(WitcherProjectRepository::class);
        $this->witcherUserRepository = $container->get(WitcherUserRepository::class);
        $this->trackerTaskTypeRepository = $container->get(TrackerTaskTypeRepository::class);

        $this->migrationFlowHandler = $container->get(ProjectMigrationFlowHandlerInterface::class);
    }

    /**
     * @dataProvider getProjectTestData
     */
    public function testMigrateForActiveProject(
        Project $project,
        PaginatedProjects $paginatedProjects
    ): void {
        $projectMigration = $this->createProjectMigration($paginatedProjects);

        $projectMigration->migrate(new Request());

        $existingProjects = $this->projectRepository->findProjectsByExternalKey([$project->getKey()]);

        $projectObj = $existingProjects[$project->getKey()];

        $this->assertSame($project->getName(), $projectObj->getName());
    }

    /**
     * @dataProvider getProjectToDeleteTestData
     */
    public function testMigrateForProjectWithDeletedEvent(
        Project $project,
        PaginatedProjects $paginatedProjects
    ): void {
        $projectMigration = $this->createProjectMigration($paginatedProjects);

        $existingProjects = $this->projectRepository->findProjectsByExternalKey([$project->getKey()]);

        $this->assertCount(1, $existingProjects);

        $externalKey = $existingProjects[$project->getKey()]->getExternalKey();

        $projectMigration->migrate(
            new Request(
                [
                    Request::KEYS_PARAM => [$externalKey],
                    'action' => JiraTimeTrackerEvent::PROJECT_DELETED,
                ]
            )
        );

        $projectAfterMigration = $this->projectRepository->findProjectsByExternalKey([$project->getKey()]);

        $this->assertCount(0, $projectAfterMigration);
    }

    /**
     * @dataProvider getSoftDeleteProjectTestData
     */
    public function testMigrateForSoftDeleteProject(
        Project $project,
        PaginatedProjects $paginatedProjects
    ): void {
        $projectMigration = $this->createProjectMigration($paginatedProjects);

        $projectMigration->migrate(new Request());

        $existingProjects = $this->projectRepository->findProjectsByExternalKey(
            [$project->getKey()],
            false
        );

        $projectObj = $existingProjects[$project->getKey()];

        $this->assertNotSame($project->getName(), $projectObj->getName());
    }

    /**
     * @dataProvider getProjectTestData
     */
    public function testMigrateAllForActiveProject(
        Project $project,
        PaginatedProjects $paginatedProjects
    ): void {
        $projectMigration = $this->createProjectMigration($paginatedProjects);

        $projectMigration->migrateAll(new Request());

        $existingProjects = $this->projectRepository->findProjectsByExternalKey([$project->getKey()]);

        $projectObj = $existingProjects[$project->getKey()];

        $this->assertSame($project->getName(), $projectObj->getName());
    }

    /**
     * @dataProvider getSoftDeleteProjectTestData
     */
    public function testMigrateAllForSoftDeleteProject(
        Project $project,
        PaginatedProjects $paginatedProjects
    ): void {
        $projectMigration = $this->createProjectMigration($paginatedProjects);

        $projectMigration->migrateAll(new Request());

        $existingProjects = $this->projectRepository->findProjectsByExternalKey(
            [$project->getKey()],
            false
        );

        $projectObj = $existingProjects[$project->getKey()];

        $this->assertNotSame($project->getName(), $projectObj->getName());
    }

    /**
     * @dataProvider getProjectWithIncorrectExternalKey
     */
    public function testProjectWithIncorrectExternalKey(
        Project $project,
        PaginatedProjects $paginatedProjects
    ): void {
        $projectMigration = $this->createProjectMigration($paginatedProjects);

        $projectMigration->migrateAll(new Request());

        $projectResult = $this->projectRepository->findOneByExternalKey(
            $project->getKey(),
            false
        );

        $this->assertNull($projectResult);
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function getProjectTestData(): array
    {
        $user = $this->createUser('sandip', 'developer', 'test1-jira-account', true, 'sandy', 'sandip@jagaad.it', 'test', 'IST');

        return [
            'it will test existing project migration' => [
                $project = $this->createProject(
                    '1',
                    'test-project-existing',
                    'test-project-desc',
                    1,
                    'BUG',
                    [1 => 'test', 2 => 'test2'],
                    $this->createIssueType('2', 'Bug', 'Bug'),
                    'bugfixing',
                    'https://test',
                    $user
                ),
                $this->createPaginatedProjects('test', 2, 0, 3, true, $project),
            ],

            'it will test new project migration' => [
                $project = $this->createProject(
                    '1',
                    'test-project-new',
                    'test-project-desc',
                    1,
                    'TEST',
                    [1 => 'test', 2 => 'test2'],
                    $this->createIssueType('1', 'Bug', 'Bug'),
                    'bugfixing',
                    'https://test',
                    $user
                ),
                $this->createPaginatedProjects('test', 2, 0, 3, true, $project),
            ],
        ];
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function getProjectWithIncorrectExternalKey(): array
    {
        $user = $this->createUser('sandip', 'developer', '12345', true, 'sandy', 'sandip@jagaad.it', 'test', 'IST');

        return [
            'it will test project with digits suffix slug' => [
                $project = $this->createProject(
                    '1',
                    'test-project-existing',
                    'test-project-desc',
                    1,
                    'BUG-234',
                    [1 => 'test', 2 => 'test2'],
                    $this->createIssueType('2', 'Bug', 'Bug'),
                    'bugfixing',
                    'https://test',
                    $user
                ),
                $this->createPaginatedProjects('test', 2, 0, 3, true, $project),
            ],
            'it will test project with digits prefix slug' => [
                $project = $this->createProject(
                    '1',
                    'test-project-existing',
                    'test-project-desc',
                    1,
                    '234BUG',
                    [1 => 'test', 2 => 'test2'],
                    $this->createIssueType('2', 'Bug', 'Bug'),
                    'bugfixing',
                    'https://test',
                    $user
                ),
                $this->createPaginatedProjects('test', 2, 0, 3, true, $project),
            ],
            'it will test slug with digits only' => [
                $project = $this->createProject(
                    '1',
                    'test-project-existing',
                    'test-project-desc',
                    1,
                    '1234',
                    [1 => 'test', 2 => 'test2'],
                    $this->createIssueType('2', 'Bug', 'Bug'),
                    'bugfixing',
                    'https://test',
                    $user
                ),
                $this->createPaginatedProjects('test', 2, 0, 3, true, $project),
            ],
            'it will test slug with underscores slug' => [
                $project = $this->createProject(
                    '1',
                    'test-project-existing',
                    'test-project-desc',
                    1,
                    'BUG_TEST',
                    [1 => 'test', 2 => 'test2'],
                    $this->createIssueType('2', 'Bug', 'Bug'),
                    'bugfixing',
                    'https://test',
                    $user
                ),
                $this->createPaginatedProjects('test', 2, 0, 3, true, $project),
            ],
            'it will test slug with dashes' => [
                $project = $this->createProject(
                    '1',
                    'test-project-existing',
                    'test-project-desc',
                    1,
                    'BUG-TEST',
                    [1 => 'test', 2 => 'test2'],
                    $this->createIssueType('2', 'Bug', 'Bug'),
                    'bugfixing',
                    'https://test',
                    $user
                ),
                $this->createPaginatedProjects('test', 2, 0, 3, true, $project),
            ],
            'it will test empty slug' => [
                $project = $this->createProject(
                    '1',
                    'test-project-existing',
                    'test-project-desc',
                    1,
                    '',
                    [1 => 'test', 2 => 'test2'],
                    $this->createIssueType('2', 'Bug', 'Bug'),
                    'bugfixing',
                    'https://test',
                    $user
                ),
                $this->createPaginatedProjects('test', 2, 0, 3, true, $project),
            ],
            'it will test maximum characters exceeded slug length' => [
                $project = $this->createProject(
                    '1',
                    'test-project-existing',
                    'test-project-desc',
                    1,
                    'ABCDEFGHIJKLMNOPQRSTV',
                    [1 => 'test', 2 => 'test2'],
                    $this->createIssueType('2', 'Bug', 'Bug'),
                    'bugfixing',
                    'https://test',
                    $user
                ),
                $this->createPaginatedProjects('test', 2, 0, 3, true, $project),
            ],
        ];
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function getProjectToDeleteTestData(): array
    {
        $user = $this->createUser('sandip', 'developer', '12345', true, 'sandy', 'sandip@jagaad.it', 'test', 'IST');

        return [
            'it will test existing project delete migration event' => [
                $project = $this->createProject(
                    '1',
                    'test-project-existing',
                    'test-project-desc',
                    1,
                    'BUG',
                    [1 => 'test', 2 => 'test2'],
                    $this->createIssueType('2', 'Bug', 'Bug'),
                    'bugfixing',
                    'https://test',
                    $user
                ),
                $this->createPaginatedProjects('test', 2, 0, 3, true, $project),
            ],
        ];
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function getSoftDeleteProjectTestData(): array
    {
        return [
            'it will test existing inactive project migration' => [
                $project = $this->createProject(
                    '1',
                    'test-project-inactive',
                    'test-project-desc',
                    1,
                    'FIXING',
                    [1 => 'test', 2 => 'test2'],
                    $this->createIssueType('2', 'Bug', 'Bug'),
                    'bugfixing',
                    'https://test',
                    $this->createUser('sandip', 'developer', 'test1-jira-account', true, 'sandy', 'sandip@jagaad.it', 'test', 'IST')
                ),
                $this->createPaginatedProjects('test', 2, 0, 3, true, $project),
            ],
        ];
    }

    private function createProjectMigration(PaginatedProjects $paginatedProject): ProjectMigration
    {
        $this->projectReadApiRepository
            ->getAllPaginated(new Request())
            ->willReturn($paginatedProject)
        ;

        /** @var ProjectReadApiInterface $projectReadApiRepo */
        $projectReadApiRepo = $this->projectReadApiRepository->reveal();
        /** @var LoggerInterface $logger */
        $logger = $this->logger->reveal();
        /** @var RendererInterface $render */
        $render = $this->renderer->reveal();

        return new ProjectMigration(
            $projectReadApiRepo,
            $this->projectRepository,
            $this->witcherUserRepository,
            $this->trackerTaskTypeRepository,
            $this->migrationFlowHandler,
            $logger,
            $render
        );
    }

    private function createIssueType(string $id, string $name, string $key, bool $subTask = false): IssueType
    {
        return (new IssueType())
            ->setId($id)
            ->setName($name)
            ->setKey($key)
            ->setSubtask($subTask);
    }

    private function createUser(
        string $name,
        string $key,
        string $accountId,
        bool $active,
        string $displayName,
        string $emailAddress,
        string $expand,
        string $timeZone
    ): User {
        return (new User())
            ->setName($name)
            ->setKey($key)
            ->setAccountId($accountId)
            ->setActive($active)
            ->setDisplayName($displayName)
            ->setEmailAddress($emailAddress)
            ->setExpand($expand)
            ->setTimeZone($timeZone);
    }

    /**
     * @param array<int, mixed> $projectCategory
     */
    private function createProject(
        string $id,
        string $name,
        string $description,
        int $categoryId,
        string $key,
        array $projectCategory,
        IssueType $issueType,
        string $projectTypeKey,
        string $url,
        User $user
    ): Project {
        return (new Project())
            ->setId($id)
            ->setName($name)
            ->setDescription($description)
            ->setCategoryId($categoryId)
            ->setKey($key)
            ->setProjectCategory($projectCategory)
            ->setIssueTypes([0 => $issueType])
            ->setProjectTypeKey($projectTypeKey)
            ->setUrl($url)
            ->setLead($user);
    }

    private function createPaginatedProjects(
        string $self,
        int $maxResults,
        int $startAt,
        int $total,
        bool $isLast,
        Project $project
    ): PaginatedProjects {
        return (new PaginatedProjects())
            ->setSelf($self)
            ->setMaxResults($maxResults)
            ->setStartAt($startAt)
            ->setTotal($total)
            ->setIsLast($isLast)
            ->setValues([0 => $project]);
    }
}
