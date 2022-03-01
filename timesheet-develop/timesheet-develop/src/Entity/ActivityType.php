<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Jagaad\WitcherApi\Entity\Traits\DictionaryEntityTrait;
use Jagaad\WitcherApi\Enum\ContextGroup;
use Jagaad\WitcherApi\Security\ReadableResourceInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ApiResource(
 *     normalizationContext={"groups"={ContextGroup::GROUP_ACTIVITY_READ, ContextGroup::GROUP_GENERAL_DICTIONARY_READ}},
 *     denormalizationContext={"groups"={ContextGroup::GROUP_GENERAL_DICTIONARY_WRITE}},
 *     itemOperations={"get", "patch", "delete"},
 * )
 *
 * @ORM\Entity()
 * @UniqueEntity(fields={"handle"}, message="Activity type with such handle exists")
 * @ORM\Table(
 *     name="activity_type",
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(name="UK_activity_type_handle", fields={"handle"}),
 *     },
 *     indexes={
 *         @ORM\Index(name="IDX_activity_type_displayOrder", fields={"displayOrder"}),
 *         @ORM\Index(name="IDX_activity_type_createdAt", fields={"createdAt"}),
 *         @ORM\Index(name="IDX_activity_type_deletedAt", fields={"deletedAt"}),
 *     }
 * )
 *
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 *
 * @ORM\HasLifecycleCallbacks()
 */
class ActivityType implements ReadableResourceInterface
{
    use DictionaryEntityTrait;
    use SoftDeleteableEntity;

    /**
     * @var Collection<int, Activity>
     *
     * @ORM\OneToMany(targetEntity=Activity::class, mappedBy="activityType", cascade={"persist"})
     */
    private Collection $activities;

    public static function create(string $handle, string $name): self
    {
        $activityType = new self();

        $activityType->friendlyName = $name;
        $activityType->handle = $handle;

        return $activityType;
    }

    /**
     * @return Activity[]
     */
    public function getActivities(): array
    {
        return $this->activities->getValues();
    }

    public function addActivity(Activity $activity): self
    {
        if (!$this->activities->contains($activity)) {
            $this->activities->add($activity);
        }

        return $this;
    }

    public function deleteActivity(Activity $activity): self
    {
        if ($this->activities->contains($activity)) {
            $this->activities->removeElement($activity);
        }

        return $this;
    }
}
