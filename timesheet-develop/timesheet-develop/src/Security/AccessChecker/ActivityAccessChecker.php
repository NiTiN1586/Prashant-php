<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Security\AccessChecker;

use Jagaad\WitcherApi\Entity\Activity;
use Jagaad\WitcherApi\Entity\Comment;
use Jagaad\WitcherApi\Repository\TaskRepository;
use Jagaad\WitcherApi\Repository\WitcherProjectRepository;
use Jagaad\WitcherApi\Security\Enum\Permission\Activity as EnumActivity;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;

final class ActivityAccessChecker extends AbstractAccessChecker
{
    public function __construct(
        Security $security,
        private WitcherProjectRepository $witcherProjectRepository,
        private TaskRepository $taskRepository
    ) {
        parent::__construct($security);
    }

    public function supports(object|string $resource): bool
    {
        return $resource instanceof Activity
            || $resource instanceof Comment
            || \in_array($resource, [Activity::class, Comment::class], true);
    }

    /**
     * {@inheritDoc}
     */
    protected function supportsOperation(string $operation, object|string $resource, array $originalData = []): bool
    {
        return $this->verifyActivityPermissionRules($resource, $operation);
    }

    private function verifyActivityPermissionRules(Activity|Comment|string $activity, string $operation): bool
    {
        return match ($operation) {
            self::COLLECTION_QUERY => \in_array(EnumActivity::MANAGE_ACTIVITIES->value, $this->permissions, true),

            self::ITEM_QUERY,
            Request::METHOD_GET,
            self::CREATE,
            Request::METHOD_POST => ($activity instanceof Activity || $activity instanceof Comment)
                && $this->verifyActivityAccessibilityRule($activity),

            self::UPDATE,
            Request::METHOD_DELETE,
            Request::METHOD_PATCH => ($activity instanceof Activity || $activity instanceof Comment)
                && $this->verifyOwnActivityManagementRule($activity),

            default => false,
        };
    }

    private function verifyActivityAccessibilityRule(Activity|Comment $activity): bool
    {
        if (!\in_array(EnumActivity::MANAGE_ACTIVITIES->value, $this->permissions, true)) {
            return false;
        }

        $userId = $this->user->getId();
        $witcherProject = $activity->getTask()->getWitcherProject();

        $isPartOfProjectTeam = $this->witcherProjectRepository->isAssignedToWitcherProjectTeam(
            $witcherProject->getId(),
            $userId
        );

        $hasAssignedTasks = $this->taskRepository->hasAssignedTasks(
            $witcherProject->getId(),
            $userId
        );

        return $isPartOfProjectTeam || $hasAssignedTasks || $witcherProject->getCreatedByUserId() === $userId;
    }

    private function verifyOwnActivityManagementRule(Activity|Comment $activity): bool
    {
        return \in_array(EnumActivity::MANAGE_ACTIVITIES->value, $this->permissions, true)
            && $activity->getCreatedByUserId() === $this->user->getId();
    }
}
