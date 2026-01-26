<?php

declare(strict_types=1);

namespace AgentSoftware\Unsplash\Http;

use AgentSoftware\Unsplash\Contracts\UnsplashResponse;
use AgentSoftware\Unsplash\Support\HeaderParser;

final class HttpResponse implements UnsplashResponse
{
    private const HEADER_RATELIMIT_LIMIT = 'X-Ratelimit-Limit';

    private const HEADER_RATELIMIT_REMAINING = 'X-Ratelimit-Remaining';

    /**
     * @param  array<string, mixed>|array<int, mixed>  $data
     * @param  array<string, array<int, string>>  $headers
     */
    public function __construct(
        private readonly array $data,
        private readonly int $status,
        private readonly array $headers
    ) {}

    public function data(): array
    {
        return $this->data;
    }

    public function status(): int
    {
        return $this->status;
    }

    public function headers(): array
    {
        return $this->headers;
    }

    public function rateLimitLimit(): ?int
    {
        return HeaderParser::getInt($this->headers, self::HEADER_RATELIMIT_LIMIT);
    }

    public function rateLimitRemaining(): ?int
    {
        return HeaderParser::getInt($this->headers, self::HEADER_RATELIMIT_REMAINING);
    }
}
