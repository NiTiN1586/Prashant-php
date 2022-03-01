<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Integration\Repository\GitManagement;

interface GitlabReadApiInterface
{
    /**
     * @param array<string, mixed> $params
     *
     * @return array<string, mixed>
     */
    public function getProjects(array $params = []): array;

    /**
     * @param array<string, mixed> $params
     *
     * @return array<string, mixed>
     */
    public function getProjectById(int|string $id, array $params = []): array;
}
