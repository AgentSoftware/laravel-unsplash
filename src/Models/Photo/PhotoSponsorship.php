<?php

declare(strict_types=1);

namespace AgentSoftware\Unsplash\Models\Photo;

use AgentSoftware\Unsplash\Models\User\User;

final class PhotoSponsorship
{
    /**
     * @param  array<int, string>  $impressionUrls
     */
    public function __construct(
        public readonly array $impressionUrls,
        public readonly ?string $tagline,
        public readonly ?string $taglineUrl,
        public readonly ?User $sponsor
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        $impressionUrls = [];
        if (isset($data['impression_urls']) && is_array($data['impression_urls'])) {
            $impressionUrls = array_values(array_map('strval', $data['impression_urls']));
        }

        return new self(
            $impressionUrls,
            isset($data['tagline']) ? (string) $data['tagline'] : null,
            isset($data['tagline_url']) ? (string) $data['tagline_url'] : null,
            isset($data['sponsor']) && is_array($data['sponsor']) ? User::fromArray($data['sponsor']) : null
        );
    }
}
