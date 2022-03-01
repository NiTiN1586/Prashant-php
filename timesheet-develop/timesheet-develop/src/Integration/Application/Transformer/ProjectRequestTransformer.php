<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Integration\Application\Transformer;

use Jagaad\WitcherApi\Integration\Application\DTO\Request;

final class ProjectRequestTransformer implements RequestTransformerInterface
{
    /**
     * {@inheritdoc}
     */
    public function transform(Request $request): array
    {
        return [
            'startAt' => $request->getStartAt(),
            'maxResults' => $request->getMaxResults(),
            'expand' => $request->getRequestParam('expand', 'lead,description,projectKeys,issueTypes'),
            'id' => $request->getRequestParam('id', []),
            Request::KEYS_PARAM => $request->getRequestParam(Request::KEYS_PARAM, []),
        ];
    }
}
