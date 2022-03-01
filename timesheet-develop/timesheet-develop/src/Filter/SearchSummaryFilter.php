<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Filter;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\AbstractContextAwareFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use Doctrine\ORM\QueryBuilder;

final class SearchSummaryFilter extends AbstractContextAwareFilter
{
    /**
     * @param array<mixed> $context
     */
    protected function filterProperty(
        string $property,
        mixed $value,
        QueryBuilder $queryBuilder,
        QueryNameGeneratorInterface $queryNameGenerator,
        string $resourceClass,
        string $operationName = null,
        array $context = []
    ): void {
        $exp = $queryBuilder->expr();

        if ('searchSummary' !== $property) {
            return;
        }

        $alias = $queryBuilder->getRootAliases()[0];
        $query = \mb_strtolower($value);

        $queryBuilder->andWhere(
            $exp->orX(
                $exp->like(\sprintf('%s.slug', $alias), ':searchStart'),
                $exp->like(\sprintf('%s.summary', $alias), ':searchStart'),
                \sprintf('REGEXP(%s.summary, :searchWord) = 1', $alias)
            )
        )
            ->setParameter('searchStart', $query.'%')
            ->setParameter('searchWord', \sprintf('\\b%s\\b', $query));
    }

    /**
     * @return array<mixed>
     */
    public function getDescription(string $resourceClass): array
    {
        return [
            'searchSummary' => [
                'property' => 'searchSummary',
                'type' => 'string',
                'required' => false,
                'openapi' => [
                    'description' => 'Search for query in task slug and summary',
                ],
            ],
        ];
    }
}
