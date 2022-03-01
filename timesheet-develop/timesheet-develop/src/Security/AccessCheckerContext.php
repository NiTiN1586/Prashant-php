<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Security;

use Jagaad\WitcherApi\Entity\Role;
use Jagaad\WitcherApi\Security\AccessChecker\AccessCheckerInterface;
use Symfony\Component\Security\Core\Security;

final class AccessCheckerContext implements AccessCheckerContextInterface
{
    /**
     * @param iterable<AccessCheckerInterface> $accessCheckers
     */
    public function __construct(private readonly iterable $accessCheckers, private Security $security)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function hasAccess(object|string $resource, string $operation, array $originalData = []): bool
    {
        if ($this->security->isGranted(Role::ADMIN)) {
            return true;
        }

        foreach ($this->accessCheckers as $accessChecker) {
            if ($accessChecker->supports($resource)) {
                return $accessChecker->hasAccess($operation, $resource, $originalData);
            }
        }

        return false;
    }
}
