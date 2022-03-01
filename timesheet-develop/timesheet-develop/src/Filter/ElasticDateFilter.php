<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Filter;

use ApiPlatform\Core\Bridge\Elasticsearch\DataProvider\Filter\AbstractSearchFilter;

/**
 * Filter the collection by given date type property >= specified datetime value.
 */
final class ElasticDateFilter extends AbstractSearchFilter
{
    /**
     * @param array<mixed> $values
     *
     * @return array<int, mixed>
     */
    protected function getQuery(string $property, array $values, ?string $nestedPath): array
    {
        return [
            [
                'bool' => [
                    'must' => [
                        'range' => [
                            $property => [
                                'gte' => \current($values),
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }
}
