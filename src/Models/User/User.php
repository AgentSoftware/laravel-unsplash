<?php

declare(strict_types=1);

namespace AgentSoftware\Unsplash\Models\User;

use AgentSoftware\Unsplash\Support\DateParser;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Carbon;

/**
 * @implements Arrayable<string, mixed>
 */
final class User implements Arrayable
{
    /**
     * @param  array<string, mixed>  $raw
     */
    public function __construct(
        public readonly string $id,
        public readonly ?Carbon $updatedAt,
        public readonly ?string $username,
        public readonly ?string $name,
        public readonly ?string $firstName,
        public readonly ?string $lastName,
        public readonly ?string $twitterUsername,
        public readonly ?string $portfolioUrl,
        public readonly ?string $bio,
        public readonly ?string $location,
        public readonly ?UserLinks $links,
        public readonly ?ProfileImage $profileImage,
        public readonly ?string $instagramUsername,
        public readonly ?int $totalCollections,
        public readonly ?int $totalLikes,
        public readonly ?int $totalPhotos,
        public readonly ?int $totalFreePhotos,
        public readonly ?int $totalPromotedPhotos,
        public readonly ?int $totalIllustrations,
        public readonly ?int $totalFreeIllustrations,
        public readonly ?int $totalPromotedIllustrations,
        public readonly ?bool $acceptedTos,
        public readonly ?bool $forHire,
        public readonly ?Social $social,
        public readonly array $raw = []
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            (string) ($data['id'] ?? ''),
            DateParser::parse(isset($data['updated_at']) ? (string) $data['updated_at'] : null),
            isset($data['username']) ? (string) $data['username'] : null,
            isset($data['name']) ? (string) $data['name'] : null,
            isset($data['first_name']) ? (string) $data['first_name'] : null,
            isset($data['last_name']) ? (string) $data['last_name'] : null,
            isset($data['twitter_username']) ? (string) $data['twitter_username'] : null,
            isset($data['portfolio_url']) ? (string) $data['portfolio_url'] : null,
            isset($data['bio']) ? (string) $data['bio'] : null,
            isset($data['location']) ? (string) $data['location'] : null,
            isset($data['links']) && is_array($data['links']) ? UserLinks::fromArray($data['links']) : null,
            isset($data['profile_image']) && is_array($data['profile_image']) ? ProfileImage::fromArray($data['profile_image']) : null,
            isset($data['instagram_username']) ? (string) $data['instagram_username'] : null,
            isset($data['total_collections']) ? (int) $data['total_collections'] : null,
            isset($data['total_likes']) ? (int) $data['total_likes'] : null,
            isset($data['total_photos']) ? (int) $data['total_photos'] : null,
            isset($data['total_free_photos']) ? (int) $data['total_free_photos'] : null,
            isset($data['total_promoted_photos']) ? (int) $data['total_promoted_photos'] : null,
            isset($data['total_illustrations']) ? (int) $data['total_illustrations'] : null,
            isset($data['total_free_illustrations']) ? (int) $data['total_free_illustrations'] : null,
            isset($data['total_promoted_illustrations']) ? (int) $data['total_promoted_illustrations'] : null,
            isset($data['accepted_tos']) ? (bool) $data['accepted_tos'] : null,
            isset($data['for_hire']) ? (bool) $data['for_hire'] : null,
            isset($data['social']) && is_array($data['social']) ? Social::fromArray($data['social']) : null,
            $data
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return array_filter([
            'id' => $this->id,
            'username' => $this->username,
            'name' => $this->name,
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'updated_at' => $this->updatedAt?->toIso8601String(),
            'twitter_username' => $this->twitterUsername,
            'instagram_username' => $this->instagramUsername,
            'portfolio_url' => $this->portfolioUrl,
            'bio' => $this->bio,
            'location' => $this->location,
            'links' => $this->links?->toArray(),
            'profile_image' => $this->profileImage?->toArray(),
            'accepted_tos' => $this->acceptedTos,
            'for_hire' => $this->forHire,
            'social' => $this->social?->toArray(),
        ], static function ($value): bool {
            return $value !== null;
        });
    }
}
