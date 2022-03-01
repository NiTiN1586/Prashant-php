<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Integration\Repository\GitManagement;

use Jagaad\WitcherApi\Integration\Application\DTO\Request;

interface EventReadApiInterface
{
    /**
     * @param Request $request
     *
     * @return iterable<object>
     */
    public function getEvents(Request $request): iterable;
}
