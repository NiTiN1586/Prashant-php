<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Integration\Application\Transformer;

use Jagaad\WitcherApi\Integration\Application\DTO\Request;

final class GitCommitRequestTransformer implements RequestTransformerInterface
{
    public function transform(Request $request): array
    {
        $params = [
            'per_page' => $request->getMaxResults(),
            'page' => $request->getStartAt(),
        ];

        if (null !== $request->getRequestParam('branch')) {
            $params['ref_name'] = $request->getRequestParam('branch');
        }

        return $params;
    }
}
