<?php

declare(strict_types=1);

namespace App\Tests\Functional\Integration\Migration;

use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;
use Jagaad\WitcherApi\Entity\TrackerTaskType;
use Jagaad\WitcherApi\Integration\Application\DTO\Request;
use Jagaad\WitcherApi\Integration\Application\IssueTracker\Jira\Migration\ProjectIssueTypeMigration;
use Jagaad\WitcherApi\Integration\Domain\IssueTracker\IssueType;
use Jagaad\WitcherApi\Integration\Repository\IssueTracker\IssueTypeReadApiInterface;
use Jagaad\WitcherApi\Renderer\RendererInterface;
use Jagaad\WitcherApi\Repository\TrackerTaskTypeRepository;
use PHPUnit\Framework\MockObject\MockObject;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class ProjectIssueTypeMigrationTest extends KernelTestCase
{
    use ProphecyTrait;
    use ReloadDatabaseTrait;

    private LoggerInterface|ObjectProphecy $logger;
    private RendererInterface|ObjectProphecy $renderer;
    private IssueTypeReadApiInterface|MockObject $issueTypeApiReadRepository;

    private TrackerTaskTypeRepository $trackerTaskTypeRepository;

    protected function setUp(): void
    {
        self::bootKernel(['environment' => 'test']);

        $this->logger = $this->prophesize(LoggerInterface::class);
        $this->renderer = $this->prophesize(RendererInterface::class);
        $this->issueTypeApiReadRepository = $this->createMock(IssueTypeReadApiInterface::class);
        $this->trackerTaskTypeRepository = static::getContainer()->get(TrackerTaskTypeRepository::class);
    }

    /**
     * @dataProvider getIssueTypeData
     *
     * @param IssueType[] $issueTypes
     */
    public function testProjectIssueTypeMigrationAll(
        array $issueTypes,
        Request $request
    ): void {
        $this->setUpIssueTypeApiReadRepositoryMock($issueTypes);

        /** @var ProjectIssueTypeMigration $savedIssue */
        $issueTypeMigration = $this->createIssueTypeMigration();
        $issueTypeMigration->migrateAll($request);
        $issueTypeNames = \array_map(static fn (IssueType $issueType): string => $issueType->getName(), $issueTypes);

        $savedIssueTypes = $this->trackerTaskTypeRepository->findBy(['friendlyName' => $issueTypeNames]);
        $savedIssueTypeNames = \array_map(static fn (TrackerTaskType $taskType): string => $taskType->getFriendlyName(), $savedIssueTypes);

        $this->assertEquals($issueTypeNames, $savedIssueTypeNames);
    }

    /**
     * @dataProvider getIssueTypeData
     *
     * @param IssueType[] $issueTypes
     */
    public function testProjectIssueTypeMigration(
        array $issueTypes,
        Request $request
    ): void {
        $this->setUpIssueTypeApiReadRepositoryMock($issueTypes);

        /** @var ProjectIssueTypeMigration $savedIssue */
        $issueTypeMigration = $this->createIssueTypeMigration();
        $issueTypeMigration->migrate($request);
        $issueTypeNames = \array_map(static fn (IssueType $issueType): string => $issueType->getName(), $issueTypes);

        $savedIssueTypes = $this->trackerTaskTypeRepository->findBy(['friendlyName' => $issueTypeNames]);
        $savedIssueTypeNames = \array_map(static fn (TrackerTaskType $taskType): string => $taskType->getFriendlyName(), $savedIssueTypes);

        $this->assertEquals($issueTypeNames, $savedIssueTypeNames);
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function getIssueTypeData(): array
    {
        return [
            'it will test new issue types will be created' => [
                [
                    $this->createIssueType('1', 'epic', 'epic'),
                    $this->createIssueType('2', 'sub-task', 'sub-task'),
                ],
                new Request(),
            ],
            'it will test old and new issue types in 1 response' => [
                [
                    $this->createIssueType('1', 'tracker-task-type-name1', 'tracker-task-type-name1'),
                    $this->createIssueType('2', 'sub-task', 'sub-task'),
                ],
                new Request(),
            ],
            'it will test only existing issue types' => [
                [
                    $this->createIssueType('1', 'tracker-task-type-name1', 'tracker-task-type-name1'),
                    $this->createIssueType('2', 'tracker-task-type-name2', 'tracker-task-type-name2'),
                ],
                new Request(),
            ],
        ];
    }

    /**
     * @param IssueType[] $issueTypes
     */
    private function setUpIssueTypeApiReadRepositoryMock(array $issueTypes): void
    {
        $this->issueTypeApiReadRepository
            ->method('findAll')
            ->willReturnOnConsecutiveCalls($issueTypes);
    }

    private function createIssueTypeMigration(): ProjectIssueTypeMigration
    {
        return new ProjectIssueTypeMigration(
            $this->issueTypeApiReadRepository,
            $this->trackerTaskTypeRepository,
            $this->logger->reveal(),
            $this->renderer->reveal()
        );
    }

    private function createIssueType(string $id, string $name, string $key, bool $subTask = false): IssueType
    {
        return (new IssueType())
            ->setId($id)
            ->setName($name)
            ->setSubtask($subTask)
            ->setKey($key);
    }
}
