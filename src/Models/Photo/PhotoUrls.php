<?php

declare(strict_types=1);

namespace AgentSoftware\Unsplash\Models\Photo;

use AgentSoftware\Unsplash\Images\ImgixParams;
use Illuminate\Contracts\Support\Arrayable;

/**
 * @implements Arrayable<string, mixed>
 */
final class PhotoUrls implements Arrayable
{
    public function __construct(
        public readonly ?string $raw,
        public readonly ?string $full,
        public readonly ?string $regular,
        public readonly ?string $small,
        public readonly ?string $thumb,
        public readonly ?string $smallS3
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            isset($data['raw']) ? (string) $data['raw'] : null,
            isset($data['full']) ? (string) $data['full'] : null,
            isset($data['regular']) ? (string) $data['regular'] : null,
            isset($data['small']) ? (string) $data['small'] : null,
            isset($data['thumb']) ? (string) $data['thumb'] : null,
            isset($data['small_s3']) ? (string) $data['small_s3'] : null
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return array_filter([
            'raw' => $this->raw,
            'full' => $this->full,
            'regular' => $this->regular,
            'small' => $this->small,
            'thumb' => $this->thumb,
            'small_s3' => $this->smallS3,
        ], static function ($value): bool {
            return $value !== null;
        });
    }

    public function customUrl(ImgixParams $params): ?string
    {
        if ($this->raw === null) {
            return null;
        }

        $query = $params->toQuery();

        if ($query === []) {
            return $this->raw;
        }

        $separator = str_contains($this->raw, '?') ? '&' : '?';

        return $this->raw.$separator.http_build_query($query);
    }
}
