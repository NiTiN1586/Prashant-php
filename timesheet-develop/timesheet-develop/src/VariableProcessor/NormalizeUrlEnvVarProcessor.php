<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\VariableProcessor;

use Symfony\Component\DependencyInjection\EnvVarProcessorInterface;

final class NormalizeUrlEnvVarProcessor implements EnvVarProcessorInterface
{
    public function getEnv(string $prefix, string $name, \Closure $getEnv)
    {
        return \rtrim($getEnv($name), '/');
    }

    /**
     * @return array<string, string>
     */
    public static function getProvidedTypes(): array
    {
        return [
            'normalize_url' => 'string',
        ];
    }
}
