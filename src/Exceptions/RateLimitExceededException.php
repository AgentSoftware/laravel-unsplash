<?php

declare(strict_types=1);

namespace AgentSoftware\Unsplash\Exceptions;

use AgentSoftware\Unsplash\Support\HeaderParser;

final class RateLimitExceededException extends UnsplashException
{
    private const HEADER_LIMIT = 'X-Ratelimit-Limit';

    private const HEADER_REMAINING = 'X-Ratelimit-Remaining';

    public function __construct(
        public readonly ?int $limit = null,
        public readonly ?int $remaining = null,
        string $message = 'Rate limit exceeded',
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, 429, $previous);
    }

    /**
     * @param  array<string, array<int, string>|string>  $headers
     */
    public static function fromHeaders(array $headers): self
    {
        $limit = HeaderParser::getInt($headers, self::HEADER_LIMIT);
        $remaining = HeaderParser::getInt($headers, self::HEADER_REMAINING);

        $message = 'Rate limit exceeded';
        if ($limit !== null) {
            $message .= " (limit: {$limit}/hour)";
        }

        return new self($limit, $remaining, $message);
    }
}
