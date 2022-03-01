<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Repository;

use Doctrine\Persistence\ManagerRegistry;
use Jagaad\WitcherApi\Entity\Role;

/**
 * @template-extends AbstractRepository<Role>
 */
final class RoleRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, Role::class);
    }

    public function findAll(bool $active = true): array
    {
        $results = [];
        $this->softDeleteFilterAction($active);

        /** @var Role[] $roles */
        $roles = $this->createQueryBuilder('r')
            ->getQuery()
            ->getResult();

        foreach ($roles as $role) {
            $results[$role->getHandle()] = $role;
        }

        $this->softDeleteFilterAction();

        return $results;
    }
}
