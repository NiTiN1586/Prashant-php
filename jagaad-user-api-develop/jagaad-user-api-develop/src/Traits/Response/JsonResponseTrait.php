<?php

declare(strict_types=1);

namespace Jagaad\UserApi\Traits\Response;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

trait JsonResponseTrait
{
    /**
     * @param mixed $data
     */
    private static function createSuccessfulJsonResponse($data, int $responseCode = Response::HTTP_OK): JsonResponse
    {
        return new JsonResponse([
            'success' => true,
            'data' => $data,
        ], $responseCode);
    }

    /**
     * @param array<mixed> $errorPayload
     */
    private static function createErrorJsonResponse(
        string $message,
        array $errorPayload = [],
        int $responseCode = Response::HTTP_INTERNAL_SERVER_ERROR
    ): JsonResponse {
        return new JsonResponse([
            'success' => false,
            'message' => $message,
            'payload' => $errorPayload,
        ], $responseCode);
    }
}
