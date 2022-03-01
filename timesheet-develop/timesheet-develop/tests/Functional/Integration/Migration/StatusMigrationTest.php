<?php

declare(strict_types=1);

namespace App\Tests\Functional\Integration\Migration;

use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;
use Jagaad\WitcherApi\Integration\Application\DTO\Request;
use Jagaad\WitcherApi\Integration\Application\IssueTracker\Jira\Migration\StatusMigration;
use Jagaad\WitcherApi\Integration\Domain\IssueTracker\Status as StatusDTO;
use Jagaad\WitcherApi\Integration\Repository\IssueTracker\StatusReadApiInterface;
use Jagaad\WitcherApi\Renderer\RendererInterface;
use Jagaad\WitcherApi\Repository\StatusRepository;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class StatusMigrationTest extends KernelTestCase
{
    use ProphecyTrait;
    use ReloadDatabaseTrait;

    private LoggerInterface|ObjectProphecy $logger;
    private RendererInterface|ObjectProphecy $renderer;

    private StatusRepository $statusRepository;
    private StatusReadApiInterface|ObjectProphecy $statusReadApiRepository;

    protected function setUp(): void
    {
        self::bootKernel(['environment' => 'test']);

        $this->logger = $this->prophesize(LoggerInterface::class);
        $this->renderer = $this->prophesize(RendererInterface::class);
        $this->statusReadApiRepository = $this->prophesize(StatusReadApiInterface::class);

        $this->statusRepository = static::getContainer()->get(StatusRepository::class);
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function getStatusTests(): array
    {
        return [
            'it will test new status migration' => [
                $this->createStatusDTO(1, 'testNewStatus', 'test desc'),
            ],
            'it will test existing status migration' => [
                $this->createStatusDTO(1, 'test1 active status', 'testing data'),
            ],
        ];
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function getDuplicatedStatusTests(): array
    {
        return [
            'it will test duplicated status migration' => [
                [
                    $this->createStatusDTO(1, 'name duplicate', 'test duplicate description'),
                    $this->createStatusDTO(2, 'name duplicate', 'test duplicate description'),
                ],
            ],
        ];
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function getStatusListTests(): array
    {
        return [
            'it will test duplicated status migration' => [
                [
                    $this->createStatusDTO(1, 'name1', 'test description'),
                    $this->createStatusDTO(2, 'name2', 'test description2'),
                ],
            ],
        ];
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function getSoftDeleteStatusTestData(): array
    {
        return [
            'it will test existing inactive status migration' => [
                $this->createStatusDTO(3, 'inprogress', 'test desc'),
            ],
        ];
    }

    /**
     * @dataProvider getStatusTests
     */
    public function testStatusMigrate(StatusDTO $statusDTO): void
    {
        $statusyMigration = $this->createStatusMigration($statusDTO);

        $statusyMigration->migrate(new Request());

        $status = $this->statusRepository->findOneBy(['friendlyName' => $statusDTO->getName()]);

        $this->assertSame($statusDTO->getName(), $status->getFriendlyName());
    }

    /**
     * @dataProvider getDuplicatedStatusTests
     *
     * @param StatusDTO[] $statuses
     */
    public function testDuplicatedMigrationSavedOnce(array $statuses): void
    {
        $statusMigration = $this->createStatusListMigration($statuses);
        $statusNames = \array_filter(
            \array_map(static fn (StatusDTO $dto): string => $dto->getName(), $statuses)
        );

        $this->assertCount(0, $this->statusRepository->findByNames($statusNames));

        $statusMigration->migrateAll(new Request());

        $this->assertCount(1, $this->statusRepository->findByNames($statusNames));
    }

    /**
     * @dataProvider getStatusListTests
     *
     * @param StatusDTO[] $statuses
     */
    public function testMigrationList(array $statuses): void
    {
        $statusMigration = $this->createStatusListMigration($statuses);
        $statusNames = \array_map(static fn (StatusDTO $dto): string => $dto->getName(), $statuses);
        $this->assertCount(0, $this->statusRepository->findByNames($statusNames));

        $statusMigration->migrateAll(new Request());

        $this->assertCount(2, $this->statusRepository->findByNames($statusNames));
    }

    /**
     * @dataProvider getSoftDeleteStatusTestData
     */
    public function testSoftDeleteStatusMigrate(StatusDTO $statusDTO): void
    {
        $statusMigration = $this->createStatusMigration($statusDTO);

        $statusMigration->migrate(new Request());

        $statuses = $this->statusRepository->findByNames([$statusDTO->getName()], false);

        $this->assertCount(1, $statuses);

        $this->assertNotSame($statusDTO->getDescription(), $statuses[0]->getDescription());
    }

    /**
     * @dataProvider getStatusTests
     */
    public function testStatusMigrateAll(StatusDTO $statusDTO): void
    {
        $statusMigration = $this->createStatusMigration($statusDTO);

        $statusMigration->migrateAll(new Request());

        $status = $this->statusRepository->findOneBy(['friendlyName' => $statusDTO->getName()]);

        $this->assertSame($statusDTO->getName(), $status->getFriendlyName());
    }

    /**
     * @dataProvider getSoftDeleteStatusTestData
     */
    public function testSoftDeleteStatusMigrateAll(StatusDTO $statusDTO): void
    {
        $statusMigration = $this->createStatusMigration($statusDTO);

        $statusMigration->migrateAll(new Request());

        $statuses = $this->statusRepository->findByNames([$statusDTO->getName()], false);

        $this->assertCount(1, $statuses);
        $this->assertNotSame($statusDTO->getDescription(), $statuses[0]->getDescription());
    }

    private function createStatusMigration(StatusDTO $status): StatusMigration
    {
        $this->statusReadApiRepository->getAll()->shouldBeCalled()->willReturn([1 => $status]);

        /** @var StatusReadApiInterface $statusReadApiRepository */
        $statusReadApiRepository = $this->statusReadApiRepository->reveal();

        /** @var LoggerInterface $logger */
        $logger = $this->logger->reveal();

        /** @var RendererInterface $renderer */
        $renderer = $this->renderer->reveal();

        return new StatusMigration(
            $statusReadApiRepository,
            $this->statusRepository,
            $logger,
            $renderer
        );
    }

    private function createStatusDTO(int $id, string $name, string $description): StatusDTO
    {
        return (new StatusDTO())->setId($id)
            ->setName($name)
            ->setDescription($description);
    }

    /**
     * @param StatusDTO[] $statuses
     */
    private function createStatusListMigration(array $statuses): StatusMigration
    {
        $this->statusReadApiRepository->getAll()->shouldBeCalled()->willReturn($statuses);

        /** @var StatusReadApiInterface $statusReadApiRepo */
        $statusReadApiRepo = $this->statusReadApiRepository->reveal();

        /** @var LoggerInterface $logger */
        $logger = $this->logger->reveal();

        /** @var RendererInterface $renderer */
        $renderer = $this->renderer->reveal();

        return new StatusMigration(
            $statusReadApiRepo,
            $this->statusRepository,
            $logger,
            $renderer
        );
    }
}
