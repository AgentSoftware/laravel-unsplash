<?php

declare(strict_types=1);

namespace AgentSoftware\Unsplash\Exceptions;

final class InvalidConfigurationException extends UnsplashException
{
    public static function missingAccessKey(): self
    {
        return new self('Unsplash access key is required. Set UNSPLASH_ACCESS_KEY in your environment.');
    }
}
