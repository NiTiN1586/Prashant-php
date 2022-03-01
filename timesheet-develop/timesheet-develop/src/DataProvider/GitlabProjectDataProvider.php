<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\DataProvider;

use ApiPlatform\Core\DataProvider\ArrayPaginator;
use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use Jagaad\WitcherApi\ApiResource\GitlabProject;
use Jagaad\WitcherApi\Integration\Application\GitManagement\GitLab\Converter\ConverterInterface;
use Jagaad\WitcherApi\Integration\Repository\GitManagement\GitlabReadApiInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

final class GitlabProjectDataProvider implements ItemDataProviderInterface, ContextAwareCollectionDataProviderInterface, RestrictedDataProviderInterface
{
    private const ITEMS_PER_PAGE = 20;

    public function __construct(
        private DenormalizerInterface $denormalizer,
        private ConverterInterface $converter,
        private GitlabReadApiInterface $gitlabReadApi
    ) {
    }

    /**
     * @param array<mixed> $context
     *
     * @return iterable<int, GitlabProject>
     */
    public function getCollection(string $resourceClass, string $operationName = null, array $context = []): iterable
    {
        $data = [];
        $params = [
            'visibility' => 'private',
        ];

        $filters = $context['filter'] ?? [];
        $page = (int) ($filters['page'] ?? 1);
        $after = (int) ($filters['after'] ?? 0);

        if ($after > 0) {
            $page = (int) \ceil($filters['after'] / self::ITEMS_PER_PAGE);
        }

        if (isset($filters['gitlab_project'])) {
            $params['search'] = $filters['gitlab_project'];
        }

        $this->converter->convert($this->gitlabReadApi->getProjects($params), $data);

        $gitlabProjects = $this->denormalizer->denormalize(
            $data,
            GitlabProject::class.'[]'
        );

        return new ArrayPaginator(
            $gitlabProjects,
            $page,
            self::ITEMS_PER_PAGE
        );
    }

    /**
     * @param mixed $id
     * @param array<mixed> $context
     */
    public function getItem(string $resourceClass, mixed $id, string $operationName = null, array $context = []): ?GitlabProject
    {
        if (!\is_string($id) && !\is_int($id)) {
            throw new \InvalidArgumentException('Id should be of type int or string');
        }

        return GitlabProject::createFromArray($this->gitlabReadApi->getProjectById($id));
    }

    /**
     * @param array<mixed> $context
     */
    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return GitlabProject::class === $resourceClass;
    }
}
