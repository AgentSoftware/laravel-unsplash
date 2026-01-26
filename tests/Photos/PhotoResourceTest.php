<?php

declare(strict_types=1);

use AgentSoftware\Unsplash\Contracts\UnsplashClient;
use AgentSoftware\Unsplash\Contracts\UnsplashRequest;
use AgentSoftware\Unsplash\Contracts\UnsplashResponse;
use AgentSoftware\Unsplash\Exceptions\InvalidRequestParameter;
use AgentSoftware\Unsplash\Models\Photo\Photo;
use AgentSoftware\Unsplash\Photos\PhotoResource;
use AgentSoftware\Unsplash\Photos\Photos;

it('throws when download location is missing', function (): void {
    $photo = Photo::fromArray([
        'id' => 'photo-1',
        'links' => [],
    ]);

    $client = new class implements UnsplashClient
    {
        public function send(UnsplashRequest $request): UnsplashResponse
        {
            throw new RuntimeException('Not used in this test.');
        }
    };
    $photos = new Photos($client);
    $resource = new PhotoResource($photo, $photos);

    expect(fn () => $resource->trackDownload())
        ->toThrow(InvalidRequestParameter::class, 'Photo does not include a download location.');
});
