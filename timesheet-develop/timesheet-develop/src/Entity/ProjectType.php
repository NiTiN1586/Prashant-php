<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Jagaad\WitcherApi\Entity\Traits\DictionaryEntityTrait;
use Jagaad\WitcherApi\Enum\ContextGroup;
use Jagaad\WitcherApi\Security\ReadableResourceInterface;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     normalizationContext={"groups"={ContextGroup::GROUP_GENERAL_DICTIONARY_READ}},
 *     denormalizationContext={"groups"={ContextGroup::GROUP_GENERAL_DICTIONARY_WRITE, ContextGroup::GROUP_PROJECT_TYPE_WRITE}},
 *     itemOperations={
 *       "get",
 *       "patch",
 *       "delete"
 *     }
 * )
 *
 * @ORM\Entity()
 * @ORM\Table(
 *     name="project_type",
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(name="UK_project_type_handle", fields={"handle"}),
 *     },
 *     indexes={
 *         @ORM\Index(name="IDX_project_type_displayOrder", fields={"displayOrder"}),
 *         @ORM\Index(name="IDX_project_type_createdAt", fields={"createdAt"}),
 *         @ORM\Index(name="IDX_project_type_deletedAt", fields={"deletedAt"}),
 *         @ORM\Index(name="IDX_project_type_businessBranch", fields={"businessBranch"}),
 *     }
 * )
 *
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 *
 * @ApiFilter(SearchFilter::class, properties={"businessBranch.handle"})
 * @ApiFilter(OrderFilter::class, properties={"handle", "businessBranch.handle"})
 *
 * @ORM\HasLifecycleCallbacks()
 */
class ProjectType implements ReadableResourceInterface
{
    use DictionaryEntityTrait;
    use SoftDeleteableEntity;

    /**
     * @ORM\ManyToOne(targetEntity=BusinessBranch::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="business_branch", referencedColumnName="id", onDelete="CASCADE", nullable=false)
     *
     * @Groups({ContextGroup::GROUP_GENERAL_DICTIONARY_READ, ContextGroup::GROUP_PROJECT_TYPE_WRITE})
     */
    private BusinessBranch $businessBranch;

    public function getBusinessBranch(): BusinessBranch
    {
        return $this->businessBranch;
    }

    public function setBusinessBranch(BusinessBranch $businessBranch): self
    {
        $this->businessBranch = $businessBranch;

        return $this;
    }
}
