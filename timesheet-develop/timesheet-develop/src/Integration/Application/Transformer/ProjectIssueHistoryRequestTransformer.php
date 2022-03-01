<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Integration\Application\Transformer;

use Jagaad\WitcherApi\Integration\Application\DTO\Request;

final class ProjectIssueHistoryRequestTransformer implements RequestTransformerInterface
{
    /**
     * {@inheritdoc}
     */
    public function transform(Request $request): array
    {
        return [
            'startAt' => $request->getStartAt(),
            'maxResults' => $request->getMaxResults(),
            'expand' => $request->getRequestParam('expand', 'changelog'),
            'fields' => $request->getRequestParam('fields', 'histories'),
        ];
    }
}
