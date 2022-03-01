<?php

declare(strict_types=1);

namespace Jagaad\UserProviderBundle\HttpRequest;

final class HttpHeader
{
    public const CONTENT_TYPE_HEADER = 'Content-Type';
    public const JSON_CONTENT_TYPE = 'application/json';
    public const PATCH_JSON_CONTENT_TYPE = 'application/merge-patch+json';
}
