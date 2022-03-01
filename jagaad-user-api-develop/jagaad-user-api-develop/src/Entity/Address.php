<?php

declare(strict_types=1);

namespace Jagaad\UserApi\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Jagaad\UserApi\Enum\ContextGroup;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *     normalizationContext={"groups"={ContextGroup::USER_ADDRESS_READ}},
 *     denormalizationContext={"groups"={ContextGroup::USER_ADDRESS_WRITE}}
 * )
 *
 * @ORM\Entity()
 * @ORM\Table(
 *     name="address",
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(name="UK_address_user", columns={"user"}),
 *     }
 * )
 */
class Address
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="id", type="integer")
     *
     * @Groups({ContextGroup::USER_ADDRESS_READ})
     */
    private int $id;

    /**
     * @ORM\Column(name="street", type="string")
     *
     * @Groups({ContextGroup::USER_ADDRESS_READ, ContextGroup::USER_ADDRESS_WRITE, ContextGroup::USER_READ})
     */
    private string $street;

    /**
     * @ORM\Column(name="city", type="string")
     *
     * @Groups({ContextGroup::USER_ADDRESS_READ, ContextGroup::USER_ADDRESS_WRITE, ContextGroup::USER_READ})
     */
    private string $city;

    /**
     * @ORM\Column(name="state", type="string")
     *
     * @Groups({ContextGroup::USER_ADDRESS_READ, ContextGroup::USER_ADDRESS_WRITE, ContextGroup::USER_READ})
     */
    private string $state;

    /**
     * @ORM\Column(name="country", length=50, type="string")
     * @Assert\Length(max=50)
     *
     * @Groups({ContextGroup::USER_ADDRESS_READ, ContextGroup::USER_ADDRESS_WRITE, ContextGroup::USER_READ})
     */
    private string $country;

    /**
     * @ORM\Column(name="postal_code", length=20, type="string")
     * @Assert\Length(max=20)
     *
     * @Groups({ContextGroup::USER_ADDRESS_READ, ContextGroup::USER_ADDRESS_WRITE, ContextGroup::USER_READ})
     */
    private string $postalCode;

    /**
     * @ORM\OneToOne(targetEntity=User::class)
     * @ORM\JoinColumn(name="user", referencedColumnName="id", onDelete="CASCADE", unique=true, nullable=false)
     *
     * @Groups({ContextGroup::USER_ADDRESS_READ, ContextGroup::USER_ADDRESS_WRITE})
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

    public function getStreet(): string
    {
        return $this->street;
    }

    public function setStreet(string $street): self
    {
        $this->street = $street;

        return $this;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getState(): string
    {
        return $this->state;
    }

    public function setState(string $state): self
    {
        $this->state = $state;

        return $this;
    }

    public function getCountry(): string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getPostalCode(): string
    {
        return $this->postalCode;
    }

    public function setPostalCode(string $postalCode): self
    {
        $this->postalCode = $postalCode;

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
