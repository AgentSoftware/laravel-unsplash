<?php

declare(strict_types=1);

namespace AgentSoftware\Unsplash\Photos;

use AgentSoftware\Unsplash\Contracts\UnsplashClient;
use AgentSoftware\Unsplash\Events\PhotoDownloadRequested;
use AgentSoftware\Unsplash\Mappers\Photo\PhotoCollectionMapper;
use AgentSoftware\Unsplash\Mappers\Photo\PhotoDownloadMapper;
use AgentSoftware\Unsplash\Models\Photo\Photo;
use AgentSoftware\Unsplash\Models\Photo\PhotoCollection;
use AgentSoftware\Unsplash\Models\Photo\PhotoDownload;
use AgentSoftware\Unsplash\Models\Photo\PhotoStatistics;
use AgentSoftware\Unsplash\Requests\Photos\GetPhotoRequest;
use AgentSoftware\Unsplash\Requests\Photos\GetPhotoStatisticsRequest;
use AgentSoftware\Unsplash\Requests\Photos\ListPhotosRequest;
use AgentSoftware\Unsplash\Requests\Photos\RandomPhotoRequest;
use AgentSoftware\Unsplash\Requests\Photos\SearchPhotosRequest;
use AgentSoftware\Unsplash\Requests\Photos\TrackDownloadRequest;
use AgentSoftware\Unsplash\Support\HeaderParser;
use Illuminate\Contracts\Events\Dispatcher;

final class Photos
{
    public function __construct(
        private readonly UnsplashClient $client,
        private readonly ?Dispatcher $events = null,
        private readonly ?PhotoCollectionMapper $collectionMapper = null,
        private readonly ?PhotoDownloadMapper $downloadMapper = null
    ) {}

    public function list(ListPhotosRequest $request): PhotoCollection
    {
        $response = $this->client->send($request);
        $total = HeaderParser::getInt($response->headers(), 'X-Total');
        $perPage = $request->perPage ?? 10;
        $totalPages = $total !== null ? (int) ceil($total / $perPage) : null;

        return $this->collectionMapper()->map($response->data(), $total, $totalPages);
    }

    public function get(GetPhotoRequest $request): PhotoResource
    {
        $response = $this->client->send($request);

        $photo = Photo::fromArray($this->normalizeObjectPayload($response->data()));

        return new PhotoResource($photo, $this);
    }

    public function random(RandomPhotoRequest $request): PhotoCollection
    {
        $response = $this->client->send($request);
        $payload = $response->data();

        if ($this->isCollectionPayload($payload)) {
            return $this->collectionMapper()->map($payload);
        }

        $photo = Photo::fromArray($this->normalizeObjectPayload($payload));

        return new PhotoCollection([$photo]);
    }

    public function search(SearchPhotosRequest $request): PhotoCollection
    {
        $response = $this->client->send($request);

        return $this->collectionMapper()->map($response->data());
    }

    public function statistics(GetPhotoStatisticsRequest $request): PhotoStatistics
    {
        $response = $this->client->send($request);

        return PhotoStatistics::fromArray($this->normalizeObjectPayload($response->data()));
    }

    public function trackDownload(TrackDownloadRequest $request): PhotoDownload
    {
        $response = $this->client->send($request);

        return $this->downloadMapper()->map($this->normalizeObjectPayload($response->data()));
    }

    public function requestDownload(TrackDownloadRequest $request): void
    {
        if ($this->events === null) {
            $this->trackDownload($request);

            return;
        }

        $this->events->dispatch(new PhotoDownloadRequested($request->downloadLocation));
    }

    /**
     * @param  array<int, mixed>|array<string, mixed>  $payload
     */
    private function isCollectionPayload(array $payload): bool
    {
        if ($payload === []) {
            return true;
        }

        $firstKey = array_key_first($payload);

        return $firstKey === 0;
    }

    /**
     * @param  array<int|string, mixed>  $payload
     * @return array<string, mixed>
     */
    private function normalizeObjectPayload(array $payload): array
    {
        $normalized = [];

        foreach ($payload as $key => $value) {
            if (is_string($key)) {
                $normalized[$key] = $value;
            }
        }

        return $normalized;
    }

    private function collectionMapper(): PhotoCollectionMapper
    {
        return $this->collectionMapper ?? new PhotoCollectionMapper;
    }

    private function downloadMapper(): PhotoDownloadMapper
    {
        return $this->downloadMapper ?? new PhotoDownloadMapper;
    }
}
