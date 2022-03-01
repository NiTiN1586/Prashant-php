<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Filter;

use ApiPlatform\Core\Serializer\Filter\FilterInterface;
use Symfony\Component\HttpFoundation\Request;

final class GitProjectFilter implements FilterInterface
{
    private const PROJECT = 'project';

    /**
     * @param array<mixed> $attributes
     * @param array<mixed> $context
     */
    public function apply(Request $request, bool $normalization, array $attributes, array &$context): void
    {
        $project = $request->get(self::PROJECT);

        if (!isset($context['filters'])) {
            $context['filters'] = [];
        }
        $context['filters'][self::PROJECT] = $project;
    }

    /**
     * @return array<mixed>
     */
    public function getDescription(string $resourceClass): array
    {
        return [
            self::PROJECT => [
                'property' => null,
                'type' => 'string',
                'required' => true,
                'strategy' => 'exact',
                'openapi' => [
                    'description' => 'Git Project',
                    'example' => '12345',
                ],
                'swagger' => [
                    'description' => 'Git Project',
                    'example' => '12345',
                ],
            ],
        ];
    }
}
