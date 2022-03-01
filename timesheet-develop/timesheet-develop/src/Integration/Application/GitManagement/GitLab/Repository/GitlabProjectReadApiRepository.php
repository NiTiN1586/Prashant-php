<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Integration\Application\GitManagement\GitLab\Repository;

use Gitlab\Client;
use Jagaad\WitcherApi\Integration\Repository\GitManagement\GitlabReadApiInterface;

final class GitlabProjectReadApiRepository implements GitlabReadApiInterface
{
    public function __construct(private Client $gitlabClient)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function getProjects(array $params = []): array
    {
        return $this->gitlabClient->projects()->all($params);
    }

    /**
     * {@inheritDoc}
     */
    public function getProjectById(int|string $id, array $params = []): array
    {
        return $this->gitlabClient->projects()->show($id, $params);
    }
}
