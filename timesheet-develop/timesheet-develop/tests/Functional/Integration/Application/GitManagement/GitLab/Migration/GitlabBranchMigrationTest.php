<?php

declare(strict_types=1);

namespace App\Tests\Functional\Integration\Application\GitManagement\GitLab\Migration;

use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;
use Jagaad\WitcherApi\Integration\Application\DTO\Request;
use Jagaad\WitcherApi\Integration\Application\Exception\IntegrationException;
use Jagaad\WitcherApi\Integration\Application\GitManagement\GitLab\Migration\GitlabBranchMigration;
use Jagaad\WitcherApi\Integration\Domain\GitManagement\Branch;
use Jagaad\WitcherApi\Integration\Domain\GitManagement\Message\GitEvent;
use Jagaad\WitcherApi\Integration\Repository\GitManagement\ProjectBranchReadApiInterface;
use Jagaad\WitcherApi\Renderer\RendererInterface;
use Jagaad\WitcherApi\Repository\GitBranchRepository;
use Jagaad\WitcherApi\Repository\GitProjectRepository;
use Jagaad\WitcherApi\Repository\TaskRepository;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class GitlabBranchMigrationTest extends KernelTestCase
{
    use ProphecyTrait;
    use ReloadDatabaseTrait;

    private ProjectBranchReadApiInterface|ObjectProphecy $projectBranchRepository;
    private LoggerInterface|ObjectProphecy $logger;
    private RendererInterface|ObjectProphecy $renderer;

    private TaskRepository $taskRepository;
    private GitBranchRepository $gitBranchRepository;
    private GitProjectRepository $gitProjectRepository;
    private ValidatorInterface $validator;

    protected function setUp(): void
    {
        self::bootKernel(['environment' => 'test']);

        $container = static::getContainer();

        $this->logger = $this->prophesize(LoggerInterface::class);
        $this->renderer = $this->prophesize(RendererInterface::class);
        $this->projectBranchRepository = $this->createMock(ProjectBranchReadApiInterface::class);

        $this->taskRepository = $container->get(TaskRepository::class);
        $this->gitBranchRepository = $container->get(GitBranchRepository::class);
        $this->gitProjectRepository = $container->get(GitProjectRepository::class);
        $this->validator = $container->get(ValidatorInterface::class);
    }

    /**
     * @dataProvider getDeletionOfExistingBranchData
     */
    public function testBranchDeletion(Branch $branch, Request $request): void
    {
        $this->assertNotNull($this->gitBranchRepository->findOneBy(['branchName' => $branch->getName()]));
        $migration = $this->createMigration([$branch]);
        $migration->migrate($request);

        $this->assertNull($this->gitBranchRepository->findOneBy(['branchName' => $branch->getName()]));
    }

    /**
     * @dataProvider getDeletedBranchRecoveryData
     */
    public function testDeletedBranchRecovery(Branch $branch, Request $request): void
    {
        $this->assertNull($this->gitBranchRepository->findOneBy(['branchName' => $branch->getName()]));
        $migration = $this->createMigration([$branch]);
        $migration->migrate($request);

        $this->assertNotNull($this->gitBranchRepository->findOneBy(['branchName' => $branch->getName()]));
    }

    /**
     * @dataProvider getNewBranchCreatedData
     */
    public function testNewBranchCreated(Branch $branch, Request $request): void
    {
        $this->assertNull($this->gitBranchRepository->findOneBy(['branchName' => $branch->getName()]));
        $migration = $this->createMigration([$branch]);
        $migration->migrate($request);

        $this->assertNotNull($this->gitBranchRepository->findOneBy(['branchName' => $branch->getName()]));
    }

    /**
     * @dataProvider getInvalidBranchData
     */
    public function testInvalidBranchCreationAttempt(Branch $branch, Request $request): void
    {
        $this->expectException(IntegrationException::class);
        $this->assertNull($this->gitBranchRepository->findOneBy(['branchName' => $branch->getName()]));
        $migration = $this->createMigration([$branch]);
        $migration->migrate($request);

        $this->assertNotNull($this->gitBranchRepository->findOneBy(['branchName' => $branch->getName()]));
    }

    /**
     * @dataProvider getMigrateAllData
     *
     * @param Branch[] $branches
     */
    public function testMigrateAll(array $branches, Request $request): void
    {
        $branchNames = \array_map(static fn (Branch $branch): string => $branch->getName(), $branches);
        $this->assertCount(0, $this->gitBranchRepository->findBy(['branchName' => $branchNames]));

        $migration = $this->createMigration($branches, true);
        $migration->migrateAll($request);

        $this->assertCount(\count($branches), $this->gitBranchRepository->findBy(['branchName' => $branchNames]));
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function getMigrateAllData(): array
    {
        return [
            'it will test list of git branches' => [
                [
                    $this->createBranch('task-1-branch-test-one', 'http://task-1-branch-test-one'),
                    $this->createBranch('task-1-branch-test-two', 'http://task-1-branch-test-two'),
                    $this->createBranch('task-1-branch-test-three', 'http://task-1-branch-test-three'),
                    $this->createBranch('task-1-branch-test-four', 'http://task-1-branch-test-four'),
                ],
                new Request(),
            ],
        ];
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function getInvalidBranchData(): array
    {
        return [
            'it will test git branch with unexisted project' => [
                $this->createBranch('task-2-branch', 'http://task-2-branch'),
                new Request(['project' => 456, 'branch' => 'task-2-branch', 'action' => GitEvent::BRANCH_CREATED]),
            ],
            'it will test git branch has incorrect format' => [
                $this->createBranch('task incorrect branch', 'http://task-2-branch'),
                new Request(['project' => 123, 'branch' => 'task incorrect branch', 'action' => GitEvent::BRANCH_CREATED]),
            ],
            'it will test git branch with unexisted task' => [
                $this->createBranch('task-10-branch', 'http://task-2-branch'),
                new Request(['project' => 123, 'branch' => 'task-10-branch', 'action' => GitEvent::BRANCH_CREATED]),
            ],
        ];
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function getNewBranchCreatedData(): array
    {
        return [
            'it will test new git branch created' => [
                $this->createBranch('task-2-branch', 'http://task-2-branch'),
                new Request(['project' => 123, 'branch' => 'task-2-branch', 'action' => GitEvent::BRANCH_CREATED]),
            ],
        ];
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function getDeletionOfExistingBranchData(): array
    {
        return [
            'it will test existing git branch deletion' => [
                $this->createBranch('branch-1', 'http://branch_1'),
                new Request(['project' => 123, 'branch' => 'branch-1', 'action' => GitEvent::BRANCH_DELETED]),
            ],
        ];
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function getDeletedBranchRecoveryData(): array
    {
        return [
            'it will test existing git branch recovered after deletion' => [
                $this->createBranch('branch-2', 'http://branch_2'),
                new Request(['project' => 321, 'branch' => 'branch-2', 'action' => GitEvent::BRANCH_CREATED]),
            ],
        ];
    }

    private function createBranch(string $name, string $url, bool $merged = false): Branch
    {
        return (new Branch())
            ->setName($name)
            ->setWebUrl($url)
            ->setMerged($merged);
    }

    /**
     * @param array<int, Branch> $branches
     */
    private function createMigration(array $branches, bool $withConsecutive = false): GitlabBranchMigration
    {
        if ($withConsecutive) {
            $this->projectBranchRepository->expects($this->exactly(3))
                ->method('findGitlabProjectBranchesByHandle')
                ->with($this->anything())
                ->willReturnOnConsecutiveCalls($branches, []);
        } else {
            $this->projectBranchRepository
                ->method('findGitlabProjectBranchesByHandle')
                ->with($this->anything())
                ->willReturn($branches);
        }

        /** @var LoggerInterface $logger */
        $logger = $this->logger->reveal();

        /** @var RendererInterface $renderer */
        $renderer = $this->renderer->reveal();

        return new GitlabBranchMigration(
            $this->projectBranchRepository,
            $logger,
            $this->taskRepository,
            $this->gitBranchRepository,
            $this->gitProjectRepository,
            $this->validator,
            $renderer
        );
    }
}
