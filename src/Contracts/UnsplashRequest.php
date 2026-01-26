<?php

declare(strict_types=1);

namespace AgentSoftware\Unsplash\Contracts;

interface UnsplashRequest
{
    public function method(): string;

    public function endpoint(): string;

    /**
     * @return array<string, mixed>
     */
    public function query(): array;
}
