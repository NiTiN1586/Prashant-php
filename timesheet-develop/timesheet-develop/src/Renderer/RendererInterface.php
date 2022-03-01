<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Renderer;

interface RendererInterface
{
    public function renderError(string $message): void;

    public function renderNotice(string $message): void;
}
