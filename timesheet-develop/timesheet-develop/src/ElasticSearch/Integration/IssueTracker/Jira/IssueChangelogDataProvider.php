<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\ElasticSearch\Integration\IssueTracker\Jira;

use FOS\ElasticaBundle\Provider\PagerfantaPager;
use FOS\ElasticaBundle\Provider\PagerInterface;
use FOS\ElasticaBundle\Provider\PagerProviderInterface;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;

/**
 * We populate history index during migration. Current provider is a stub to prevent issues during elastic indices
 * population via fos:elastica:populate command
 */
final class IssueChangelogDataProvider implements PagerProviderInterface
{
    /**
     * @param array<mixed> $options
     */
    public function provide(array $options = []): PagerInterface
    {
        return new PagerfantaPager(
            new Pagerfanta(new ArrayAdapter([]))
        );
    }
}
