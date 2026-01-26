<?php

declare(strict_types=1);

namespace AgentSoftware\Unsplash;

use AgentSoftware\Unsplash\Config\UnsplashConfig;
use AgentSoftware\Unsplash\Contracts\UnsplashClient;
use AgentSoftware\Unsplash\Events\PhotoDownloadRequested;
use AgentSoftware\Unsplash\Http\UnsplashClient as HttpUnsplashClient;
use AgentSoftware\Unsplash\Listeners\TrackPhotoDownload;
use AgentSoftware\Unsplash\Photos\Photos;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Http\Client\Factory as HttpFactory;
use Illuminate\Support\ServiceProvider;
use InvalidArgumentException;
use Psr\SimpleCache\CacheInterface;

final class UnsplashServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/unsplash.php', 'unsplash');

        $this->app->singleton(UnsplashConfig::class, function (): UnsplashConfig {
            /** @var array<string, mixed> $config */
            $config = config('unsplash', []);

            return UnsplashConfig::fromArray($config);
        });

        $this->app->singleton(UnsplashClient::class, function (): UnsplashClient {
            $config = $this->app->make(UnsplashConfig::class);
            $cache = null;
            $http = $this->app->make(HttpFactory::class);

            if ($config->cacheEnabled) {
                if ($config->cacheStore !== null) {
                    $cacheCandidate = $this->app->make($config->cacheStore);
                    if (! $cacheCandidate instanceof CacheInterface) {
                        throw new InvalidArgumentException('Unsplash cache store must implement Psr\\SimpleCache\\CacheInterface.');
                    }
                    $cache = $cacheCandidate;
                } elseif ($this->app->bound(CacheInterface::class)) {
                    $cache = $this->app->make(CacheInterface::class);
                }
            }

            return new HttpUnsplashClient($config, $cache, null, $http);
        });

        $this->app->singleton(Photos::class, function (): Photos {
            $events = $this->app->bound(Dispatcher::class) ? $this->app->make(Dispatcher::class) : null;

            return new Photos(
                $this->app->make(UnsplashClient::class),
                $events
            );
        });

        $this->app->singleton('unsplash', function (): Unsplash {
            return new Unsplash(
                $this->app->make(UnsplashClient::class),
                $this->app->make(Photos::class)
            );
        });
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/unsplash.php' => config_path('unsplash.php'),
        ], 'unsplash-config');

        if (config('unsplash.downloads.enabled', true) && $this->app->bound(Dispatcher::class)) {
            $this->app->make(Dispatcher::class)->listen(PhotoDownloadRequested::class, TrackPhotoDownload::class);
        }
    }
}
