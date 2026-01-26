<?php

declare(strict_types=1);

namespace AgentSoftware\Unsplash\Facades;

use AgentSoftware\Unsplash\Contracts\UnsplashRequest;
use AgentSoftware\Unsplash\Contracts\UnsplashResponse;
use AgentSoftware\Unsplash\Photos\Photos;
use Illuminate\Support\Facades\Facade;

/**
 * @method static UnsplashResponse send(UnsplashRequest $request)
 * @method static Photos photos()
 *
 * @see \AgentSoftware\Unsplash\Unsplash
 */
class Unsplash extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'unsplash';
    }
}
