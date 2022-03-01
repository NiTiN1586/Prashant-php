<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Security\CollectionExtensionDecorator;

use Doctrine\ORM\QueryBuilder;
use Jagaad\WitcherApi\Entity\Activity;
use Jagaad\WitcherApi\Entity\Comment;
use Jagaad\WitcherApi\Repository\TaskRepository;
use Jagaad\WitcherApi\Repository\WitcherProjectRepository;
use Symfony\Component\Security\Core\Security;

final class ActivityQueryDecorator extends AbstractQueryDecorator
{
    public function __construct(
        Security $security,
        private TaskRepository $taskRepository,
        private WitcherProjectRepository $witcherProjectRepository
    ) {
        parent::__construct($security);
    }

    /**
     * {@inheritdoc}
     */
    protected function decorate(QueryBuilder $queryBuilder, array $permissions, string $resource): void
    {
        $queryBuilder->innerJoin(\sprintf('%s.task', $queryBuilder->getRootAliases()[0]), 't')
            ->innerJoin('t.witcherProject', 'wp')
            ->andWhere('wp.id IN(:witcherProjectIds)')
            ->setParameter('witcherProjectIds', $this->getAssignedProjectIds());
    }

    public function supports(string $resource): bool
    {
        return \in_array($resource, [Activity::class, Comment::class], true);
    }

    /**
     * @return int[]
     */
    private function getAssignedProjectIds(): array
    {
        $userId = $this->user->getId();

        $projectIds = \array_unique(
            [
                ...$this->taskRepository->findAllAssignedToUserWitcherProjectIds($userId, false),
                ...$this->witcherProjectRepository->findAllAssignedToTeamProjectIds($userId, false),
            ]
        );

        \sort($projectIds);

        return $projectIds;
    }
}
