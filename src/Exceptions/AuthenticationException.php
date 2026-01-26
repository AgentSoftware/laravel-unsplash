<?php

declare(strict_types=1);

namespace AgentSoftware\Unsplash\Exceptions;

final class AuthenticationException extends UnsplashException
{
    public function __construct(
        string $message = 'Unauthorized - Invalid or missing access key',
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, 401, $previous);
    }
}
