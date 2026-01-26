<?php

declare(strict_types=1);

namespace AgentSoftware\Unsplash\Listeners;

use AgentSoftware\Unsplash\Config\UnsplashConfig;
use AgentSoftware\Unsplash\Contracts\UnsplashClient;
use AgentSoftware\Unsplash\Events\PhotoDownloadRequested;
use AgentSoftware\Unsplash\Requests\Photos\TrackDownloadRequest;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

final class TrackPhotoDownload implements ShouldQueue
{
    use InteractsWithQueue;

    public ?string $queue = null;

    public function __construct(
        private readonly UnsplashClient $client,
        UnsplashConfig $config
    ) {
        if ($config->downloadsQueue !== null && $config->downloadsQueue !== '') {
            $this->queue = $config->downloadsQueue;
        }
    }

    public function handle(PhotoDownloadRequested $event): void
    {
        $this->client->send(new TrackDownloadRequest($event->downloadLocation));
    }
}
