<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Jagaad\WitcherApi\Entity\Traits\EntityAuthorTrait;
use Jagaad\WitcherApi\Enum\ContextGroup;
use Jagaad\WitcherApi\Security\ReadableResourceInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *     normalizationContext={"groups"={ContextGroup::GROUP_CLIENT_READ, ContextGroup::GROUP_GENERIC_TRAIT_READ}},
 *     denormalizationContext={"groups"={ContextGroup::GROUP_CLIENT_WRITE}},
 *     itemOperations={
 *       "get",
 *       "patch",
 *       "delete"
 *     }
 * )
 *
 * @ORM\Entity()
 * @ORM\Table(
 *     name="client",
 *     indexes={
 *         @ORM\Index(name="IDX_client_createdAt", fields={"createdAt"}),
 *         @ORM\Index(name="IDX_client_deletedAt", fields={"deletedAt"}),
 *         @ORM\Index(name="IDX_client_country", fields={"country"}),
 *         @ORM\Index(name="IDX_client_industry", fields={"industry"}),
 *         @ORM\Index(name="IDX_client_currency", fields={"currency"}),
 *         @ORM\Index(name="IDX_client_updatedBy", fields={"updatedBy"}),
 *         @ORM\Index(name="IDX_client_createdBy", fields={"createdBy"}),
 *     }
 * )
 *
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 *
 * @ORM\HasLifecycleCallbacks
 */
class Client implements EntityAuthorInterface, ReadableResourceInterface
{
    use EntityAuthorTrait;
    use SoftDeleteableEntity;
    use TimestampableEntity;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="id", type="integer")
     *
     * @ApiProperty(identifier=true)
     */
    private int $id;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *             "type"="string",
     *             "example"="client_name"
     *         }
     *     }
     * )
     *
     * @ORM\Column(name="name", type="string", length=50, nullable=false)
     *
     * @Assert\NotBlank()
     * @Assert\Length(
     *      max = 50,
     *      maxMessage = "Client name cannot be longer than {{ limit }} characters",
     * )
     *
     * @Groups({ContextGroup::GROUP_CLIENT_READ, ContextGroup::GROUP_CLIENT_WRITE})
     */
    private string $name;

    /**
     * @ORM\ManyToOne(targetEntity=Country::class, cascade={"persist", "remove"}, inversedBy="clients")
     * @ORM\JoinColumn(name="country", referencedColumnName="id", onDelete="SET NULL", nullable=true)
     *
     * @Groups({ContextGroup::GROUP_CLIENT_READ, ContextGroup::GROUP_CLIENT_WRITE})
     */
    private ?Country $country;

    /**
     * @ORM\ManyToOne(targetEntity=Industry::class, cascade={"persist", "remove"}, inversedBy="clients")
     * @ORM\JoinColumn(name="industry", referencedColumnName="id", onDelete="SET NULL", nullable=true)
     *
     * @Groups({ContextGroup::GROUP_CLIENT_READ, ContextGroup::GROUP_CLIENT_WRITE})
     */
    private ?Industry $industry;

    /**
     * @ORM\ManyToOne(targetEntity=Currency::class, cascade={"persist", "remove"}, inversedBy="clients")
     * @ORM\JoinColumn(name="currency", referencedColumnName="id", onDelete="SET NULL", nullable=true)
     *
     * @Groups({ContextGroup::GROUP_CLIENT_READ, ContextGroup::GROUP_CLIENT_WRITE})
     */
    private ?Currency $currency;

    /**
     * @var Collection<int, WitcherProject>
     *
     * @ORM\OneToMany(targetEntity=WitcherProject::class, mappedBy="client", cascade={"persist"})
     */
    private Collection $witcherProjects;

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCountry(): ?Country
    {
        return $this->country;
    }

    public function setCountry(?Country $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getIndustry(): ?Industry
    {
        return $this->industry;
    }

    public function setIndustry(?Industry $industry): self
    {
        $this->industry = $industry;

        return $this;
    }

    public function getCurrency(): ?Currency
    {
        return $this->currency;
    }

    public function setCurrency(?Currency $currency): self
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * @return Collection<int, WitcherProject>
     */
    public function getWitcherProjects(): Collection
    {
        return $this->witcherProjects;
    }

    /**
     * @param Collection<int, WitcherProject> $witcherProjects
     */
    public function setWitcherProjects(Collection $witcherProjects): self
    {
        $this->witcherProjects = $witcherProjects;

        return $this;
    }
}
