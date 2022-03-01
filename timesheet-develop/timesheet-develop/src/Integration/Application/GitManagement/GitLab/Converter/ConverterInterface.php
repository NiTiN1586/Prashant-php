<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Integration\Application\GitManagement\GitLab\Converter;

interface ConverterInterface
{
    /**
     * @param array<string|int, mixed> $source
     * @param array<string, mixed> $destination
     */
    public function convert(array $source, array &$destination): void;
}
