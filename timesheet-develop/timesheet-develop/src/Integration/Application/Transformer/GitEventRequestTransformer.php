<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Integration\Application\Transformer;

use Jagaad\WitcherApi\Integration\Application\DTO\Request;

final class GitEventRequestTransformer implements RequestTransformerInterface
{
    public function transform(Request $request): array
    {
        $queryParams = [
            'per_page' => $request->getMaxResults(),
            'page' => $request->getStartAt(),
        ];

        if ($request->getRequestParam('before') instanceof \DateTimeInterface) {
            $queryParams['before'] = $request->getRequestParam('before');
        }

        if ($request->getRequestParam('after') instanceof \DateTimeInterface) {
            $queryParams['after'] = $request->getRequestParam('after');
        }

        return $queryParams;
    }
}
