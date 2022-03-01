<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Security\CollectionExtensionDecorator;

use Doctrine\ORM\QueryBuilder;
use Jagaad\UserProviderBundle\Security\Model\User;
use Jagaad\WitcherApi\Entity\Role;
use Jagaad\WitcherApi\Exception\ResourceAccessDeniedException;
use Jagaad\WitcherApi\Security\Extension\CollectionExtensionQueryDecoratorInterface;
use Jagaad\WitcherApi\Utils\ClassHelper;
use Symfony\Component\Security\Core\Authorization\Voter\AuthenticatedVoter;
use Symfony\Component\Security\Core\Security;

abstract class AbstractQueryDecorator implements CollectionExtensionQueryDecoratorInterface
{
    protected User $user;
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function process(QueryBuilder $queryBuilder, string $resource): void
    {
        $user = $this->security->getUser();

        if (!$user instanceof User) {
            throw new \LogicException('Invalid session user instance detected');
        }

        if ($this->security->isGranted(Role::ADMIN)) {
            return;
        }

        if (!$this->security->isGranted(AuthenticatedVoter::IS_AUTHENTICATED_FULLY)) {
            throw ResourceAccessDeniedException::create(\sprintf('You don\'t have permissions to invoke operation on resource \'%s\'', ClassHelper::getClass($resource)));
        }

        $this->user = $user;
        $this->decorate($queryBuilder, $user->getPermissions(), $resource);
    }

    /**
     * @param string[] $permissions
     */
    abstract protected function decorate(QueryBuilder $queryBuilder, array $permissions, string $resource): void;
}
