<?php

declare(strict_types=1);

namespace AgentSoftware\Unsplash\Models\Photo;

use Illuminate\Contracts\Support\Arrayable;

/**
 * @implements Arrayable<string, mixed>
 */
final class PhotoDownload implements Arrayable
{
    /**
     * @param  array<string, mixed>  $raw
     */
    public function __construct(
        public readonly ?string $url,
        public readonly array $raw = []
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            isset($data['url']) ? (string) $data['url'] : null,
            $data
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return array_filter([
            'url' => $this->url,
        ], static function ($value): bool {
            return $value !== null;
        });
    }
}
