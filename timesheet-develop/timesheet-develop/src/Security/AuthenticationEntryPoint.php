<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Security;

use Jagaad\WitcherApi\HttpRequest\HttpHeader;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;

final class AuthenticationEntryPoint implements AuthenticationEntryPointInterface
{
    public function start(Request $request, AuthenticationException $authException = null): Response
    {
        $response = new Response(
            \json_encode(null !== $authException ? $authException->getMessage() : null, \JSON_THROW_ON_ERROR),
            Response::HTTP_UNAUTHORIZED
        );

        $response->headers->set(HttpHeader::CONTENT_TYPE_HEADER, HttpHeader::JSON_CONTENT_TYPE);

        return $response;
    }
}
