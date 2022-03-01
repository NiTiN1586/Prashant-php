<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Integration\Application\GitManagement\GitLab\Repository;

use Gitlab\Client;
use Jagaad\WitcherApi\ApiResource\Commit;
use Jagaad\WitcherApi\Integration\Application\DTO\Request;
use Jagaad\WitcherApi\Integration\Application\GitManagement\GitLab\Converter\ConverterInterface;
use Jagaad\WitcherApi\Integration\Application\Transformer\RequestTransformerInterface;
use Jagaad\WitcherApi\Integration\Repository\GitManagement\ProjectGitCommitReadApiInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

final class ProjectGitCommitRepository implements ProjectGitCommitReadApiInterface
{
    public function __construct(
        private Client $gitlabClient,
        private DenormalizerInterface $denormalizer,
        private RequestTransformerInterface $transformer,
        private ConverterInterface $converter
    ) {
    }

    /**
     * @return Commit[]
     */
    public function getProjectCommits(Request $request): array
    {
        $id = $request->getRequestParam('id');
        $commits = [];

        if ('' === $id || !\is_numeric($id)) {
            throw new \InvalidArgumentException('Project Id is required parameter');
        }

        $this->converter->convert(
            $this->gitlabClient->repositories()->commits($id, $this->transformer->transform($request)),
            $commits
        );

        return $this->denormalizer->denormalize($commits, Commit::class.'[]');
    }
}
