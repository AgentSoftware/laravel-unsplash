<?php

declare(strict_types=1);

namespace AgentSoftware\Unsplash;

use AgentSoftware\Unsplash\Contracts\UnsplashClient;
use AgentSoftware\Unsplash\Contracts\UnsplashRequest;
use AgentSoftware\Unsplash\Contracts\UnsplashResponse;
use AgentSoftware\Unsplash\Photos\Photos;

final class Unsplash
{
    public function __construct(
        private readonly UnsplashClient $client,
        private readonly Photos $photos
    ) {}

    public function send(UnsplashRequest $request): UnsplashResponse
    {
        return $this->client->send($request);
    }

    public function photos(): Photos
    {
        return $this->photos;
    }
}
