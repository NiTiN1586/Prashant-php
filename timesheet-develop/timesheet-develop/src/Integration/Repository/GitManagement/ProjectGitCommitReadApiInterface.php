<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Integration\Repository\GitManagement;

use Jagaad\WitcherApi\Integration\Application\DTO\Request;

interface ProjectGitCommitReadApiInterface
{
    /**
     * @return object[]
     */
    public function getProjectCommits(Request $request): array;
}
