<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Jagaad\WitcherApi\Entity\Traits\DictionaryEntityTrait;
use Jagaad\WitcherApi\Security\ReadableResourceInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

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
 * @UniqueEntity(fields={"handle"}, message="Status with such handle exists")
 * @ORM\Table(
 *     name="status",
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(name="UK_status_handle", fields={"handle"}),
 *     },
 *     indexes={
 *         @ORM\Index(name="IDX_status_displayOrder", fields={"displayOrder"}),
 *         @ORM\Index(name="IDX_status_createdAt", fields={"createdAt"}),
 *         @ORM\Index(name="IDX_status_deletedAt", fields={"deletedAt"}),
 *     }
 * )
 *
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 *
 * @ORM\HasLifecycleCallbacks()
 */
class Status implements ReadableResourceInterface
{
    use DictionaryEntityTrait;
    use SoftDeleteableEntity;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *             "type"="string",
     *             "example"="Status Description"
     *         }
     *     }
     * )
     *
     * @ORM\Column(name="description", type="string", length=200, nullable=false)
     *
     * @Assert\NotNull()
     * @Assert\Length(
     *      max = 200,
     *      maxMessage = "Description cannot be longer than {{ limit }} characters",
     * )
     */
    private string $description;

    public static function createFromParams(string $name, string $handle, string $description): self
    {
        $status = new self();
        $status->friendlyName = $name;
        $status->handle = $handle;
        $status->description = $description;

        return $status;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }
}
