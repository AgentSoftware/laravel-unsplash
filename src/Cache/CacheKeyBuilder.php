<?php

declare(strict_types=1);

namespace AgentSoftware\Unsplash\Cache;

use AgentSoftware\Unsplash\Contracts\UnsplashRequest;
use JsonException;

final class CacheKeyBuilder
{
    public function build(UnsplashRequest $request, string $prefix = 'unsplash:'): string
    {
        $payload = [
            'method' => $request->method(),
            'endpoint' => $request->endpoint(),
            'query' => $this->normalize($request->query()),
        ];

        try {
            $encoded = json_encode($payload, JSON_THROW_ON_ERROR);
        } catch (JsonException) {
            // Fallback for invalid UTF-8 data so cache keys are still stable.
            $encoded = serialize($payload);
        }

        return $prefix.sha1($encoded);
    }

    /**
     * @param  array<mixed>  $value
     * @return array<mixed>
     */
    private function normalize(array $value): array
    {
        if ($this->isAssociative($value)) {
            ksort($value);
        }

        foreach ($value as $key => $item) {
            if (is_array($item)) {
                $value[$key] = $this->normalize($item);
            }
        }

        return $value;
    }

    /**
     * @param  array<mixed>  $value
     */
    private function isAssociative(array $value): bool
    {
        $keys = array_keys($value);

        return $keys !== array_keys($keys);
    }
}
