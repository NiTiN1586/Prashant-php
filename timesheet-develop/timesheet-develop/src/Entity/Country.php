<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Jagaad\WitcherApi\Entity\Traits\DictionaryEntityTrait;
use Jagaad\WitcherApi\Security\ReadableResourceInterface;

/**
 * @ApiResource(
 *     itemOperations={
 *       "get",
 *       "patch",
 *       "delete"
 *     }
 * )
 *
 * @ORM\Entity()
 * @ORM\Table(
 *     name="country",
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(name="UK_country_handle", fields={"handle"}),
 *     },
 *     indexes={
 *         @ORM\Index(name="IDX_country_displayOrder", fields={"displayOrder"}),
 *         @ORM\Index(name="IDX_country_createdAt", fields={"createdAt"}),
 *         @ORM\Index(name="IDX_country_deletedAt", fields={"deletedAt"}),
 *     }
 * )
 *
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 *
 * @ORM\HasLifecycleCallbacks
 */
class Country implements ReadableResourceInterface
{
    use DictionaryEntityTrait;
    use SoftDeleteableEntity;
    use TimestampableEntity;

    /**
     * @var Collection<int, Client>
     *
     * @ORM\OneToMany(targetEntity=Client::class, mappedBy="country", cascade={"persist", "remove"})
     */
    private Collection $clients;

    /**
     * @return Collection<int, Client>
     */
    public function getClients(): Collection
    {
        return $this->clients;
    }

    public function setClient(Client $client): self
    {
        if (!$this->clients->contains($client)) {
            $this->clients->add($client);
        }

        return $this;
    }
}
