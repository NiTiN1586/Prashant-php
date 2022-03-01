<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Security;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Annotations\Reader;

final class SupportedResource implements SupportedResourceInterface
{
    private Reader $reader;

    public function __construct(Reader $reader)
    {
        $this->reader = $reader;
    }

    /**
     * {@inheritdoc}
     */
    public function supports(string|object $class): bool
    {
        try {
            return null !== $this->reader->getClassAnnotation(new \ReflectionClass($class), ApiResource::class);
        } catch (\Throwable $exception) {
            return false;
        }
    }
}
