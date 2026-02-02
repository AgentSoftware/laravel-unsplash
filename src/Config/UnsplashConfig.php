<?php

declare(strict_types=1);

namespace AgentSoftware\Unsplash\Config;

use AgentSoftware\Unsplash\Exceptions\InvalidConfigurationException;

final class UnsplashConfig
{
    /**
     * @param  array<string, mixed>  $httpOptions
     * @param  array<string, mixed>  $headers
     */
    public function __construct(
        public readonly string $accessKey,
        public readonly ?string $secretKey,
        public readonly string $baseUri,
        public readonly array $headers,
        public readonly array $httpOptions,
        public readonly bool $cacheEnabled,
        public readonly int $cacheTtlSeconds,
        public readonly string $cachePrefix,
        public readonly ?string $cacheStore,
        public readonly bool $downloadsEnabled,
        public readonly ?string $downloadsQueue,
        public readonly string $utmSource,
        public readonly string $utmMedium
    ) {}

    /**
     * @param  array<string, mixed>  $config
     *
     * @throws InvalidConfigurationException
     */
    public static function fromArray(array $config): self
    {
        $accessKey = isset($config['access_key']) ? trim((string) $config['access_key']) : '';

        if ($accessKey === '') {
            throw InvalidConfigurationException::missingAccessKey();
        }

        $headers = isset($config['headers']) && is_array($config['headers']) ? $config['headers'] : [];
        $http = isset($config['http']) && is_array($config['http']) ? $config['http'] : [];
        $cache = isset($config['cache']) && is_array($config['cache']) ? $config['cache'] : [];
        $downloads = isset($config['downloads']) && is_array($config['downloads']) ? $config['downloads'] : [];
        $attribution = isset($config['attribution']) && is_array($config['attribution']) ? $config['attribution'] : [];

        return new self(
            $accessKey,
            isset($config['secret_key']) ? (string) $config['secret_key'] : null,
            (string) ($config['base_uri'] ?? 'https://api.unsplash.com'),
            $headers,
            $http,
            (bool) ($cache['enabled'] ?? false),
            (int) ($cache['ttl_seconds'] ?? 86400),
            (string) ($cache['prefix'] ?? 'unsplash:'),
            isset($cache['store']) ? (string) $cache['store'] : null,
            (bool) ($downloads['enabled'] ?? true),
            isset($downloads['queue']) ? (string) $downloads['queue'] : null,
            (string) ($attribution['utm_source'] ?? 'your_app_name'),
            (string) ($attribution['utm_medium'] ?? 'referral')
        );
    }
}
