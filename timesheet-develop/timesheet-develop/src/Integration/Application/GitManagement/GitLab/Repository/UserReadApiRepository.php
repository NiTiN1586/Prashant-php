<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Integration\Application\GitManagement\GitLab\Repository;

use Gitlab\Client;
use Jagaad\WitcherApi\Integration\Domain\GitManagement\GitlabUser;
use Jagaad\WitcherApi\Integration\Repository\GitManagement\UserReadApiInterface;
use Symfony\Component\Serializer\Normalizer\ContextAwareDenormalizerInterface;

final class UserReadApiRepository implements UserReadApiInterface
{
    private Client $gitlabClient;
    private ContextAwareDenormalizerInterface $responseDenormalizer;

    public function __construct(Client $gitlabClient, ContextAwareDenormalizerInterface $responseDenormalizer)
    {
        $this->gitlabClient = $gitlabClient;
        $this->responseDenormalizer = $responseDenormalizer;
    }

    public function getUserById(int $userId): ?GitlabUser
    {
        return $this->responseDenormalizer->denormalize(
            $this->gitlabClient->users()->show($userId),
            GitlabUser::class
        );
    }
}
