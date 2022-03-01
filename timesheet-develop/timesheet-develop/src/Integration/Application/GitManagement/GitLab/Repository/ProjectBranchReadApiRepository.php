<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Integration\Application\GitManagement\GitLab\Repository;

use Gitlab\Client;
use Jagaad\WitcherApi\Integration\Domain\GitManagement\Branch;
use Jagaad\WitcherApi\Integration\Infrastructure\Cache\CacheKeys;
use Jagaad\WitcherApi\Integration\Repository\GitManagement\ProjectBranchReadApiInterface;
use Symfony\Component\Serializer\Normalizer\ContextAwareDenormalizerInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

final class ProjectBranchReadApiRepository implements ProjectBranchReadApiInterface
{
    private const BRANCH_PREFIX_REGEX = '/%s(?=[\d])/';

    private Client $gitlabClient;
    private CacheInterface $cache;
    private ContextAwareDenormalizerInterface $responseDenormalizer;

    public function __construct(Client $gitlabClient, ContextAwareDenormalizerInterface $responseDenormalizer, CacheInterface $cache)
    {
        $this->gitlabClient = $gitlabClient;
        $this->cache = $cache;
        $this->responseDenormalizer = $responseDenormalizer;
    }

    /**
     * @return array<int, Branch>
     */
    public function findGitlabProjectBranchesByHandle(string $projectId, string $taskHandle): array
    {
        if ('' === $projectId) {
            throw new \InvalidArgumentException('Project Id is required, please pass it into request.');
        }

        return $this->cache->get(
            \sprintf(CacheKeys::PROJECT_BRANCH_DATA_KEY, $projectId, $taskHandle),
            function (ItemInterface $item) use ($projectId, $taskHandle): array {
                $item->expiresAfter(CacheKeys::INTEGRATION_DATA_KEY_EXPIRE_1_HOUR);

                $branches = $this->responseDenormalizer->denormalize(
                    $this->gitlabClient->repositories()->branches($projectId, ['search' => $taskHandle]),
                    Branch::class.'[]'
                );

                // Filter returned results for case when for TASK-1 gitlab returns TASK-1 and TASK-17 branches.
                return \array_filter($branches, static function (Branch $branch) use ($taskHandle): bool {
                    return 0 === \preg_match(\sprintf(self::BRANCH_PREFIX_REGEX, $taskHandle), $branch->getName());
                });
            }
        );
    }
}
