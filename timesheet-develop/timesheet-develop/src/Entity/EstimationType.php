<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Jagaad\WitcherApi\Entity\Traits\DictionaryEntityTrait;
use Jagaad\WitcherApi\Enum\ContextGroup;
use Jagaad\WitcherApi\Security\ReadableResourceInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ApiResource(
 *     normalizationContext={"groups"={ContextGroup::GROUP_GENERAL_DICTIONARY_READ}},
 *     collectionOperations={"get"},
 *     itemOperations={"get"},
 *     graphql={"item_query", "collection_query"}
 * )
 *
 * @ORM\Entity()
 * @UniqueEntity(fields={"handle"}, message="Estimation type with such handle exists")
 * @ORM\Table(
 *     name="estimation_type",
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(name="UK_estimation_type_handle", fields={"handle"}),
 *     },
 *     indexes={
 *         @ORM\Index(name="IDX_estimation_type_createdAt", fields={"createdAt"}),
 *         @ORM\Index(name="IDX_estimation_type_deletedAt", fields={"deletedAt"}),
 *     }
 * )
 *
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 *
 * @ORM\HasLifecycleCallbacks()
 */
class EstimationType implements ReadableResourceInterface
{
    use DictionaryEntityTrait;
    use SoftDeleteableEntity;
    use TimestampableEntity;

    public const ESTIMATION_SP = 'SP';
    public const ESTIMATION_TIME = 'TIME';

    public function isStoryPointEstimationType(): bool
    {
        return self::ESTIMATION_SP === $this->handle;
    }

    public function isTimeEstimationType(): bool
    {
        return self::ESTIMATION_TIME === $this->handle;
    }
}
