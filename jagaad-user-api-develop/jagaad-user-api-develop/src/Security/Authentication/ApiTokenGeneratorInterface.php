<?php

declare(strict_types=1);

namespace Jagaad\UserApi\Security\Authentication;

interface ApiTokenGeneratorInterface
{
    public function generateTokenForApp(string $application): string;

    public function rotateTokenForApp(string $application): string;
}
