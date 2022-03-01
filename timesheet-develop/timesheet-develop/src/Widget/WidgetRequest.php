<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Widget;

use Webmozart\Assert\Assert;

final class WidgetRequest
{
    /** @var array<string, mixed> */
    private array $params = [];

    /**
     * @param array<string, mixed> $params
     */
    public static function fromParams(array $params): self
    {
        $widgetRequest = new self();

        foreach ($params as $name => $value) {
            $widgetRequest->setParam($name, $value);
        }

        return $widgetRequest;
    }

    public function setParam(string $name, mixed $value): self
    {
        Assert::stringNotEmpty($name, 'Parameter name is required');
        $this->params[$name] = $value;

        return $this;
    }

    public function getParam(string $name): mixed
    {
        return $this->params[$name] ?? null;
    }
}
