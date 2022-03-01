<?php

declare(strict_types=1);

namespace Jagaad\UserProviderBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class JagaadUserProviderBundle extends Bundle
{
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}