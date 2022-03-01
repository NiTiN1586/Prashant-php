<?php

declare(strict_types=1);

namespace App\Tests\Functional\Integration\Migration;

use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;
use Jagaad\WitcherApi\Entity\Label;
use Jagaad\WitcherApi\Entity\Task;
use Jagaad\WitcherApi\Integration\Application\DTO\Request;
use Jagaad\WitcherApi\Integration\Application\IssueTracker\Jira\Migration\Handler\IssueMigrationHandler;
use Jagaad\WitcherApi\Integration\Application\IssueTracker\Jira\Migration\IssueMigrationStep\TaskEstimationMigrationStep;
use Jagaad\WitcherApi\Integration\Application\IssueTracker\Jira\Migration\ProjectIssueMigration;
use Jagaad\WitcherApi\Integration\Domain\IssueTracker\Issue;
use Jagaad\WitcherApi\Integration\Domain\IssueTracker\IssueField;
use Jagaad\WitcherApi\Integration\Domain\IssueTracker\IssueSearchResult;
use Jagaad\WitcherApi\Integration\Domain\IssueTracker\IssueStatus;
use Jagaad\WitcherApi\Integration\Domain\IssueTracker\IssueType;
use Jagaad\WitcherApi\Integration\Domain\IssueTracker\Message\JiraTimeTrackerEvent;
use Jagaad\WitcherApi\Integration\Domain\IssueTracker\Project;
use Jagaad\WitcherApi\Integration\Domain\IssueTracker\Reporter;
use Jagaad\WitcherApi\Integration\Repository\IssueTracker\IssueReadApiInterface;
use Jagaad\WitcherApi\Renderer\RendererInterface;
use Jagaad\WitcherApi\Repository\EstimationTypeRepository;
use Jagaad\WitcherApi\Repository\LabelRepository;
use Jagaad\WitcherApi\Repository\PriorityRepository;
use Jagaad\WitcherApi\Repository\StatusRepository;
use Jagaad\WitcherApi\Repository\TaskRepository;
use Jagaad\WitcherApi\Repository\TrackerTaskTypeRepository;
use Jagaad\WitcherApi\Repository\WitcherProjectRepository;
use PHPUnit\Framework\MockObject\MockObject;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ProjectIssueMigrationTest extends KernelTestCase
{
    use ProphecyTrait;
    use ReloadDatabaseTrait;

    private const EXISTING_PROJECT_BUG = 'BUG';
    private const EXISTING_PROJECT_FEATURE = 'FEATURE';
    private const ESTIMATION_CUSTOM_FIELD_KEY = 'customfield_1';
    private const ESTIMATION_VALUE = 5.0;

    private LoggerInterface|ObjectProphecy $logger;
    private RendererInterface|ObjectProphecy $renderer;
    private IssueReadApiInterface|MockObject $issueApiReadRepository;
    private WitcherProjectRepository $witcherProjectRepository;

    private TrackerTaskTypeRepository $trackerTaskTypeRepository;
    private StatusRepository $statusRepository;
    private PriorityRepository $priorityRepository;

    private LabelRepository $labelRepository;
    private IssueMigrationHandler $migrationFlowHandler;
    private TaskRepository $taskRepository;
    private EstimationTypeRepository $estimationTypeRepository;

    protected function setUp(): void
    {
        self::bootKernel(['environment' => 'test']);

        $this->logger = $this->prophesize(LoggerInterface::class);
        $this->renderer = $this->prophesize(RendererInterface::class);
        $this->issueApiReadRepository = $this->createMock(IssueReadApiInterface::class);

        $container = static::getContainer();

        $this->witcherProjectRepository = $container->get(WitcherProjectRepository::class);
        $this->trackerTaskTypeRepository = $container->get(TrackerTaskTypeRepository::class);
        $this->statusRepository = $container->get(StatusRepository::class);
        $this->priorityRepository = $container->get(PriorityRepository::class);
        $this->migrationFlowHandler = $container->get(IssueMigrationHandler::class);
        $this->taskRepository = $container->get(TaskRepository::class);
        $this->labelRepository = $container->get(LabelRepository::class);
        $this->estimationTypeRepository = $container->get(EstimationTypeRepository::class);
    }

    /**
     * @dataProvider getActiveIssueTestData
     */
    public function testProjectIssueMigration(
        Issue $issue,
        Request $request
    ): void {
        $this->setUpIssueApiReadRepositoryMock($issue);

        /** @var Task $savedIssue */
        $savedIssue = $this->callProjectIssueMigrate($request, $issue->getKey());

        /** @var IssueField $issueField */
        $issueField = $issue->getFields();
        $this->assertSame($issueField->getSummary(), $savedIssue->getSummary());
    }

    /**
     * @dataProvider getSoftDeleteIssueTestData
     */
    public function testProjectIssueMigrationForSoftDeleteIssue(
        Issue $issue,
        Request $request
    ): void {
        $this->setUpIssueApiReadRepositoryMock($issue);
        /** @var Task $savedIssue */
        $savedIssue = $this->callProjectIssueMigrate($request, $issue->getKey());

        /** @var IssueField $issueField */
        $issueField = $issue->getFields();
        $this->assertNotSame($issueField->getSummary(), $savedIssue->getSummary());
    }

    /**
     * @dataProvider getActiveIssueTestData
     */
    public function testProjectIssueMigrationAll(
        Issue $issue,
        Request $request
    ): void {
        $this->setUpIssueApiReadRepositoryMock($issue);

        /** @var Task $savedIssue */
        $savedIssue = $this->callProjectIssueMigrateAll($request, $issue->getKey());

        /** @var IssueField $issueField */
        $issueField = $issue->getFields();
        $this->assertSame($issueField->getSummary(), $savedIssue->getSummary());
    }

    /**
     * @dataProvider getIssueToDeleteTestData
     */
    public function testProjectIssueDeletedEvent(
        Issue $issue,
        Request $request
    ): void {
        $existingIssue = $this->taskRepository->findOneBy(['externalKey' => $issue->getKey()]);

        self::assertNotNull($existingIssue);
        $savedIssue = $this->callProjectIssueMigrate($request, $issue->getKey());
        self::assertTrue($savedIssue->isDeleted());
    }

    /**
     * @dataProvider getSoftDeleteIssueTestData
     */
    public function testProjectIssueMigrationAllForSoftDeleteIssue(
        Issue $issue,
        Request $request
    ): void {
        /** @var Task $savedIssue */
        $savedIssue = $this->callProjectIssueMigrateAll($request, $issue->getKey());

        /** @var IssueField $issueField */
        $issueField = $issue->getFields();
        $this->assertNotSame($issueField->getSummary(), $savedIssue->getSummary());
    }

    /**
     * @dataProvider getIssuesWithLabels
     */
    public function testIssuesWithLabels(Issue $issue, Request $request): void
    {
        $this->setUpIssueApiReadRepositoryMock($issue);

        /** @var Task $savedIssue */
        $savedIssue = $this->callProjectIssueMigrateAll($request, $issue->getKey());

        /** @var IssueField $issueField */
        $issueField = $issue->getFields();

        $taskLabels = \array_map(static fn (Label $label): string => $label->getName(), $savedIssue->getLabels()->toArray());

        $this->assertEquals(\array_values(\array_unique($issueField->getLabels())), $taskLabels);
    }

    /**
     * @dataProvider getIssuesWithSoftDeletedLabels
     */
    public function testIssueWithSoftDeletedLabels(Issue $issue, Request $request): void
    {
        /** @var IssueField $issueField */
        $issueField = $issue->getFields();

        $this->setUpIssueApiReadRepositoryMock($issue);

        $deletedLabels = \array_filter(
            $this->labelRepository->findByNames($issueField->getLabels(), false),
            static fn (Label $label): bool => $label->isDeleted()
        );

        $this->assertNotEmpty($issueField->getLabels());
        $this->assertNotEmpty($deletedLabels);
        $this->assertCount(\count($issueField->getLabels()), $deletedLabels);

        /** @var Task $savedIssue */
        $savedIssue = $this->callProjectIssueMigrateAll($request, $issue->getKey());

        $this->assertCount(0, $savedIssue->getLabels());
    }

    /**
     * @dataProvider getIssueWithNewTaskType
     */
    public function testProjectIssueWithNewTaskType(
        Issue $issue,
        Request $request
    ): void {
        $this->expectException(\LogicException::class);
        $this->setUpIssueApiReadRepositoryMock($issue);

        /** @var Task $savedIssue */
        $savedIssue = $this->callProjectIssueMigrate($request, $issue->getKey());

        /** @var IssueField $issueField */
        $issueField = $issue->getFields();
        $this->assertSame($issueField->getSummary(), $savedIssue->getSummary());
    }

    /**
     * @dataProvider getIssueWithIncorrectTaskType
     */
    public function testProjectIssueWithIncorrectTaskType(
        Issue $issue,
        Request $request
    ): void {
        $this->expectException(\LogicException::class);
        $this->setUpIssueApiReadRepositoryMock($issue);

        /** @var Task $savedIssue */
        $savedIssue = $this->callProjectIssueMigrate($request, $issue->getKey());

        /** @var IssueField $issueField */
        $issueField = $issue->getFields();
        $this->assertSame($issueField->getSummary(), $savedIssue->getSummary());
    }

    /**
     * @dataProvider getIssueWithEstimationTestDataSp
     */
    public function testProjectIssueWithEstimationSp(Issue $issue, Request $request): void
    {
        $this->setUpIssueApiReadRepositoryMock(
            $issue,
            (object) [self::ESTIMATION_CUSTOM_FIELD_KEY => TaskEstimationMigrationStep::SP_ESTIMATE]
        );

        /** @var Task $savedIssue */
        $savedIssue = $this->callProjectIssueMigrate($request, $issue->getKey());

        $this->assertEquals((int) self::ESTIMATION_VALUE, $savedIssue->getEstimationSp());
    }

    /**
     * @dataProvider getIssueWithEstimationTestDataTime
     */
    public function testProjectIssueWithEstimationTime(Issue $issue, Request $request): void
    {
        $this->setUpIssueApiReadRepositoryMock($issue);

        /** @var Task $savedIssue */
        $savedIssue = $this->callProjectIssueMigrate($request, $issue->getKey());

        $this->assertEquals((int) self::ESTIMATION_VALUE, $savedIssue->getEstimationTime());
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function getIssueWithNewTaskType(): array
    {
        $reporter = $this->createReporter('sandip', 'test1-jira-account', 'true', 'test-dev@jagaad.it', true);
        $createIssue = $this->createIssueStatus('1', 'Todo');
        $createIssueType = $this->createIssueType('unexisting-task-type', 'unexisting-task-type', false, 'unexisting-task-type');
        $issueFieldForNew = $this->createIssueField($reporter, $createIssue, $createIssueType, 'test issue field new');

        return [
            'it will test task migrations for new issue' => [
                $this->createIssue('2', 'task5-handle', $issueFieldForNew),
                new Request([Request::KEYS_PARAM => ['task5-handle']]),
            ],
        ];
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function getIssueWithIncorrectTaskType(): array
    {
        $reporter = $this->createReporter('sandip', 'test1-jira-account', 'true', 'test-dev@jagaad.it', true);
        $createIssue = $this->createIssueStatus('1', 'Todo');
        $createIssueType = $this->createIssueType('tracker-task-type-name2', 'tracker-task-type-name2', false, 'test2-handle');
        $issueFieldForNew = $this->createIssueField($reporter, $createIssue, $createIssueType, 'test issue field new');

        return [
            'it will test task migrations for new issue' => [
                $this->createIssue('2', 'task5-handle', $issueFieldForNew),
                new Request([Request::KEYS_PARAM => ['task5-handle']]),
            ],
        ];
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function getActiveIssueTestData(): array
    {
        $reporter = $this->createReporter('sandip', 'test1-jira-account', 'true', 'integrations-dev@jagaad.it', true);
        $createIssue = $this->createIssueStatus('1', 'Todo');
        $createIssueType = $this->createIssueType('tracker-task-type-name1', 'tracker-task-type-name1', false, 'tracker-task-type-name1');
        $issueFieldForNew = $this->createIssueField($reporter, $createIssue, $createIssueType, 'test issue field new');
        $issueFieldForExisting = $this->createIssueField($reporter, $createIssue, $createIssueType, 'test issue field existing');

        return [
            'it will test task migrations for new issue' => [
                $this->createIssue('2', 'task5-handle', $issueFieldForNew),
                new Request([Request::KEYS_PARAM => ['task5-handle']]),
            ],

            'it will test task migrations for existing issue' => [
                $this->createIssue('2', 'task-4', $issueFieldForExisting),
                new Request([Request::KEYS_PARAM => ['task-4']]),
            ],
        ];
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function getIssuesWithLabels(): array
    {
        $reporter = $this->createReporter('user1', 'test1-jira-account', 'true', 'test-dev@jagaad.it', true);
        $createIssue = $this->createIssueStatus('1', 'Todo');
        $createIssueType = $this->createIssueType('tracker-task-type-name1', 'tracker-task-type-name1', false, 'tracker-task-type-name1');
        $issueFieldForNew = $this->createIssueField($reporter, $createIssue, $createIssueType, 'test issue field new', ['Label 5', 'Label 5', 'Label 6', 'Label 7']);
        $issueFieldForExisting = $this->createIssueField($reporter, $createIssue, $createIssueType, 'test issue field existing', ['Label 1', 'Label 1', 'Label 2']);

        return [
            'it will test task migrations for new issue with labels' => [
                $this->createIssue('2', 'task-5', $issueFieldForNew),
                new Request([Request::KEYS_PARAM => ['task-5']]),
            ],

            'it will test task migrations for existing issue with labels' => [
                $this->createIssue('2', 'task-4', $issueFieldForExisting),
                new Request([Request::KEYS_PARAM => ['task-4']]),
            ],
        ];
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function getIssuesWithSoftDeletedLabels(): array
    {
        $reporter = $this->createReporter('user1', 'test1-jira-account', 'true', 'test-dev@jagaad.it', true);
        $createIssue = $this->createIssueStatus('1', 'Todo');
        $createIssueType = $this->createIssueType('tracker-task-type-name1', 'tracker-task-type-name1', false, 'tracker-task-type-name1');
        $issueFieldForNew = $this->createIssueField($reporter, $createIssue, $createIssueType, 'test issue field new', ['Label 3']);
        $issueFieldForExisting = $this->createIssueField($reporter, $createIssue, $createIssueType, 'test issue field existing', ['Label 3']);

        return [
            'it will test task migrations for new issue with softdeleted labels' => [
                $this->createIssue('2', 'task-5', $issueFieldForNew),
                new Request([Request::KEYS_PARAM => ['task-5']]),
            ],

            'it will test task migrations for existing issue with with softdeleted labels' => [
                $this->createIssue('2', 'task-4', $issueFieldForExisting),
                new Request([Request::KEYS_PARAM => ['task-4']]),
            ],
        ];
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function getIssueToDeleteTestData(): array
    {
        $reporter = $this->createReporter('sandip', 'test1-jira-account', 'true', 'test-dev@jagaad.it', true);
        $createIssue = $this->createIssueStatus('1', 'Todo');
        $createIssueType = $this->createIssueType('tracker-task-type-name1', 'tracker-task-type-name1', false, 'tracker-task-type-name1');
        $issueFieldForExisting = $this->createIssueField($reporter, $createIssue, $createIssueType, 'test issue field existing');

        return [
            'it will test task migrations for deleting of existing issue' => [
                $this->createIssue('2', 'task-4', $issueFieldForExisting),
                new Request([Request::KEYS_PARAM => ['task-4'], 'action' => JiraTimeTrackerEvent::ISSUE_DELETED]),
            ],
        ];
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function getSoftDeleteIssueTestData(): array
    {
        $reporter = $this->createReporter('sandip', 'test1-jira-account', 'true', 'integrations-dev@jagaad.it', true);
        $createIssue = $this->createIssueStatus('1', 'Todo');
        $createIssueType = $this->createIssueType('tracker-task-type-name1', 'tracker-task-type-name1', false, 'tracker-task-type-name1');
        $issueField = $this->createIssueField($reporter, $createIssue, $createIssueType, 'test issue field for inactive');

        return [
            'it will test task migrations for existing inactive issue' => [
                $this->createIssue('2', 'task-3', $issueField),
                new Request([Request::KEYS_PARAM => ['task-3']]),
            ],
        ];
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function getIssueWithEstimationTestDataSp(): array
    {
        $reporter = $this->createReporter('sandip', 'test1-jira-account', 'true', 'integrations-dev@jagaad.it', true);
        $createIssue = $this->createIssueStatus('1', 'Todo');
        $createIssueType = $this->createIssueType('test2-handle', 'tracker-task-type-name2', false, 'test2-handle');

        $issueFieldStoryPoint = $this->createIssueField(
            $reporter,
            $createIssue,
            $createIssueType,
            'test issue field',
            [],
            self::EXISTING_PROJECT_FEATURE,
            [self::ESTIMATION_CUSTOM_FIELD_KEY => self::ESTIMATION_VALUE],
        );

        return [
                'it will test task migrations for existing issue with service point estimation' => [
                    $this->createIssue('2', 'task-2', $issueFieldStoryPoint),
                    new Request([Request::KEYS_PARAM => ['task-2']]),
                ],
        ];
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function getIssueWithEstimationTestDataTime(): array
    {
        $reporter = $this->createReporter('sandip', 'test1-jira-account', 'true', 'integrations-dev@jagaad.it', true);
        $createIssue = $this->createIssueStatus('1', 'Todo');
        $createIssueType = $this->createIssueType('test2-handle', 'tracker-task-type-name2', false, 'test2-handle');

        $issueFieldTime = $this->createIssueField(
            $reporter,
            $createIssue,
            $createIssueType,
            'test issue field',
            [],
            self::EXISTING_PROJECT_FEATURE,
            [],
            self::ESTIMATION_VALUE
        );

        return [
            'it will test task migrations for existing issue with time estimation' => [
                $this->createIssue('2', 'task-2', $issueFieldTime),
                new Request([Request::KEYS_PARAM => ['task-2']]),
            ],
        ];
    }

    private function setUpIssueApiReadRepositoryMock(Issue $issue, ?object $fieldNames = null): void
    {
        $issueSearchResult1 = (new IssueSearchResult())
            ->setMaxResults(20)
            ->setStartAt(0)
            ->setTotal(1)
            ->setIssues([0 => $issue])
            ->setNames($fieldNames);

        $issueSearchResult2 = (new IssueSearchResult())
            ->setMaxResults(20)
            ->setStartAt(20)
            ->setTotal(1)
            ->setIssues([])
            ->setNames($fieldNames);

        $this->issueApiReadRepository->expects($this->atLeastOnce())
            ->method('getByJql')
            ->with($this->anything())
            ->willReturnOnConsecutiveCalls($issueSearchResult1, $issueSearchResult2)
        ;
    }

    private function createProjectIssueMigration(): ProjectIssueMigration
    {
        /** @var RendererInterface $renderer */
        $renderer = $this->renderer->reveal();
        /** @var LoggerInterface $logger */
        $logger = $this->logger->reveal();

        return new ProjectIssueMigration(
            $this->migrationFlowHandler,
            $this->witcherProjectRepository,
            $this->trackerTaskTypeRepository,
            $this->statusRepository,
            $this->priorityRepository,
            $this->estimationTypeRepository,
            $this->issueApiReadRepository,
            $this->taskRepository,
            $renderer,
            $logger
        );
    }

    private function createReporter(string $name, string $accountId, string $active, string $emailAddress, bool $wantUnassigned): Reporter
    {
        return (new Reporter())
            ->setName($name)
            ->setAccountId($accountId)
            ->setActive($active)
            ->setEmailAddress($emailAddress)
            ->setWantUnassigned($wantUnassigned);
    }

    private function createIssueStatus(string $id, string $name): IssueStatus
    {
        return (new IssueStatus())
            ->setId($id)
            ->setName($name);
    }

    private function createIssueType(string $id, string $name, bool $subtask, string $key): IssueType
    {
        return (new IssueType())->setId($id)
            ->setName($name)
            ->setSubtask($subtask)
            ->setKey($key);
    }

    /**
     * @param string[] $labels
     */
    private function createIssueField(
        Reporter $creator,
        IssueStatus $issueStatus,
        IssueType $issueType,
        string $summary,
        array $labels = [],
        string $projectName = self::EXISTING_PROJECT_BUG,
        ?array $customFields = null,
        ?float $timeEstimation = null
    ): IssueField {
        return (new IssueField())
            ->setSummary($summary)
            ->setLabels($labels)
            ->setStatus($issueStatus)
            ->setCreator($creator)
            ->setIssueType($issueType)
            ->setCreated(new \DateTimeImmutable())
            ->setProject($this->createProjectResponse($projectName))
            ->setCustomFields($customFields)
            ->setTimeOriginalEstimate($timeEstimation);
    }

    private function createProjectResponse(string $projectName): Project
    {
        $project = new Project();
        $project->setKey($projectName);

        return $project;
    }

    private function createIssue(string $id, string $key, IssueField $issueField): Issue
    {
        return (new Issue())
            ->setId($id)
            ->setKey($key)
            ->setFields($issueField);
    }

    private function callProjectIssueMigrate(Request $request, string $issueKey): ?Task
    {
        $projectIssueMigration = $this->createProjectIssueMigration();

        $projectIssueMigration->migrate($request);

        return $this->taskRepository->findOneByExternalKey($issueKey, false);
    }

    private function callProjectIssueMigrateAll(Request $request, string $issueKey): ?Task
    {
        $projectIssueMigration = $this->createProjectIssueMigration();

        $projectIssueMigration->migrateAll($request);

        return $this->taskRepository->findOneByExternalKey($issueKey, false);
    }
}
