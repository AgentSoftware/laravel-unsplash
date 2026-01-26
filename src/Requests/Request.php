<?php

declare(strict_types=1);

namespace AgentSoftware\Unsplash\Requests;

use AgentSoftware\Unsplash\Contracts\UnsplashRequest;

abstract class Request implements UnsplashRequest
{
    public function method(): string
    {
        return 'GET';
    }
}
