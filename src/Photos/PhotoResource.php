<?php

declare(strict_types=1);

namespace AgentSoftware\Unsplash\Photos;

use AgentSoftware\Unsplash\Exceptions\InvalidRequestParameter;
use AgentSoftware\Unsplash\Models\Photo\Photo;
use AgentSoftware\Unsplash\Models\Photo\PhotoDownload;
use AgentSoftware\Unsplash\Requests\Photos\TrackDownloadRequest;

final class PhotoResource
{
    public function __construct(
        private readonly Photo $photo,
        private readonly Photos $photos
    ) {}

    public function photo(): Photo
    {
        return $this->photo;
    }

    public function requestDownload(): void
    {
        $this->photos->requestDownload($this->buildDownloadRequest());
    }

    public function trackDownload(): PhotoDownload
    {
        return $this->photos->trackDownload($this->buildDownloadRequest());
    }

    private function buildDownloadRequest(): TrackDownloadRequest
    {
        $location = $this->photo->links?->downloadLocation;
        if ($location === null || $location === '') {
            throw new InvalidRequestParameter('Photo does not include a download location.');
        }

        return new TrackDownloadRequest($location);
    }
}
