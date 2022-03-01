<?php

declare(strict_types=1);

namespace Jagaad\UserApi\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Jagaad\UserApi\Enum\ContextGroup;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *     normalizationContext={"groups"={ContextGroup::GOOGLE_ACCOUNT_READ}},
 *     denormalizationContext={"groups"={ContextGroup::GOOGLE_ACCOUNT_WRITE}},
 * )
 *
 * @ORM\Entity()
 * @ORM\Table(
 *     name="google_account",
 *     indexes={
 *         @ORM\Index(name="IDX_google_account_user", columns={"user"})
 *     }
 * )
 */
class GoogleAccount
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="id", type="integer")
     *
     * @ApiProperty(identifier=true)
     *
     * @Groups({
     *     ContextGroup::GOOGLE_ACCOUNT_READ
     * })
     */
    private string $id;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *             "type"="string",
     *             "example"="1234567890987654321"
     *         }
     *     }
     * )
     *
     * @ORM\Column(name="google_account_id", type="string", length=30, nullable=false)
     *
     * @Assert\NotNull()
     * @Assert\Length(
     *      max = 30,
     *      maxMessage = "Google account id cannot be longer than {{ limit }} characters",
     * )
     *
     * @Groups({
     *     ContextGroup::GOOGLE_ACCOUNT_READ,
     *     ContextGroup::GOOGLE_ACCOUNT_WRITE,
     *     ContextGroup::USER_READ
     * })
     */
    private string $googleAccountId;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *             "type"="string",
     *             "example"="email@mail.test"
     *         }
     *     }
     * )
     *
     * @ORM\Column(name="email", type="string", length=40, nullable=false)
     *
     * @Assert\NotNull()
     * @Assert\Length(
     *      max = 40,
     *      maxMessage = "Google account email cannot be longer than {{ limit }} characters",
     * )
     *
     * @Groups({
     *     ContextGroup::GOOGLE_ACCOUNT_READ,
     *     ContextGroup::GOOGLE_ACCOUNT_WRITE,
     *     ContextGroup::USER_READ
     * })
     */
    private string $email;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *             "type"="string",
     *             "example"="firstName"
     *         }
     *     }
     * )
     *
     * @ORM\Column(name="first_name", type="string", length=40, nullable=false)
     *
     * @Assert\NotNull()
     * @Assert\Length(
     *      max = 40,
     *      maxMessage = "Google account first name cannot be longer than {{ limit }} characters",
     * )
     *
     * @Groups({
     *     ContextGroup::GOOGLE_ACCOUNT_READ,
     *     ContextGroup::GOOGLE_ACCOUNT_WRITE,
     *     ContextGroup::USER_READ
     * })
     */
    private string $firstName;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *             "type"="string",
     *             "example"="lastName"
     *         }
     *     }
     * )
     *
     * @ORM\Column(name="last_name", type="string", length=40, nullable=false)
     *
     * @Assert\NotNull()
     * @Assert\Length(
     *      max = 40,
     *      maxMessage = "Google account last name cannot be longer than {{ limit }} characters",
     * )
     *
     * @Groups({
     *     ContextGroup::GOOGLE_ACCOUNT_READ,
     *     ContextGroup::GOOGLE_ACCOUNT_WRITE,
     *     ContextGroup::USER_READ
     * })
     */
    private string $lastName;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *             "type"="string",
     *             "example"="http://domain.zone/avatar.extension"
     *         }
     *     }
     * )
     *
     * @ORM\Column(name="avatar_url", type="string", length=200, nullable=true)
     *
     * @Assert\Length(
     *      max = 200,
     *      maxMessage = "Google account avatar url cannot be longer than {{ limit }} characters",
     * )
     *
     * @Groups({
     *     ContextGroup::GOOGLE_ACCOUNT_READ,
     *     ContextGroup::GOOGLE_ACCOUNT_WRITE,
     *     ContextGroup::USER_READ
     * })
     */
    private string $avatarUrl;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, cascade={"persist", "remove"}, inversedBy="googleAccounts")
     * @ORM\JoinColumn(name="user", referencedColumnName="id", onDelete="CASCADE")
     *
     * @Groups({ContextGroup::GOOGLE_ACCOUNT_READ, ContextGroup::GOOGLE_ACCOUNT_WRITE})
     */
    private User $user;

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getGoogleAccountId(): string
    {
        return $this->googleAccountId;
    }

    public function setGoogleAccountId(string $googleAccountId): self
    {
        $this->googleAccountId = $googleAccountId;

        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getAvatarUrl(): string
    {
        return $this->avatarUrl;
    }

    public function setAvatarUrl(string $avatarUrl): self
    {
        $this->avatarUrl = $avatarUrl;

        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
