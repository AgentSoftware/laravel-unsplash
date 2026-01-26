<?php

declare(strict_types=1);

namespace AgentSoftware\Unsplash\Http;

use AgentSoftware\Unsplash\Cache\CacheKeyBuilder;
use AgentSoftware\Unsplash\Config\UnsplashConfig;
use AgentSoftware\Unsplash\Contracts\UnsplashClient as UnsplashClientContract;
use AgentSoftware\Unsplash\Contracts\UnsplashRequest;
use AgentSoftware\Unsplash\Contracts\UnsplashResponse;
use AgentSoftware\Unsplash\Exceptions\AuthenticationException;
use AgentSoftware\Unsplash\Exceptions\NotFoundException;
use AgentSoftware\Unsplash\Exceptions\RateLimitExceededException;
use AgentSoftware\Unsplash\Exceptions\UnsplashApiException;
use Illuminate\Http\Client\Factory as HttpFactory;
use Illuminate\Http\Client\PendingRequest;
use Psr\SimpleCache\CacheInterface;

final class UnsplashClient implements UnsplashClientContract
{
    /**
     * @var array<string, string>
     */
    private array $headers;

    private PendingRequest $http;

    public function __construct(
        private readonly UnsplashConfig $config,
        private readonly ?CacheInterface $cache = null,
        private readonly ?CacheKeyBuilder $keyBuilder = null,
        ?HttpFactory $http = null
    ) {
        $this->headers = $this->normalizeHeaders($this->config->headers);

        $factory = $http ?? new HttpFactory;
        $this->http = $factory->createPendingRequest()
            ->baseUrl($this->config->baseUri)
            ->withHeaders($this->headers)
            ->withOptions($this->config->httpOptions);
    }

    /**
     * @param  array<string, string>  $headers
     */
    public function withHeaders(array $headers): self
    {
        $this->headers = array_merge($this->headers, $this->normalizeHeaders($headers));
        $this->http = $this->http->withHeaders($this->headers);

        return $this;
    }

    public function send(UnsplashRequest $request): UnsplashResponse
    {
        if ($this->config->cacheEnabled && $this->cache !== null) {
            $cached = $this->getCachedResponse($request);
            if ($cached !== null) {
                return $cached;
            }
        }

        $response = $this->http->send($request->method(), $request->endpoint(), [
            'query' => $request->query(),
        ]);

        $data = $response->json();
        $data = is_array($data) ? $data : [];
        $status = $response->status();
        $headers = $response->headers();

        $this->handleErrorResponse($status, $data, $headers);

        $unsplashResponse = new HttpResponse($data, $status, $headers);

        if ($this->config->cacheEnabled && $this->cache !== null) {
            $this->storeCachedResponse($request, $unsplashResponse);
        }

        return $unsplashResponse;
    }

    /**
     * @param  array<string, mixed>  $data
     * @param  array<string, array<int, string>>  $headers
     *
     * @throws AuthenticationException
     * @throws NotFoundException
     * @throws RateLimitExceededException
     * @throws UnsplashApiException
     */
    private function handleErrorResponse(int $status, array $data, array $headers): void
    {
        if ($status >= 200 && $status < 300) {
            return;
        }

        match ($status) {
            401 => throw new AuthenticationException,
            404 => throw new NotFoundException,
            429 => throw RateLimitExceededException::fromHeaders($headers),
            default => throw UnsplashApiException::fromResponse($status, $data),
        };
    }

    private function getCachedResponse(UnsplashRequest $request): ?HttpResponse
    {
        $cacheKey = $this->buildCacheKey($request);
        $cached = $this->cache?->get($cacheKey);

        if (! is_array($cached) || ! isset($cached['data'], $cached['status'], $cached['headers'])) {
            return null;
        }

        return new HttpResponse($cached['data'], (int) $cached['status'], $cached['headers']);
    }

    private function storeCachedResponse(UnsplashRequest $request, HttpResponse $response): void
    {
        $cacheKey = $this->buildCacheKey($request);

        $this->cache?->set($cacheKey, [
            'data' => $response->data(),
            'status' => $response->status(),
            'headers' => $response->headers(),
        ], $this->config->cacheTtlSeconds);
    }

    private function buildCacheKey(UnsplashRequest $request): string
    {
        $builder = $this->keyBuilder ?? new CacheKeyBuilder;

        return $builder->build($request, $this->config->cachePrefix);
    }

    /**
     * @param  array<string, string>  $headers
     * @return array<string, string>
     */
    private function normalizeHeaders(array $headers): array
    {
        return array_filter($headers, function ($value, $name) {
            return $name !== '' && $value !== '';
        }, ARRAY_FILTER_USE_BOTH);
    }
}
