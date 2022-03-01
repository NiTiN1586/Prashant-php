<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Integration\Application\GitManagement\GitLab\Converter;

final class GitCommitResponseConverter implements ConverterInterface
{
    /**
     * {@inheritdoc}
     */
    public function convert(array $source, array &$destination): void
    {
        foreach ($source as $item) {
            $destination[] = [
                'branch' => $item['ref_name'] ?? null,
                'createdAt' => $item['authored_date'] ?? null,
                'committedAt' => $item['committed_date'] ?? null,
                'shortId' => $item['short_id'] ?? '',
                'id' => $item['id'] ?? '',
                'title' => $item['title'],
                'url' => $item['web_url'],
            ];
        }
    }
}
