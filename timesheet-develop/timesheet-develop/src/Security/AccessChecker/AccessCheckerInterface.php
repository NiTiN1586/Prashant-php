<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Security\AccessChecker;

interface AccessCheckerInterface
{
    /**
     * HttpFoundation Request is used for Rest operations verification.
     * Current constants are used for GraphQL operations verification
     */
    public const CREATE = 'CREATE';
    public const ITEM_QUERY = 'ITEM_QUERY';
    public const COLLECTION_QUERY = 'COLLECTION_QUERY';
    public const UPDATE = 'UPDATE';

    /**
     * @param array<mixed> $originalData
     */
    public function hasAccess(string $operation, object|string $resource, array $originalData = []): bool;

    public function supports(object|string $resource): bool;
}
