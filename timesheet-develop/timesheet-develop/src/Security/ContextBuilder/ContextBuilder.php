<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Security\ContextBuilder;

use ApiPlatform\Core\Serializer\SerializerContextBuilderInterface;
use Symfony\Component\HttpFoundation\Request;

final class ContextBuilder implements SerializerContextBuilderInterface
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
     * @param array<mixed>|null $extractedAttributes
     *
     * @return array<mixed>
     */
    public function createFromRequest(Request $request, bool $normalization, array $extractedAttributes = null): array
    {
        $context = $this->decorated->createFromRequest($request, $normalization, $extractedAttributes);
        $resourceClass = $context['resource_class'] ?? null;

        if ($normalization && isset($context['groups']) && null !== $resourceClass) {
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
