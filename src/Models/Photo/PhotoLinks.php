<?php

declare(strict_types=1);

namespace AgentSoftware\Unsplash\Models\Photo;

use AgentSoftware\Unsplash\Models\Concerns\HasAttributionUrl;
use Illuminate\Contracts\Support\Arrayable;

/**
 * @implements Arrayable<string, mixed>
 */
final class PhotoLinks implements Arrayable
{
    use HasAttributionUrl;

    public function __construct(
        public readonly ?string $self,
        public readonly ?string $html,
        public readonly ?string $download,
        public readonly ?string $downloadLocation
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            isset($data['self']) ? (string) $data['self'] : null,
            isset($data['html']) ? (string) $data['html'] : null,
            isset($data['download']) ? (string) $data['download'] : null,
            isset($data['download_location']) ? (string) $data['download_location'] : null
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return array_filter([
            'self' => $this->self,
            'html' => $this->html,
            'download' => $this->download,
            'download_location' => $this->downloadLocation,
        ], static function ($value): bool {
            return $value !== null;
        });
    }
}
