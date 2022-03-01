<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Security\AccessChecker;

use Jagaad\WitcherApi\Entity\WitcherUser;
use Symfony\Component\HttpFoundation\Request;

final class WitcherUserAccessChecker extends AbstractAccessChecker
{
    public function supports(object|string $resource): bool
    {
        return WitcherUser::class === $resource || $resource instanceof WitcherUser;
    }

    /**
     * {@inheritDoc}
     */
    protected function supportsOperation(string $operation, object|string $resource, array $originalData = []): bool
    {
        return match ($operation) {
            self::UPDATE,
            Request::METHOD_PATCH => $resource instanceof WitcherUser && $this->user->getId() === $resource->getUserId(),

            self::ITEM_QUERY,
            self::COLLECTION_QUERY,
            Request::METHOD_GET => true,

            default => false
        };
    }
}
