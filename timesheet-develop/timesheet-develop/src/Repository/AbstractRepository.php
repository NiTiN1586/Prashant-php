<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManager;
use Gedmo\SoftDeleteable\Filter\SoftDeleteableFilter;

/**
 * @template T of object
 * @extends ServiceEntityRepository<T>
 */
abstract class AbstractRepository extends ServiceEntityRepository
{
    /**
     * @param T $entity
     * @param bool $flush
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(object $entity, bool $flush = true): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->flush();
        }
    }

    /**
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\ORMException
     */
    public function flush(): void
    {
        $this->getEntityManager()->flush();
    }

    /**
     * @param T $entity
     * @param bool $flush
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function remove(object $entity, bool $flush = true): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->flush();
        }
    }

    /**
     * @param array<T> $entities
     * @param bool $flush
     *
     * @throws \Doctrine\ORM\ORMException
     */
    public function removeBulk(array $entities, bool $flush = true): void
    {
        $entityManager = $this->getEntityManager();

        foreach ($entities as $entity) {
            $entityManager->remove($entity);
        }

        if ($flush) {
            $this->flush();
        }
    }

    protected function softDeleteFilterAction(bool $enable = true): void
    {
        $filters = $this->getEntityManager()->getFilters();

        if ($enable) {
            if (!$filters->isEnabled('softdeleteable')) {
                $filters->enable('softdeleteable');
            }

            return;
        }

        if ($filters->isEnabled('softdeleteable')) {
            $filters->disable('softdeleteable');
        }
    }

    protected function disableSoftDeleteableFor(string $entity): static
    {
        $filters = $this->getEntityManager()
            ->getFilters();

        if ($filters->isEnabled('softdeleteable')) {
            /** @var SoftDeleteableFilter $softDeleteableFilter */
            $softDeleteableFilter = $filters->getFilter('softdeleteable');
            $softDeleteableFilter->disableForEntity($entity);
        }

        return $this;
    }

    public function restoreEntityManager(): void
    {
        if (!$this->getEntityManager()->isOpen()) {
            $this->_em = EntityManager::create($this->_em->getConnection(), $this->_em->getConfiguration());
        }
    }

    /**
     * @param T $entity
     */
    public function hasOriginalEntityData(object $entity): bool
    {
        return \count($this->getEntityManager()->getUnitOfWork()->getOriginalEntityData($entity)) > 0;
    }
}
