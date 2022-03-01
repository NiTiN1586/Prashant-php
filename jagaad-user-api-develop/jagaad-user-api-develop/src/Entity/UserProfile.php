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
 *     normalizationContext={"groups"={ContextGroup::USER_PROFILE_READ}},
 *     denormalizationContext={"groups"={ContextGroup::USER_PROFILE_WRITE}}
 * )
 *
 * @ORM\Entity()
 * @ORM\Table(
 *     name="user_profile",
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(name="IDX_user__user", columns={"user"}),
 *     },
 *     indexes={
 *         @ORM\Index(name="IDX_user_profile_user", columns={"user"})
 *     }
 * )
 */
class UserProfile
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="id", type="integer")
     *
     * @Groups({ContextGroup::USER_PROFILE_READ})
     */
    private int $id;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *             "type"="string",
     *             "example"="Dow"
     *         }
     *     }
     * )
     *
     * @ORM\Column(name="first_name", length=20, type="string", nullable=true)
     *
     * @Assert\Length(max=20)
     *
     * @Groups({ContextGroup::USER_PROFILE_READ, ContextGroup::USER_PROFILE_WRITE, ContextGroup::USER_READ})
     */
    private ?string $firstName;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *             "type"="string",
     *             "example"="Jones"
     *         }
     *     }
     * )
     *
     * @ORM\Column(name="last_name", length=20, type="string", nullable=true)
     *
     * @Assert\Length(max=20)
     *
     * @Groups({ContextGroup::USER_PROFILE_READ, ContextGroup::USER_PROFILE_WRITE, ContextGroup::USER_READ})
     */
    private ?string $lastName;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *             "type"="string",
     *             "example"="Jagaad"
     *         }
     *     }
     * )
     *
     * @ORM\Column(name="company", length=20, type="string")
     *
     * @Assert\Length(max=20)
     *
     * @Groups({ContextGroup::USER_PROFILE_READ, ContextGroup::USER_PROFILE_WRITE, ContextGroup::USER_READ})
     */
    private string $company;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *             "type"="string",
     *             "example"="CY 17 002 00128 00000012005276002﻿"
     *         }
     *     }
     * )
     *
     * @ORM\Column(name="iban", type="string", length=34)
     *
     * @Assert\Length(max=34)
     *
     * @Groups({ContextGroup::USER_PROFILE_READ, ContextGroup::USER_PROFILE_WRITE, ContextGroup::USER_READ})
     */
    private string $iban;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *             "type"="string",
     *             "example"="32451678"
     *         }
     *     }
     * )
     *
     * @ORM\Column(name="vat", type="string", length=20)
     *
     * @Assert\Length(max=20)
     *
     * @Groups({ContextGroup::USER_PROFILE_READ, ContextGroup::USER_PROFILE_WRITE, ContextGroup::USER_READ})
     */
    private string $vat;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *             "type"="string",
     *             "example"="Raiffeisen Bank"
     *         }
     *     }
     * )
     *
     * @ORM\Column(name="bank_name", type="string", length=50)
     *
     * @Assert\Length(max=50)
     *
     * @Groups({ContextGroup::USER_PROFILE_READ, ContextGroup::USER_PROFILE_WRITE, ContextGroup::USER_READ})
     */
    private string $beneficiaryBankName;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *             "type"="string",
     *             "example"="AAAA-BB-CC"
     *         }
     *     }
     * )
     *
     * @ORM\Column(name="swift_code", type="string", length=11)
     *
     * @Assert\Length(max=11)
     *
     * @Groups({ContextGroup::USER_PROFILE_READ, ContextGroup::USER_PROFILE_WRITE, ContextGroup::USER_READ})
     */
    private string $swiftCode;

    /**
     * @ORM\OneToOne(targetEntity=User::class)
     * @ORM\JoinColumn(name="user", referencedColumnName="id", onDelete="CASCADE", unique=true, nullable=false)
     *
     * @Groups({ContextGroup::USER_PROFILE_READ, ContextGroup::USER_PROFILE_WRITE})
     */
    private User $user;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getCompany(): string
    {
        return $this->company;
    }

    public function setCompany(string $company): self
    {
        $this->company = $company;

        return $this;
    }

    public function getIban(): string
    {
        return $this->iban;
    }

    public function setIban(string $iban): self
    {
        $this->iban = $iban;

        return $this;
    }

    public function getVat(): string
    {
        return $this->vat;
    }

    public function setVat(string $vat): self
    {
        $this->vat = $vat;

        return $this;
    }

    public function getBeneficiaryBankName(): string
    {
        return $this->beneficiaryBankName;
    }

    public function setBeneficiaryBankName(string $beneficiaryBankName): self
    {
        $this->beneficiaryBankName = $beneficiaryBankName;

        return $this;
    }

    public function getSwiftCode(): string
    {
        return $this->swiftCode;
    }

    public function setSwiftCode(string $swiftCode): self
    {
        $this->swiftCode = $swiftCode;

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
