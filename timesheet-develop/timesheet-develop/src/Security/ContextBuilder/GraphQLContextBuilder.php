<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Security\ContextBuilder;

use ApiPlatform\Core\GraphQl\Serializer\SerializerContextBuilderInterface;

final class GraphQLContextBuilder implements SerializerContextBuilderInterface
{
    /** @var iterable<SerializationGroupAssetInterface> */
    private iterable $serializationGroupAssets;
    private SerializerContextBuilderInterface $decorated;

    /**
     * @param iterable<SerializationGroupAssetInterface> $serializationGroupAssets
     */
    public function __construct(SerializerContextBuilderInterface $decorated, iterable $serializationGroupAssets)
    {
        $this->decorated = $decorated;
        $this->serializationGroupAssets = $serializationGroupAssets;
    }

    /**
     * @param array<mixed> $resolverContext
     *
     * @return array<mixed>
     */
    public function create(string $resourceClass, string $operationName, array $resolverContext, bool $normalization): array
    {
        $context = $this->decorated->create($resourceClass, $operationName, $resolverContext, $normalization);

        if ($normalization && isset($context['groups'])) {
            foreach ($this->serializationGroupAssets as $serializationGroupAsset) {
                if ($serializationGroupAsset->support($resourceClass)) {
                    $serializationGroupAsset->process($context['groups']);

                    return $context;
                }
            }
        }

        return $context;
    }
}
