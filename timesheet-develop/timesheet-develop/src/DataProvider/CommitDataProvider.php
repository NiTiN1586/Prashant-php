<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\DataProvider;

use ApiPlatform\Core\DataProvider\ArrayPaginator;
use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use ApiPlatform\Core\DataProvider\SerializerAwareDataProviderInterface;
use ApiPlatform\Core\DataProvider\SerializerAwareDataProviderTrait;
use Jagaad\WitcherApi\ApiResource\Commit;
use Jagaad\WitcherApi\Integration\Application\DTO\Request;
use Jagaad\WitcherApi\Integration\Repository\GitManagement\ProjectGitCommitReadApiInterface;

final class CommitDataProvider implements ContextAwareCollectionDataProviderInterface, RestrictedDataProviderInterface, SerializerAwareDataProviderInterface
{
    use SerializerAwareDataProviderTrait;

    private const ITEMS_PER_PAGE = 20;

    public function __construct(private ProjectGitCommitReadApiInterface $projectGitCommitRepository)
    {
    }

    /**
     * @param array<mixed> $context
     *
     * @return iterable<int, Commit>
     */
    public function getCollection(string $resourceClass, string $operationName = null, array $context = []): iterable
    {
        $filters = $context['filters'] ?? [];

        if (!isset($filters['project'])) {
            return [];
        }

        $page = (int) ($filters['page'] ?? 1);
        $after = (int) ($filters['after'] ?? 0);

        if ($after > 0) {
            $page = (int) \ceil($filters['after'] / self::ITEMS_PER_PAGE);
        }

        return new ArrayPaginator(
            $this->projectGitCommitRepository->getProjectCommits(
                new Request(
                    [
                        'id' => $filters['project'],
                        'branch' => $filters['branch'] ?? null,
                    ],
                    $page,
                    self::ITEMS_PER_PAGE
                )
            ),
            $page,
            self::ITEMS_PER_PAGE
        );
    }

    /**
     * @param array<mixed> $context
     */
    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return Commit::class === $resourceClass;
    }
}
