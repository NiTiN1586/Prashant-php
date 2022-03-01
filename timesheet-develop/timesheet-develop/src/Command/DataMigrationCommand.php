<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Command;

use Assert\Assertion;
use Jagaad\WitcherApi\Integration\Application\DTO\Request;
use Jagaad\WitcherApi\Integration\Migration\Interfaces\MigrationRegistryInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

final class DataMigrationCommand extends Command
{
    private const BATCH_SIZE = 50;

    private const OPTION_TYPE = 'type';
    private const OPTION_HANDLE = 'handle';
    private const TYPE_ALL = 'all';

    private MigrationRegistryInterface $migrationRegistry;

    protected static $defaultName = 'witcher:migration';

    public function __construct(MigrationRegistryInterface $migrationRegistry)
    {
        parent::__construct();

        $this->migrationRegistry = $migrationRegistry;
    }

    protected function configure(): void
    {
        $this->addOption(
            self::OPTION_TYPE,
            null,
            InputOption::VALUE_REQUIRED,
            'Migration Type'
        )->addOption(
            self::OPTION_HANDLE,
            null,
            InputOption::VALUE_OPTIONAL,
            \sprintf('key handles, comma separated (Maximum %d items)', self::BATCH_SIZE)
        )
            ->setDescription('Invokes Jira and Gitlab migrations')
            ->setHelp(<<<EOT
                You can invoke separate migration by using <comment>--type</comment> option:
                <info>%command.full_name% --type=all</info> - to invoke all migrations

                You can define additional option for <info>project</info> and <info>issue</info> migrations <comment>--handle</comment> option to invoke migration for particular project/issue:
                <info>%command.full_name% --type=project --handle="TASK-1"</info>
            EOT);
    }

    /**
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\Persistence\Mapping\MappingException
     * @throws \Doctrine\ORM\ORMException
     * @throws \InvalidArgumentException|\Assert\AssertionFailedException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $type = $input->getOption(self::OPTION_TYPE);
        $handles = $input->getOption(self::OPTION_HANDLE);
        $outputStyle = new SymfonyStyle($input, $output);

        Assertion::string($type, \sprintf('--%s is required', self::OPTION_TYPE));
        Assertion::nullOrString($handles, \sprintf('--%s has incorrect type', self::OPTION_HANDLE));

        if (self::TYPE_ALL === $type) {
            foreach ($this->migrationRegistry->getAll() as $migration) {
                try {
                    $outputStyle->warning(\sprintf('Migrating %s...', $migration->getAlias()));
                    $migration->migrateAll(new Request());
                } catch (\Throwable $exception) {
                    $outputStyle->error($exception->getMessage());
                }
            }

            return Command::SUCCESS;
        }

        /** @var string|null $handles */
        $handleList = null !== $handles
            ? \array_slice(\explode(',', $handles), 0, self::BATCH_SIZE)
            : [];

        /** @var string $type */
        $migration = $this->migrationRegistry->getMigration($type);

        if (0 === \count($handleList)) {
            $migration->migrateAll(new Request());

            return Command::SUCCESS;
        }

        $migration->migrate(new Request([Request::KEYS_PARAM => $handleList]));

        return Command::SUCCESS;
    }
}
