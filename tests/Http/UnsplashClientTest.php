<?php

declare(strict_types=1);

use AgentSoftware\Unsplash\Cache\CacheKeyBuilder;
use AgentSoftware\Unsplash\Config\UnsplashConfig;
use AgentSoftware\Unsplash\Contracts\UnsplashRequest;
use AgentSoftware\Unsplash\Http\UnsplashClient;
use Illuminate\Http\Client\Factory as HttpFactory;
use Psr\SimpleCache\CacheInterface;

final class HttpTestRequest implements UnsplashRequest
{
    public function method(): string
    {
        return 'GET';
    }

    public function endpoint(): string
    {
        return '/photos';
    }

    public function query(): array
    {
        return ['page' => 1];
    }
}

final class ArrayCache implements CacheInterface
{
    /**
     * @var array<string, mixed>
     */
    private array $items = [];

    public function get($key, $default = null): mixed
    {
        return $this->items[$key] ?? $default;
    }

    public function set($key, $value, $ttl = null): bool
    {
        $this->items[$key] = $value;

        return true;
    }

    public function delete($key): bool
    {
        unset($this->items[$key]);

        return true;
    }

    public function clear(): bool
    {
        $this->items = [];

        return true;
    }

    public function getMultiple($keys, $default = null): iterable
    {
        $values = [];

        foreach ($keys as $key) {
            $values[$key] = $this->items[$key] ?? $default;
        }

        return $values;
    }

    /**
     * @param  iterable<string, mixed>  $values
     */
    public function setMultiple($values, $ttl = null): bool
    {
        foreach ($values as $key => $value) {
            $this->items[$key] = $value;
        }

        return true;
    }

    public function deleteMultiple($keys): bool
    {
        foreach ($keys as $key) {
            unset($this->items[$key]);
        }

        return true;
    }

    public function has($key): bool
    {
        return array_key_exists($key, $this->items);
    }
}

it('sends requests with the laravel http client', function (): void {
    $factory = new HttpFactory;
    $factory->fake([
        'https://example.test/photos*' => HttpFactory::response(['ok' => true], 200, ['X-Test' => '1']),
    ]);

    $config = UnsplashConfig::fromArray([
        'access_key' => 'abc123',
        'base_uri' => 'https://example.test',
        'headers' => ['Authorization' => 'Client-ID abc123'],
        'http' => ['timeout' => 5],
    ]);

    $client = new UnsplashClient($config, null, null, $factory);
    $response = $client->send(new HttpTestRequest);

    expect($response->data())->toBe(['ok' => true]);
    expect($response->status())->toBe(200);
    expect($response->headers())->toHaveKey('X-Test');

    $recorded = $factory->recorded();
    expect($recorded)->toHaveCount(1);
});

it('returns cached responses without making http calls', function (): void {
    $factory = new HttpFactory;
    $factory->preventStrayRequests();

    $cache = new ArrayCache;
    $keyBuilder = new CacheKeyBuilder;

    $config = UnsplashConfig::fromArray([
        'access_key' => 'abc123',
        'base_uri' => 'https://example.test',
        'headers' => ['Authorization' => 'Client-ID abc123'],
        'cache' => [
            'enabled' => true,
            'ttl_seconds' => 300,
            'prefix' => 'unsplash:',
        ],
    ]);

    $request = new HttpTestRequest;
    $cacheKey = $keyBuilder->build($request, 'unsplash:');
    $cache->set($cacheKey, [
        'data' => ['cached' => true],
        'status' => 200,
        'headers' => ['X-Cache' => ['hit']],
    ]);

    $client = new UnsplashClient($config, $cache, $keyBuilder, $factory);
    $response = $client->send($request);

    expect($response->data())->toBe(['cached' => true]);
    expect($response->headers())->toHaveKey('X-Cache');
});

it('stores responses in cache when enabled', function (): void {
    $factory = new HttpFactory;
    $factory->fake([
        'https://example.test/photos*' => HttpFactory::response(['stored' => true], 200),
    ]);

    $cache = new ArrayCache;
    $keyBuilder = new CacheKeyBuilder;

    $config = UnsplashConfig::fromArray([
        'access_key' => 'abc123',
        'base_uri' => 'https://example.test',
        'headers' => ['Authorization' => 'Client-ID abc123'],
        'cache' => [
            'enabled' => true,
            'ttl_seconds' => 300,
            'prefix' => 'unsplash:',
        ],
    ]);

    $client = new UnsplashClient($config, $cache, $keyBuilder, $factory);
    $request = new HttpTestRequest;
    $client->send($request);

    $cacheKey = $keyBuilder->build($request, 'unsplash:');
    $cached = $cache->get($cacheKey);

    expect($cached)->toBeArray();
    expect($cached['data'])->toBe(['stored' => true]);
    expect($cached['status'])->toBe(200);
});
