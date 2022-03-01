<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\QueryFilter;

use Doctrine\Common\Annotations\Reader;
use Doctrine\ORM\EntityManagerInterface;
use Gedmo\SoftDeleteable\Filter\SoftDeleteableFilter;
use Jagaad\WitcherApi\Annotation\IgnoreFilter;

final class IgnoreSoftdeletableQueryFilter implements QueryFilterInterface
{
    public function __construct(private Reader $annotationReader, private EntityManagerInterface $entityManager)
    {
    }

    public function filter(string $resource): void
    {
        $annotation = $this->annotationReader->getClassAnnotation(
            new \ReflectionClass($resource),
            IgnoreFilter::class
        );

        if ($annotation instanceof IgnoreFilter) {
            $filter = $this->entityManager->getFilters()->enable($annotation->filter);

            if ($filter instanceof SoftDeleteableFilter) {
                foreach ($annotation->forClasses as $ignoredClass) {
                    $filter->disableForEntity($ignoredClass);
                }
            }
        }
    }
}
