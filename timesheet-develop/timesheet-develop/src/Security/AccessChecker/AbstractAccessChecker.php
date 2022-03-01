<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Security\AccessChecker;

use Jagaad\UserProviderBundle\Security\Model\User;
use Jagaad\WitcherApi\Entity\Role;
use Symfony\Component\Security\Core\Authorization\Voter\AuthenticatedVoter;
use Symfony\Component\Security\Core\Security;

abstract class AbstractAccessChecker implements AccessCheckerInterface
{
    protected User $user;

    /** @var string[] */
    protected array $permissions = [];

    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * {@inheritDoc}
     */
    public function hasAccess(string $operation, object|string $resource, array $originalData = []): bool
    {
        $user = $this->security->getUser();

        if (!$user instanceof User) {
            throw new \LogicException('Invalid session user instance detected');
        }

        $this->user = $user;
        $this->permissions = $user->getPermissions();

        if ($this->security->isGranted(Role::ADMIN)) {
            return true;
        }

        if (!$this->supports($resource)
            || !$this->security->isGranted(AuthenticatedVoter::IS_AUTHENTICATED_FULLY)
        ) {
            return false;
        }

        return $this->supportsOperation(\strtoupper($operation), $resource, $originalData);
    }

    /**
     * @param array<mixed> $originalData
     */
    abstract protected function supportsOperation(string $operation, object|string $resource, array $originalData = []): bool;
}
