<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Widget;

interface WidgetCalculationInterface
{
    public function getCalculatedWidget(WidgetRequest $request): ?object;
}
