<?php

declare(strict_types=1);

namespace AgentSoftware\Unsplash\Contracts;

interface UnsplashClient
{
    public function send(UnsplashRequest $request): UnsplashResponse;
}
