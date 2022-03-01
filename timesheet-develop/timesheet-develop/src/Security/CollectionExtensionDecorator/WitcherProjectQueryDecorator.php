<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Security\CollectionExtensionDecorator;

use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use Gedmo\SoftDeleteable\Filter\SoftDeleteableFilter;
use Jagaad\WitcherApi\Entity\GitProject;
use Jagaad\WitcherApi\Entity\WitcherProject;
use Jagaad\WitcherApi\Entity\WitcherUser;
use Jagaad\WitcherApi\Repository\TaskRepository;
use Jagaad\WitcherApi\Repository\WitcherProjectRepository;
use Jagaad\WitcherApi\Security\Enum\Permission\Project;
use Symfony\Component\Security\Core\Security;

final class WitcherProjectQueryDecorator extends AbstractQueryDecorator
{
    private WitcherProjectRepository $witcherProjectRepository;
    private TaskRepository $taskRepository;

    public function __construct(
        Security $security,
        WitcherProjectRepository $witcherProjectRepository,
        TaskRepository $taskRepository
    ) {
        parent::__construct($security);
        $this->witcherProjectRepository = $witcherProjectRepository;
        $this->taskRepository = $taskRepository;
    }

    /**
     * {@inheritdoc}
     */
    protected function decorate(QueryBuilder $queryBuilder, array $permissions, string $resource): void
    {
        if (\in_array(Project::VIEW_ALL_PROJECTS->value, $permissions, true)) {
            return;
        }

        $this->whereAuthor($queryBuilder, $resource);
    }

    protected function whereAuthor(QueryBuilder $queryBuilder, string $resource): void
    {
        $alias = $queryBuilder->getRootAliases()[0];
        $exp = $queryBuilder->expr();

        $filters = $queryBuilder->getEntityManager()
            ->getFilters();

        if ($filters->isEnabled('softdeleteable')) {
            /** @var SoftDeleteableFilter $softDeleteableFilter */
            $softDeleteableFilter = $filters->getFilter('softdeleteable');
            $softDeleteableFilter->disableForEntity(WitcherUser::class);
        }

        $queryBuilder->innerJoin(WitcherUser::class, 'wu', Join::WITH, 'wu.userId = :userId');

        if (GitProject::class === $resource) {
            $queryBuilder->innerJoin(\sprintf('%s.witcherProject', $alias), 'wp');
            $alias = 'wp';
        }

        $queryBuilder->andWhere(
            $exp->orX(
                \sprintf('%s.createdBy = wu.id', $alias),
                \sprintf('%s.id IN(:projects)', $alias)
            )
        )
            ->setParameter('userId', $this->user->getId())
            ->setParameter(
                'projects',
                $this->getAssignedProjectIds($this->user->getId())
            );
    }

    public function supports(string $resource): bool
    {
        return \in_array($resource, [GitProject::class, WitcherProject::class], true);
    }

    /**
     * @return int[]
     */
    private function getAssignedProjectIds(int $userId): array
    {
        $projectIds = \array_unique(
            [
                ...$this->taskRepository->findAllAssignedToUserWitcherProjectIds($userId),
                ...$this->witcherProjectRepository->findAllAssignedToTeamProjectIds($userId),
            ]
        );

        \sort($projectIds);

        return $projectIds;
    }
}
