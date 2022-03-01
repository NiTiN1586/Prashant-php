<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Integration\Migration\Interfaces;

use Jagaad\WitcherApi\Entity\Task;
use Jagaad\WitcherApi\Integration\Application\DTO\Request;

interface HistoryMigrationStepInterface
{
    public function process(Request $request, Task $task): void;
}
