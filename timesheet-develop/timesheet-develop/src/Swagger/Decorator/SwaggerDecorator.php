<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Swagger\Decorator;

use Jagaad\WitcherApi\DataLoader\YamlDataLoader;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class SwaggerDecorator implements NormalizerInterface
{
    private NormalizerInterface $decorated;

    private YamlDataLoader $yamlDataLoader;

    public function __construct(NormalizerInterface $decorated, YamlDataLoader $yamlDataLoader)
    {
        $this->decorated = $decorated;
        $this->yamlDataLoader = $yamlDataLoader;
    }

    /**
     * @param mixed $object
     * @param string|null $format
     * @param array<mixed> $context
     *
     * @return array<array<int, array<mixed>>>
     *
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function normalize($object, ?string $format = null, array $context = []): array
    {
        $normalizedData = \is_array($this->decorated->normalize($object, $format, $context))
            ? $this->decorated->normalize($object, $format, $context)
            : [];

        return \array_merge_recursive(
            $normalizedData,
            $this->yamlDataLoader->getApiCustomOperationsDocumentationData() ?? []
        );
    }

    /**
     * {@inheritDoc}
     */
    public function supportsNormalization($data, ?string $format = null): bool
    {
        return $this->decorated->supportsNormalization($data, $format);
    }
}
