<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Integration\Application\Transformer;

use Jagaad\WitcherApi\Integration\Application\DTO\Request;

interface RequestTransformerInterface
{
    /**
     * @return array<string, mixed>
     */
    public function transform(Request $request): array;
}
