<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Jagaad\WitcherApi\Entity\Traits\DictionaryEntityTrait;
use Jagaad\WitcherApi\Enum\ContextGroup;
use Jagaad\WitcherApi\Security\ReadableResourceInterface;

/**
 * @ApiResource(
 *     normalizationContext={"groups"={ContextGroup::GROUP_GENERAL_DICTIONARY_READ}},
 *     denormalizationContext={"groups"={ContextGroup::GROUP_GENERAL_DICTIONARY_WRITE}},
 *     itemOperations={
 *       "get",
 *       "patch",
 *       "delete"
 *     }
 * )
 *
 * @ORM\Entity()
 * @ORM\Table(
 *     name="business_branch",
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(name="UK_business_branch_handle", fields={"handle"}),
 *     },
 *     indexes={
 *         @ORM\Index(name="IDX_business_branch_displayOrder", fields={"displayOrder"}),
 *         @ORM\Index(name="IDX_business_branch_createdAt", fields={"createdAt"}),
 *         @ORM\Index(name="IDX_business_branch_deletedAt", fields={"deletedAt"}),
 *     }
 * )
 *
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 *
 * @ORM\HasLifecycleCallbacks()
 */
class BusinessBranch implements ReadableResourceInterface
{
    use DictionaryEntityTrait;
    use SoftDeleteableEntity;
}
