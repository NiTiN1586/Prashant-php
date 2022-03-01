<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Integration\Repository\GitManagement;

interface UserReadApiInterface
{
    public function getUserById(int $userId): ?object;
}
