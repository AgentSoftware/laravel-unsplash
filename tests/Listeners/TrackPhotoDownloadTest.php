<?php

declare(strict_types=1);

use AgentSoftware\Unsplash\Contracts\UnsplashClient;
use AgentSoftware\Unsplash\Contracts\UnsplashRequest;
use AgentSoftware\Unsplash\Contracts\UnsplashResponse;
use AgentSoftware\Unsplash\Listeners\TrackPhotoDownload;

final class NullUnsplashClient implements UnsplashClient
{
    public function send(UnsplashRequest $request): UnsplashResponse
    {
        throw new RuntimeException('Not used in this test.');
    }
}

it('sets the queue from UnsplashConfig', function (): void {
    config()->set('unsplash.access_key', 'test-key');
    config()->set('unsplash.downloads.queue', 'downloads');

    app()->instance(UnsplashClient::class, new NullUnsplashClient);

    $listener = app()->make(TrackPhotoDownload::class);

    expect($listener->queue)->toBe('downloads');
});

it('leaves the queue unset when downloads queue is empty', function (): void {
    config()->set('unsplash.access_key', 'test-key');
    config()->set('unsplash.downloads.queue', '');

    app()->instance(UnsplashClient::class, new NullUnsplashClient);

    $listener = app()->make(TrackPhotoDownload::class);

    expect($listener->queue)->toBeNull();
});
