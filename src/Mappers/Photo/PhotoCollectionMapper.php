<?php

declare(strict_types=1);

namespace AgentSoftware\Unsplash\Mappers\Photo;

use AgentSoftware\Unsplash\Models\Photo\Photo;
use AgentSoftware\Unsplash\Models\Photo\PhotoCollection;

final class PhotoCollectionMapper
{
    /**
     * @param  array<int, mixed>|array<string, mixed>  $payload
     */
    public function map(array $payload, ?int $total = null, ?int $totalPages = null): PhotoCollection
    {
        if ($this->isSearchResponse($payload)) {
            $items = [];
            foreach ($payload['results'] as $item) {
                if (is_array($item)) {
                    $items[] = Photo::fromArray($item);
                }
            }

            return new PhotoCollection(
                $items,
                isset($payload['total']) ? (int) $payload['total'] : null,
                isset($payload['total_pages']) ? (int) $payload['total_pages'] : null
            );
        }

        $items = [];
        foreach ($payload as $item) {
            if (is_array($item)) {
                $items[] = Photo::fromArray($item);
            }
        }

        return new PhotoCollection($items, $total, $totalPages);
    }

    /**
     * @param  array<int, mixed>|array<string, mixed>  $payload
     */
    private function isSearchResponse(array $payload): bool
    {
        return isset($payload['results']) && is_array($payload['results']);
    }
}
