<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Repository;

use Assert\Assertion;
use Doctrine\Persistence\ManagerRegistry;
use Jagaad\WitcherApi\Entity\Label;

/**
 * @template-extends AbstractRepository<Label>
 */
final class LabelRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, Label::class);
    }

    /**
     * @param string[] $names
     * @param bool $active
     *
     * @return string[]
     */
    public function findLabelAsStringListByNames(array $names, bool $active = true): array
    {
        Assertion::allString($names, 'Label names should be array of strings');

        $this->softDeleteFilterAction($active);

        $labels = $this->createQueryBuilder('l')
            ->select('l.name')
            ->andWhere('l.name IN (:labels)')
            ->setParameter('labels', $names)
            ->getQuery()
            ->getResult();

        $this->softDeleteFilterAction();

        return \array_column($labels, 'name');
    }

    /**
     * @param string[] $names
     *
     * @return array<string, Label>
     */
    public function findByNames(array $names, bool $active = true): array
    {
        Assertion::allString($names, 'Label names should be array of strings');

        $this->softDeleteFilterAction($active);
        $results = [];

        /** @var Label[] $labels */
        $labels = $this->createQueryBuilder('l')
            ->andWhere('l.name IN (:labels)')
            ->setParameter('labels', $names)
            ->getQuery()
            ->getResult();

        foreach ($labels as $label) {
            $results[$label->getName()] = $label;
        }

        $this->softDeleteFilterAction();

        return $results;
    }
}
