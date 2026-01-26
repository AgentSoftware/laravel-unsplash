<?php

declare(strict_types=1);

namespace AgentSoftware\Unsplash\Exceptions;

final class UnsplashApiException extends UnsplashException
{
    /**
     * @param  array<string, mixed>  $errors
     */
    public function __construct(
        string $message,
        public readonly int $statusCode,
        public readonly array $errors = [],
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, $statusCode, $previous);
    }

    /**
     * @param  array<string, mixed>  $response
     */
    public static function fromResponse(int $statusCode, array $response): self
    {
        $errors = isset($response['errors']) && is_array($response['errors']) ? $response['errors'] : [];
        $message = self::extractMessage($statusCode, $errors);

        return new self($message, $statusCode, $errors);
    }

    /**
     * @param  array<string, mixed>  $errors
     */
    private static function extractMessage(int $statusCode, array $errors): string
    {
        if ($errors !== []) {
            $firstError = reset($errors);
            if (is_string($firstError)) {
                return $firstError;
            }
        }

        return match ($statusCode) {
            400 => 'Bad request',
            401 => 'Unauthorized - Invalid or missing access key',
            403 => 'Forbidden - Access denied',
            404 => 'Resource not found',
            500, 503 => 'Unsplash server error',
            default => "Unsplash API error (HTTP {$statusCode})",
        };
    }
}
