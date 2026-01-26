<?php

declare(strict_types=1);

use AgentSoftware\Unsplash\Config\UnsplashConfig;
use AgentSoftware\Unsplash\Exceptions\InvalidConfigurationException;

it('builds config with defaults when optional sections are missing', function (): void {
    $config = UnsplashConfig::fromArray([
        'access_key' => 'abc123',
        'base_uri' => 'https://example.test',
    ]);

    expect($config->accessKey)->toBe('abc123');
    expect($config->secretKey)->toBeNull();
    expect($config->baseUri)->toBe('https://example.test');
    expect($config->headers)->toBe([]);
    expect($config->httpOptions)->toBe([]);
    expect($config->cacheEnabled)->toBeFalse();
    expect($config->cacheTtlSeconds)->toBe(86400);
    expect($config->cachePrefix)->toBe('unsplash:');
    expect($config->cacheStore)->toBeNull();
    expect($config->downloadsEnabled)->toBeTrue();
    expect($config->downloadsQueue)->toBeNull();
});

it('builds config with provided options', function (): void {
    $config = UnsplashConfig::fromArray([
        'access_key' => 'abc123',
        'secret_key' => 'def456',
        'base_uri' => 'https://example.test',
        'headers' => [
            'Accept' => 'application/json',
        ],
        'http' => [
            'timeout' => 2.5,
        ],
        'cache' => [
            'enabled' => false,
            'ttl_seconds' => 120,
            'prefix' => 'unsplash:test:',
            'store' => \Psr\SimpleCache\CacheInterface::class,
        ],
        'downloads' => [
            'enabled' => false,
            'queue' => 'downloads',
        ],
    ]);

    expect($config->accessKey)->toBe('abc123');
    expect($config->secretKey)->toBe('def456');
    expect($config->baseUri)->toBe('https://example.test');
    expect($config->headers)->toBe(['Accept' => 'application/json']);
    expect($config->httpOptions)->toBe(['timeout' => 2.5]);
    expect($config->cacheEnabled)->toBeFalse();
    expect($config->cacheTtlSeconds)->toBe(120);
    expect($config->cachePrefix)->toBe('unsplash:test:');
    expect($config->cacheStore)->toBe(\Psr\SimpleCache\CacheInterface::class);
    expect($config->downloadsEnabled)->toBeFalse();
    expect($config->downloadsQueue)->toBe('downloads');
});

it('throws exception when access key is missing', function (): void {
    expect(fn () => UnsplashConfig::fromArray([]))
        ->toThrow(InvalidConfigurationException::class, 'Unsplash access key is required');
});

it('throws exception when access key is empty string', function (): void {
    expect(fn () => UnsplashConfig::fromArray(['access_key' => '']))
        ->toThrow(InvalidConfigurationException::class, 'Unsplash access key is required');
});

it('throws exception when access key is whitespace only', function (): void {
    expect(fn () => UnsplashConfig::fromArray(['access_key' => '   ']))
        ->toThrow(InvalidConfigurationException::class, 'Unsplash access key is required');
});
