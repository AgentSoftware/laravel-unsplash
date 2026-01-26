<?php

declare(strict_types=1);

namespace AgentSoftware\Unsplash\Exceptions;

final class NotFoundException extends UnsplashException
{
    public function __construct(
        string $message = 'Resource not found',
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, 404, $previous);
    }
}
