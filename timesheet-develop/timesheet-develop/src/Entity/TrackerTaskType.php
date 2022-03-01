<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Jagaad\WitcherApi\Entity\Traits\DictionaryEntityTrait;
use Jagaad\WitcherApi\Enum\ContextGroup;
use Jagaad\WitcherApi\Security\ReadableResourceInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ApiResource(
 *     normalizationContext={"groups"={ContextGroup::GROUP_GENERAL_DICTIONARY_READ}},
 *     denormalizationContext={"groups"={ContextGroup::GROUP_GENERAL_DICTIONARY_WRITE}},
 *     itemOperations={
 *       "get",
 *       "patch",
 *       "delete"
 *     }
 *  )
 *
 * @ORM\Entity()
 * @UniqueEntity(fields={"handle"}, message="Tracker task type with such handle exists")
 * @ORM\Table(
 *     name="tracker_task_type",
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(name="UK_tracker_task_type_handle", fields={"handle"}),
 *     },
 *     indexes={
 *         @ORM\Index(name="IDX_tracker_task_type_createdAt", fields={"createdAt"}),
 *     }
 * )
 *
 * @ORM\HasLifecycleCallbacks()
 */
class TrackerTaskType implements ReadableResourceInterface
{
    use DictionaryEntityTrait;
    use TimestampableEntity;

    public static function create(string $handle, string $name): self
    {
        $trackerTaskType = new self();

        $trackerTaskType->friendlyName = $name;
        $trackerTaskType->handle = $handle;

        return $trackerTaskType;
    }
}
