<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Integration\Migration\Interfaces;

interface MigrationRegistryInterface
{
    public function getMigration(string $migrationType): MigrationInterface;

    /**
     * @return MigrationInterface[]
     */
    public function getAll(): array;
}
