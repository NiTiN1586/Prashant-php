<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Integration\Repository\GitManagement;

use Jagaad\WitcherApi\Integration\Domain\GitManagement\Branch;

interface ProjectBranchReadApiInterface
{
    /**
     * @param string $projectId
     * @param string $taskHandle
     *
     * @return Branch[]
     */
    public function findGitlabProjectBranchesByHandle(string $projectId, string $taskHandle): array;
}
