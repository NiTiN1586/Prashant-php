<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Filter;

use ApiPlatform\Core\Serializer\Filter\FilterInterface;
use Symfony\Component\HttpFoundation\Request;

final class GitBranchFilter implements FilterInterface
{
    private const BRANCH = 'branch';

    /**
     * @param array<mixed> $attributes
     * @param array<mixed> $context
     */
    public function apply(Request $request, bool $normalization, array $attributes, array &$context): void
    {
        $branch = $request->get(self::BRANCH);

        if (!isset($context['filters'])) {
            $context['filters'] = [];
        }

        $context['filters'][self::BRANCH] = $branch;
    }

    /**
     * @return array<mixed>
     */
    public function getDescription(string $resourceClass): array
    {
        return [
            self::BRANCH => [
                'property' => null,
                'type' => 'string',
                'required' => false,
                'strategy' => 'exact',
                'openapi' => [
                    'description' => 'Git Branch',
                    'example' => 'WITCHER-1',
                ],
                'swagger' => [
                    'description' => 'Git Branch',
                    'example' => 'WITCHER-1',
                ],
            ],
        ];
    }
}
