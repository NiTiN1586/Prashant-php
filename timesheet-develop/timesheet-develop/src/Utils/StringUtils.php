<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Utils;

class StringUtils
{
    public static function convertNameToHandle(string $name): string
    {
        $handle = \preg_replace(
            '/\W+/',
            '_',
            \strtoupper(\trim($name))
        );

        if (!\is_string($handle)) {
            throw new \UnexpectedValueException('Handle can\'t be built.');
        }

        return $handle;
    }

    public static function convertRoleNameToHandle(string $name): string
    {
        $roleHandle = \preg_replace(
            '/[^A-Z ]|(ROLE)/i', '',
            \strtoupper($name)
        );

        if (!\is_string($roleHandle)) {
            throw new \UnexpectedValueException('Handle can\'t be built.');
        }

        $roleHandle = \strtok(\trim($roleHandle), ' ');

        if (!\is_string($roleHandle)) {
            throw new \UnexpectedValueException('Handle can\'t be built.');
        }

        return \sprintf('ROLE_%s', $roleHandle);
    }
}
