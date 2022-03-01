<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Filter;

use ApiPlatform\Core\Serializer\Filter\FilterInterface;
use Symfony\Component\HttpFoundation\Request;

class GitlabProjectSearchFilter implements FilterInterface
{
    private const GITLAB_PROJECT = 'gitlab_project';

    /**
     * @param array<mixed> $attributes
     * @param array<mixed> $context
     */
    public function apply(Request $request, bool $normalization, array $attributes, array &$context): void
    {
        $project = $request->get(self::GITLAB_PROJECT);

        if (!isset($context['filters'])) {
            $context['filters'] = [];
        }
        $context['filters'][self::GITLAB_PROJECT] = $project;
    }

    /**
     * @return array<mixed>
     */
    public function getDescription(string $resourceClass): array
    {
        return [
            self::GITLAB_PROJECT => [
                'property' => null,
                'type' => 'string',
                'required' => true,
                'strategy' => 'exact',
                'openapi' => [
                    'description' => 'Git Project',
                    'example' => 'Witcher',
                ],
                'swagger' => [
                    'description' => 'Git Project',
                    'example' => 'Witcher',
                ],
            ],
        ];
    }
}
