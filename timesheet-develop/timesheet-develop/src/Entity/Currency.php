<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Jagaad\WitcherApi\Entity\Traits\DictionaryEntityTrait;

/**
 * TODO: moneyphp/money bundle should be used instead. Will be changed in related tickets
 *
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
 *     name="currency",
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(name="UK_currency_handle", fields={"handle"}),
 *     },
 *     indexes={
 *         @ORM\Index(name="IDX_currency_displayOrder", fields={"displayOrder"}),
 *         @ORM\Index(name="IDX_currency_createdAt", fields={"createdAt"}),
 *         @ORM\Index(name="IDX_currency_deletedAt", fields={"deletedAt"}),
 *     }
 * )
 *
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 *
 * @ORM\HasLifecycleCallbacks()
 */
class Currency
{
    use DictionaryEntityTrait;
    use SoftDeleteableEntity;

    /**
     * @var Collection<int, Client>
     *
     * @ORM\OneToMany(targetEntity=Client::class, mappedBy="currency", cascade={"persist", "remove"})
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
