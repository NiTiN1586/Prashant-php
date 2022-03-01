<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Integration\Migration;

use Jagaad\WitcherApi\Integration\Migration\Interfaces\MigrationInterface;
use Jagaad\WitcherApi\Integration\Migration\Interfaces\MigrationRegistryInterface;

class MigrationRegistry implements MigrationRegistryInterface
{
    /** @var array<string, MigrationInterface> */
    private array $migrations = [];

    /**
     * @param iterable<MigrationInterface> $migrations
     */
    public function __construct(iterable $migrations)
    {
        foreach ($migrations as $migration) {
            $this->migrations[$migration->getAlias()] = $migration;
        }
    }

    public function getMigration(string $migrationType): MigrationInterface
    {
        if (!isset($this->migrations[$migrationType])) {
            throw new \InvalidArgumentException(\sprintf('%s migration doesn\'t exist', $migrationType));
        }

        return $this->migrations[$migrationType];
    }

    /**
     * {@inheritdoc}
     */
    public function getAll(): array
    {
        return $this->migrations;
    }
}
