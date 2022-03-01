<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Integration\Application\Transformer;

use Jagaad\WitcherApi\Integration\Application\DTO\Request;

final class IssueRequestTransformer implements RequestTransformerInterface
{
    /**
     * {@inheritdoc}
     */
    public function transform(Request $request): array
    {
        if (null === $request->getRequestParam(Request::JQL_PARAM)) {
            throw new \InvalidArgumentException('jql parameters should be set for querying issue');
        }

        return [
            'startAt' => $request->getStartAt(),
            'maxResults' => $request->getMaxResults(),
            'expand' => $this->getExpand($request),
            'jql' => $request->getRequestParam(Request::JQL_PARAM, ''),
            'fields' => $request->getRequestParam('fields', []),
            'validateQuery' => 'warn',
            'fieldsByKeys' => false,
        ];
    }

    /**
     * @return string[]
     */
    private function getExpand(Request $request): array
    {
        $expand = \explode(',', $request->getRequestParam('expand', ''));
        $expand[] = 'names';

        $results = [];

        foreach ($expand as $value) {
            if (\in_array($value, [null, ''], true) || \in_array($value, $results, true)) {
                continue;
            }

            $results[] = $value;
        }

        return $results;
    }
}
