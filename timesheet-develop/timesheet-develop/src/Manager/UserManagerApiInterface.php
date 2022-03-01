<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Manager;

interface UserManagerApiInterface
{
    /**
     * @param array<int, string|null> $emails
     *
     * @return array<string, array{id: integer, invitationEmail: string, active: bool}>
     */
    public function findUsersByEmails(array $emails): array;

    /**
     * @param array<string, mixed> $data
     */
    public function createUser(array $data): int;

    /**
     * @param array<string, mixed> $data
     */
    public function updateUser(int $userId, array $data): void;

    public function deactivateUserById(int $userId): void;
}
