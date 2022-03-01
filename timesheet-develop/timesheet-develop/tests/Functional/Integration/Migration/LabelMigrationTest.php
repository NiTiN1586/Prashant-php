<?php

declare(strict_types=1);

namespace App\Tests\Functional\Integration\Migration;

use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;
use Jagaad\WitcherApi\Integration\Application\DTO\Request;
use Jagaad\WitcherApi\Integration\Application\IssueTracker\Jira\Migration\LabelMigration;
use Jagaad\WitcherApi\Integration\Domain\IssueTracker\Label as LabelDTO;
use Jagaad\WitcherApi\Integration\Migration\Interfaces\MigrationInterface;
use Jagaad\WitcherApi\Integration\Repository\IssueTracker\LabelReadApiInterface;
use Jagaad\WitcherApi\Renderer\RendererInterface;
use Jagaad\WitcherApi\Repository\LabelRepository;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class LabelMigrationTest extends KernelTestCase
{
    use ProphecyTrait;
    use ReloadDatabaseTrait;

    private LoggerInterface|ObjectProphecy $logger;
    private RendererInterface|ObjectProphecy $renderer;

    private LabelRepository $labelRepository;

    private ValidatorInterface $validatorInterface;
    private LabelReadApiInterface|ObjectProphecy $labelReadApiRepository;

    protected function setUp(): void
    {
        self::bootKernel(['environment' => 'test']);

        $this->logger = $this->prophesize(LoggerInterface::class);
        $this->renderer = $this->prophesize(RendererInterface::class);
        $this->labelReadApiRepository = $this->prophesize(LabelReadApiInterface::class);

        $this->labelRepository = static::getContainer()->get(LabelRepository::class);
        $this->validatorInterface = static::getContainer()->get(ValidatorInterface::class);
    }

    /**
     * @dataProvider getLabelTests
     */
    public function testLabelMigrate(LabelDTO $labelDTO): void
    {
        $labelMigration = $this->createLabelMigration($labelDTO);

        $labelMigration->migrate(new Request());

        $labels = $this->labelRepository->findBy(['name' => $labelDTO->getValues()]);

        $this->assertCount(\count($labelDTO->getValues()), $labels);
    }

    /**
     * @dataProvider getLabelWithDuplicatesTests
     */
    public function testDuplicatedMigrationSavedOnce(LabelDTO $label): void
    {
        $labelMigration = $this->createLabelMigration($label);
        $labelNames = \array_unique($label->getValues());

        $this->assertCount(3, $this->labelRepository->findLabelAsStringListByNames($labelNames, false));

        $labelMigration->migrateAll(new Request());

        $this->assertCount(4, $this->labelRepository->findLabelAsStringListByNames($labelNames, false));
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function getLabelTests(): array
    {
        return [
            'it will test new label migration' => [
                $this->createLabelDTO(2, ['Label 5', 'Label 4']),
            ],
            'it will test existing label migration' => [
                $this->createLabelDTO(2, ['Label 2', 'Label 1']),
            ],
        ];
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function getLabelWithDuplicatesTests(): array
    {
        return [
            'it will test label list with duplicates migration' => [
                $this->createLabelDTO(6, ['Label 1', 'Label 2', 'Label 2', 'Label 3', 'Label 3', 'Label 4']),
            ],
        ];
    }

    /**
     * @param string[] $values
     */
    private function createLabelDTO(int $total, array $values, bool $last = true, int $maxResults = MigrationInterface::BATCH_SIZE): LabelDTO
    {
        return (new LabelDTO())->setMaxResults($maxResults)
            ->setStartAt(0)
            ->setIsLast($last)
            ->setTotal($total)
            ->setValues($values);
    }

    private function createLabelMigration(LabelDTO $label): LabelMigration
    {
        $this->labelReadApiRepository->getAllPaginated(Argument::cetera())->shouldBeCalled()->willReturn($label);

        /** @var LabelReadApiInterface $labelReadApiRepo */
        $labelReadApiRepo = $this->labelReadApiRepository->reveal();

        /** @var LoggerInterface $logger */
        $logger = $this->logger->reveal();

        /** @var RendererInterface $renderer */
        $renderer = $this->renderer->reveal();

        return new LabelMigration(
            $labelReadApiRepo,
            $this->labelRepository,
            $logger,
            $renderer,
            $this->validatorInterface
        );
    }
}
