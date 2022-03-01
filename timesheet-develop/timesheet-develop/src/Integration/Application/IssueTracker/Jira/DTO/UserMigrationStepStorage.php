<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Integration\Application\IssueTracker\Jira\DTO;

use Assert\Assertion;
use Jagaad\WitcherApi\Entity\Role;
use Symfony\Component\Validator\Constraints as Assert;

final class UserMigrationStepStorage
{
    private int $buffer = 0;

    /** @var array{id: integer, invitationEmail: string, active: bool}|null */
    private ?array $user = null;

    /**
     * @var array<string, Role>
     *
     * @Assert\NotNull()
     * @Assert\Count(min=1, minMessage="roles can't be empty")
     */
    private array $roles;

    /**
     * @param array<string, Role> $roles
     */
    public function __construct(array $roles)
    {
        Assertion::allIsInstanceOf(
            $roles,
            Role::class,
            'roles argument should be of Role array type'
        );

        $this->roles = $roles;
    }

    public function increase(): self
    {
        ++$this->buffer;

        return $this;
    }

    public function resetBuffer(): self
    {
        $this->buffer = 0;

        return $this;
    }

    public function getBuffer(): int
    {
        return $this->buffer;
    }

    /**
     * @return Role[]
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    public function getRole(string $handle): ?Role
    {
        return $this->roles[$handle] ?? null;
    }

    /**
     * @return array{id: integer, invitationEmail: string, active: bool}|null
     */
    public function getUser(): ?array
    {
        return $this->user;
    }

    /**
     * @param array{id: integer, invitationEmail: string, active: bool}|null $user
     */
    public function setUser(?array $user): self
    {
        $this->user = $user;

        return $this;
    }
}
