<?php

declare(strict_types=1);

namespace Jagaad\UserApi\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity()
 * @ORM\Table(
 *     name="api_token",
 *     uniqueConstraints={
 *          @ORM\UniqueConstraint(name="UK_api_token_token", columns={"token"}),
 *          @ORM\UniqueConstraint(name="UK_api_token_application", columns={"application"})
 *      },
 *     indexes={
 *         @ORM\Index(name="IDX_api_token_application", columns={"application"}),
 *         @ORM\Index(name="IDX_api_token_createdAt", columns={"created_at"})
 *     })
 *
 * @UniqueEntity(fields={"token"}, message="Token generation issue occured")
 * @UniqueEntity(fields={"application"}, message="Application entry already exists")
 */
final class ApiToken implements UserInterface
{
    private const MAX_CHARS = 20;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="id", type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(name="application", type="string", length=20)
     */
    private string $application;

    /**
     * @ORM\Column(name="token", type="string")
     */
    private string $token;

    /**
     * @ORM\Column(name="created_at", type="date_immutable")
     */
    private \DateTimeImmutable $createdAt;

    public function __construct(string $application)
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->application = $application;
        $this->token = $this->generateToken();
    }

    /**
     * @return string[]
     */
    public function getRoles(): array
    {
        return ['ROLE_API'];
    }

    public function getPassword(): ?string
    {
        return null;
    }

    public function getSalt(): ?string
    {
        return null;
    }

    public function getUsername(): string
    {
        return $this->token;
    }

    public function eraseCredentials(): void
    {
    }

    public function getApplication(): string
    {
        return $this->application;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function rotate(): void
    {
        $this->token = $this->generateToken();
    }

    private function generateToken(): string
    {
        return bin2hex(random_bytes(self::MAX_CHARS));
    }
}
