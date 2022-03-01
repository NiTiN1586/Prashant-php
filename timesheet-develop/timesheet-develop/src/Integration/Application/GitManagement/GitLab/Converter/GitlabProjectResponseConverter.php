<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Integration\Application\GitManagement\GitLab\Converter;

final class GitlabProjectResponseConverter implements ConverterInterface
{
    /**
     * {@inheritdoc}
     */
    public function convert(array $source, array &$destination): void
    {
        foreach ($source as $item) {
            $destination[] = [
                'id' => $item['id'],
                'name' => $item['name'],
                'url' => $item['web_url'],
                'description' => $item['description'],
            ];
        }
    }
}
