<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Integration\Application\Renderer;

use Jagaad\WitcherApi\Renderer\RendererInterface;
use Psr\Log\LoggerInterface;

final class ConsoleMessageRenderer implements RendererInterface
{
    private LoggerInterface $consoleNotifier;

    public function __construct(LoggerInterface $consoleNotifier)
    {
        $this->consoleNotifier = $consoleNotifier;
    }

    public function renderError(string $message): void
    {
        $this->consoleNotifier->alert(\sprintf('<error>%s</error>', $message));
    }

    public function renderNotice(string $message): void
    {
        $this->consoleNotifier->alert(\sprintf('<comment>%s</comment>', $message));
    }
}
