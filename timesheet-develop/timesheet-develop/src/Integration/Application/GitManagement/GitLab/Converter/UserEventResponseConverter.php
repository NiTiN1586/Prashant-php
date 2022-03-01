<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Integration\Application\GitManagement\GitLab\Converter;

use Jagaad\WitcherApi\Cache\CacheKeys;
use Jagaad\WitcherApi\Entity\WitcherUser;
use Jagaad\WitcherApi\Repository\WitcherUserRepository;

final class UserEventResponseConverter implements ConverterInterface
{
    private WitcherUserRepository $witcherUserRepository;

    public function __construct(WitcherUserRepository $witcherUserRepository)
    {
        $this->witcherUserRepository = $witcherUserRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function convert(array $source, array &$destination): void
    {
        $witcherUsers = $this->witcherUserRepository->findWitcherUsersByGitlabUserIds(
            \array_map('intval', \array_keys($source)),
            true,
            true,
            CacheKeys::USER_DATA_KEY_EXPIRE_10_MINUTES
        );

        foreach ($destination as $index => $value) {
            $accountId = $value['author']['id'] ?? null;

            if (null === $accountId) {
                $destination[$index]['author'] = null;

                continue;
            }

            /** @var WitcherUser|null $witcherUser */
            $witcherUser = $witcherUsers[$accountId] ?? null;
            $destination[$index]['author'] = $witcherUser?->getId();
        }
    }
}
