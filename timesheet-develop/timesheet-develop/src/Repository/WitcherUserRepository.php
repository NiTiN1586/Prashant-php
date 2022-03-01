<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Repository;

use Assert\Assertion;
use Doctrine\Persistence\ManagerRegistry;
use Jagaad\UserProviderBundle\Security\Model\User;
use Jagaad\WitcherApi\Cache\CacheKeys;
use Jagaad\WitcherApi\Entity\WitcherUser;

/**
 * @template-extends AbstractRepository<WitcherUser>
 */
final class WitcherUserRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, WitcherUser::class);
    }

    public function findOneByJiraAccountId(string $accountId, bool $active = true): ?WitcherUser
    {
        $this->softDeleteFilterAction($active);

        $qb = $this->getEntityManager()->createQueryBuilder();

        $user = $qb->select('wu')
            ->from(WitcherUser::class, 'wu')
            ->where('wu.jiraAccount = :accountId')
            ->andWhere('wu.userId IS NOT NULL')
            ->setParameter('accountId', $accountId)
            ->getQuery()
            ->execute();

        $this->softDeleteFilterAction();

        return $user;
    }

    /**
     * @param array<int, string> $accountIds
     *
     * @return array<string, WitcherUser>
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \InvalidArgumentException
     */
    public function findWitcherUserByJiraAccountIds(array $accountIds, bool $active = true): array
    {
        $results = [];

        Assertion::allString(
            $accountIds,
            'Incorrect array type. Values should be a string'
        );

        $this->softDeleteFilterAction($active);

        $qb = $this->getEntityManager()->createQueryBuilder();

        $users = $qb->select('wu')
            ->from(WitcherUser::class, 'wu')
            ->where('wu.jiraAccount IN(:accountIds)')
            ->andWhere('wu.userId IS NOT NULL')
            ->getQuery()
            ->setParameter('accountIds', $accountIds)
            ->execute();

        /** @var WitcherUser $witcherUser */
        foreach ($users as $witcherUser) {
            $results[$witcherUser->getJiraAccount()] = $witcherUser;
        }

        $this->softDeleteFilterAction();

        return $results;
    }

    /**
     * @param int[] $userIds
     *
     * @return array<int, WitcherUser>
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \InvalidArgumentException
     */
    public function findWitcherUsersByGitlabUserIds(
        array $userIds,
        bool $active = true,
        bool $useCache = false,
        int $expirationTime = CacheKeys::USER_DATA_KEY_EXPIRE_1_HOUR
    ): array {
        $results = [];

        Assertion::allInteger(
            $userIds,
            'Incorrect array type. Values should be a integer'
        );

        $this->softDeleteFilterAction($active);

        $query = $this->createQueryBuilder('wu')
            ->where('wu.gitLabUserId IN(:userIds)')
            ->setParameter('userIds', $userIds)
            ->getQuery();

        if ($useCache) {
            $query = $query->enableResultCache($expirationTime);
        }

        $witcherUsers = $query->execute();

        /** @var WitcherUser $witcherUser */
        foreach ($witcherUsers as $witcherUser) {
            if (null !== $witcherUser->getGitLabUserId()) {
                $results[$witcherUser->getGitLabUserId()] = $witcherUser;
            }
        }

        $this->softDeleteFilterAction();

        return $results;
    }

    public function findOneByUserId(int $id, bool $useCache = true, int $expirationTime = CacheKeys::USER_DATA_KEY_EXPIRE_1_HOUR): ?WitcherUser
    {
        $query = $this->createQueryBuilder('wu')
            ->where('wu.userId = :id')
            ->setParameter('id', $id)
            ->getQuery();

        if ($useCache) {
            $query = $query->enableResultCache($expirationTime);
        }

        return $query->getOneOrNullResult();
    }

    /**
     * @param int[] $ids
     *
     * @return array<int, WitcherUser>
     */
    public function findByUserIds(
        array $ids,
        bool $active = true,
        bool $useCache = true,
        int $expirationTime = CacheKeys::USER_DATA_KEY_EXPIRE_1_HOUR
    ): array {
        Assertion::allInteger($ids, 'Id values should be integer type');

        $this->softDeleteFilterAction($active);

        $query = $this->createQueryBuilder('wu')
            ->where('wu.userId IN (:ids)')
            ->setParameter('ids', $ids)
            ->getQuery();

        if ($useCache) {
            $query = $query->enableResultCache($expirationTime);
        }

        $users = [];

        /** @var WitcherUser $witcherUser */
        foreach ($query->getResult() as $witcherUser) {
            $users[$witcherUser->getUserId()] = $witcherUser;
        }

        $this->softDeleteFilterAction();

        return $users;
    }

    public function findWitcherUserByUserId(int $userId): ?WitcherUser
    {
        return $this->createQueryBuilder('wu')
            ->addSelect('r, rp')
            ->innerJoin('wu.role', 'r')
            ->leftJoin('r.permissions', 'rp')
            ->andWhere('wu.userId = :userId')
            ->setParameter('userId', $userId)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findWitcherUserByRole(string $role, bool $active = true): ?WitcherUser
    {
        $this->softDeleteFilterAction($active);

        $witcherUser = $this->createQueryBuilder('wu')
            ->addSelect('r, rp')
            ->innerJoin('wu.role', 'r')
            ->leftJoin('r.permissions', 'rp')
            ->andWhere('r.handle = :role')
            ->setParameter('role', $role)
            ->getQuery()
            ->getOneOrNullResult();

        $this->softDeleteFilterAction();

        return $witcherUser;
    }

    public function findIdByUser(
        User $user,
        bool $active = true,
        bool $userCache = true,
        int $expirationTime = CacheKeys::USER_DATA_KEY_EXPIRE_1_HOUR
    ): int {
        $this->softDeleteFilterAction($active);

        $query = $this->createQueryBuilder('wu')
            ->select('wu.id')
            ->andWhere('wu.userId = :userId')
            ->setParameter('userId', $user->getId())
            ->getQuery();

        if ($userCache) {
            $query->enableResultCache($expirationTime);
        }

        $witcherUserId = $query->getSingleScalarResult();

        $this->softDeleteFilterAction();

        return (int) $witcherUserId;
    }
}
