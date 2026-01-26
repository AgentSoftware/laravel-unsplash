<?php

declare(strict_types=1);

namespace AgentSoftware\Unsplash\Contracts;

interface UnsplashResponse
{
    /**
     * @return array<string, mixed>|array<int, mixed>
     */
    public function data(): array;

    public function status(): int;

    /**
     * @return array<string, array<int, string>>
     */
    public function headers(): array;

    public function rateLimitLimit(): ?int;

    public function rateLimitRemaining(): ?int;
}
