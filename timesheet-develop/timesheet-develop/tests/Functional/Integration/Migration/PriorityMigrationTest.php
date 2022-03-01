<?php

declare(strict_types=1);

namespace App\Tests\Functional\Integration\Migration;

use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;
use Jagaad\WitcherApi\Integration\Application\DTO\Request;
use Jagaad\WitcherApi\Integration\Application\IssueTracker\Jira\Migration\PriorityMigration;
use Jagaad\WitcherApi\Integration\Domain\IssueTracker\Priority as PriorityDTO;
use Jagaad\WitcherApi\Integration\Repository\IssueTracker\PriorityReadApiInterface;
use Jagaad\WitcherApi\Renderer\RendererInterface;
use Jagaad\WitcherApi\Repository\PriorityRepository;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PriorityMigrationTest extends KernelTestCase
{
    use ProphecyTrait;
    use ReloadDatabaseTrait;

    private LoggerInterface|ObjectProphecy $logger;
    private RendererInterface|ObjectProphecy $renderer;
    private PriorityRepository $priorityRepository;
    private PriorityReadApiInterface|ObjectProphecy $priorityReadApiRepository;

    protected function setUp(): void
    {
        self::bootKernel(['environment' => 'test']);

        $this->logger = $this->prophesize(LoggerInterface::class);
        $this->renderer = $this->prophesize(RendererInterface::class);
        $this->priorityReadApiRepository = $this->prophesize(PriorityReadApiInterface::class);

        $this->priorityRepository = static::getContainer()->get(PriorityRepository::class);
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function getPriorityTests(): array
    {
        return [
            'it will test new priority migration' => [
                $this->createPriorityDTO('1', 'testNewPriority', 'test desc', '#ffffff'),
            ],
            'it will test existing priority migration' => [
                $this->createPriorityDTO('1', 'priority1', 'testing data', '#ffffff'),
            ],
        ];
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function getDuplicatedPriorityTests(): array
    {
        return [
            'it will test duplicated priority migration' => [
                [
                    $this->createPriorityDTO('1', 'name duplicate', 'test duplicate description', '#ffffff'),
                    $this->createPriorityDTO('2', 'name duplicate', 'test duplicate description', '#ffffff'),
                ],
            ],
        ];
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function getPriorityListTests(): array
    {
        return [
            'it will test duplicated priority migration' => [
                [
                    $this->createPriorityDTO('1', 'name1', 'test description', '#ffffff'),
                    $this->createPriorityDTO('2', 'name2', 'test description2', '#ffffff'),
                ],
            ],
        ];
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function getSoftDeletePriorityTestData(): array
    {
        return [
            'it will test existing inactive priority migration' => [
                $this->createPriorityDTO('3', 'priority3', 'test desc', '#ffffff'),
            ],
        ];
    }

    /**
     * @dataProvider getPriorityTests
     */
    public function testPriorityMigrate(PriorityDTO $priorityDTO): void
    {
        $priorityMigration = $this->createPriorityMigration($priorityDTO);

        $priorityMigration->migrate(new Request());

        $priority = $this->priorityRepository->findOneBy(['friendlyName' => $priorityDTO->getName()]);

        $this->assertSame($priorityDTO->getName(), $priority->getFriendlyName());
    }

    /**
     * @dataProvider getDuplicatedPriorityTests
     *
     * @param PriorityDTO[] $priorities
     */
    public function testDuplicatedMigrationSavedOnce(array $priorities): void
    {
        $priorityMigration = $this->createPriorityListMigration($priorities);

        $priorityNames = \array_filter(
            \array_map(static fn (PriorityDTO $dto): string => $dto->getName(), $priorities)
        );

        $this->assertCount(0, $this->priorityRepository->findByNames($priorityNames));

        $priorityMigration->migrateAll(new Request());

        $this->assertCount(1, $this->priorityRepository->findByNames($priorityNames));
    }

    /**
     * @dataProvider getPriorityListTests
     *
     * @param PriorityDTO[] $priorities
     */
    public function testMigrationList(array $priorities): void
    {
        $priorityMigration = $this->createPriorityListMigration($priorities);
        $priorityNames = \array_map(static fn (PriorityDTO $dto): string => $dto->getName(), $priorities);
        $this->assertCount(0, $this->priorityRepository->findByNames($priorityNames));

        $priorityMigration->migrateAll(new Request());

        $this->assertCount(2, $this->priorityRepository->findByNames($priorityNames));
    }

    /**
     * @dataProvider getSoftDeletePriorityTestData
     */
    public function testSoftDeletePriorityMigrate(PriorityDTO $priorityDTO): void
    {
        $priorityMigration = $this->createPriorityMigration($priorityDTO);

        $priorityMigration->migrate(new Request());

        $priorities = $this->priorityRepository->findByNames([$priorityDTO->getName()], false);

        $this->assertCount(1, $priorities);

        $this->assertNotSame($priorityDTO->getDescription(), $priorities[0]->getDescription());
    }

    /**
     * @dataProvider getPriorityTests
     */
    public function testPriorityMigrateAll(PriorityDTO $priorityDTO): void
    {
        $priorityMigration = $this->createPriorityMigration($priorityDTO);

        $priorityMigration->migrateAll(new Request());

        $priority = $this->priorityRepository->findOneBy(['friendlyName' => $priorityDTO->getName()]);

        $this->assertSame($priorityDTO->getName(), $priority->getFriendlyName());
    }

    /**
     * @dataProvider getSoftDeletePriorityTestData
     */
    public function testSoftDeletePriorityMigrateAll(PriorityDTO $priorityDTO): void
    {
        $priorityMigration = $this->createPriorityMigration($priorityDTO);

        $priorityMigration->migrateAll(new Request());

        $priorities = $this->priorityRepository->findByNames([$priorityDTO->getName()], false);

        $this->assertCount(1, $priorities);
        $this->assertNotSame($priorityDTO->getDescription(), $priorities[0]->getDescription());
    }

    private function createPriorityMigration(PriorityDTO $priorityDTO): PriorityMigration
    {
        $this->priorityReadApiRepository->getAll()->shouldBeCalled()->willReturn([1 => $priorityDTO]);

        /** @var PriorityReadApiInterface $priorityReadApiRepo */
        $priorityReadApiRepo = $this->priorityReadApiRepository->reveal();

        /** @var LoggerInterface $logger */
        $logger = $this->logger->reveal();

        /** @var RendererInterface $renderer */
        $renderer = $this->renderer->reveal();

        return new PriorityMigration(
            $priorityReadApiRepo,
            $this->priorityRepository,
            $logger,
            $renderer
        );
    }

    /**
     * @param PriorityDTO[] $priorities
     */
    private function createPriorityListMigration(array $priorities): PriorityMigration
    {
        $this->priorityReadApiRepository->getAll()->shouldBeCalled()->willReturn($priorities);

        /** @var PriorityReadApiInterface $priorityReadApiRepo */
        $priorityReadApiRepo = $this->priorityReadApiRepository->reveal();

        /** @var LoggerInterface $logger */
        $logger = $this->logger->reveal();

        /** @var RendererInterface $renderer */
        $renderer = $this->renderer->reveal();

        return new PriorityMigration(
            $priorityReadApiRepo,
            $this->priorityRepository,
            $logger,
            $renderer
        );
    }

    private function createPriorityDTO(string $id, string $name, string $description, string $statusColor): PriorityDTO
    {
        return (new PriorityDTO())->setId($id)
            ->setName($name)
            ->setStatusColor($statusColor)
            ->setDescription($description);
    }
}
