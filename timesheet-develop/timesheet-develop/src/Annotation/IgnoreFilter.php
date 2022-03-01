<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Annotation;

use Doctrine\Common\Annotations\Annotation\Attribute;
use Doctrine\Common\Annotations\Annotation\Attributes;
use Doctrine\Common\Annotations\Annotation\Target;

/**
 * @Annotation
 * @Target({"CLASS"})
 *
 * @Attributes({
 *  @Attribute("filter", type="string"),
 *  @Attribute("forClasses",  type="array"),
 * })
 */
final class IgnoreFilter
{
    /** @Required */
    public string $filter;

    /** @var string[] */
    public array $forClasses = [];
}
