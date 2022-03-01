<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Integration\Application\Transformer;

use Jagaad\WitcherApi\Integration\Application\DTO\Request;

final class GitlabBranchRequestTransformer implements RequestTransformerInterface
{
    /**
     * {@inheritdoc}
     */
    public function transform(Request $request): array
    {
        return [
            'per_page' => $request->getMaxResults(),
            'page' => $request->getStartAt(),
        ];
    }
}
