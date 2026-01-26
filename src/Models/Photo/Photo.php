<?php

declare(strict_types=1);

namespace AgentSoftware\Unsplash\Models\Photo;

use AgentSoftware\Unsplash\Models\User\User;
use AgentSoftware\Unsplash\Support\DateParser;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Carbon;

/**
 * @implements Arrayable<string, mixed>
 */
final class Photo implements Arrayable
{
    /**
     * @param  array<string, string>  $alternativeSlugs
     * @param  array<int, mixed>  $breadcrumbs
     * @param  array<int, mixed>  $currentUserCollections
     * @param  array<string, mixed>  $topicSubmissions
     * @param  array<string, mixed>  $raw
     */
    public function __construct(
        public readonly string $id,
        public readonly ?string $slug,
        public readonly array $alternativeSlugs,
        public readonly ?Carbon $createdAt,
        public readonly ?Carbon $updatedAt,
        public readonly ?Carbon $promotedAt,
        public readonly ?int $width,
        public readonly ?int $height,
        public readonly ?string $color,
        public readonly ?string $blurHash,
        public readonly ?string $description,
        public readonly ?string $altDescription,
        public readonly array $breadcrumbs,
        public readonly ?PhotoUrls $urls,
        public readonly ?PhotoLinks $links,
        public readonly ?int $likes,
        public readonly ?bool $likedByUser,
        public readonly ?bool $bookmarked,
        public readonly array $currentUserCollections,
        public readonly ?PhotoSponsorship $sponsorship,
        public readonly array $topicSubmissions,
        public readonly ?string $assetType,
        public readonly ?User $user,
        public readonly array $raw = []
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        $alternativeSlugs = [];
        if (isset($data['alternative_slugs']) && is_array($data['alternative_slugs'])) {
            foreach ($data['alternative_slugs'] as $key => $value) {
                if (is_string($key) && is_string($value)) {
                    $alternativeSlugs[$key] = $value;
                }
            }
        }

        $breadcrumbs = [];
        if (isset($data['breadcrumbs']) && is_array($data['breadcrumbs'])) {
            $breadcrumbs = $data['breadcrumbs'];
        }

        $currentUserCollections = [];
        if (isset($data['current_user_collections']) && is_array($data['current_user_collections'])) {
            $currentUserCollections = $data['current_user_collections'];
        }

        $topicSubmissions = [];
        if (isset($data['topic_submissions']) && is_array($data['topic_submissions'])) {
            $topicSubmissions = $data['topic_submissions'];
        }

        return new self(
            (string) ($data['id'] ?? ''),
            isset($data['slug']) ? (string) $data['slug'] : null,
            $alternativeSlugs,
            DateParser::parse(isset($data['created_at']) ? (string) $data['created_at'] : null),
            DateParser::parse(isset($data['updated_at']) ? (string) $data['updated_at'] : null),
            DateParser::parse(isset($data['promoted_at']) ? (string) $data['promoted_at'] : null),
            isset($data['width']) ? (int) $data['width'] : null,
            isset($data['height']) ? (int) $data['height'] : null,
            isset($data['color']) ? (string) $data['color'] : null,
            isset($data['blur_hash']) ? (string) $data['blur_hash'] : null,
            isset($data['description']) ? (string) $data['description'] : null,
            isset($data['alt_description']) ? (string) $data['alt_description'] : null,
            $breadcrumbs,
            isset($data['urls']) && is_array($data['urls']) ? PhotoUrls::fromArray($data['urls']) : null,
            isset($data['links']) && is_array($data['links']) ? PhotoLinks::fromArray($data['links']) : null,
            isset($data['likes']) ? (int) $data['likes'] : null,
            isset($data['liked_by_user']) ? (bool) $data['liked_by_user'] : null,
            isset($data['bookmarked']) ? (bool) $data['bookmarked'] : null,
            $currentUserCollections,
            isset($data['sponsorship']) && is_array($data['sponsorship']) ? PhotoSponsorship::fromArray($data['sponsorship']) : null,
            $topicSubmissions,
            isset($data['asset_type']) ? (string) $data['asset_type'] : null,
            isset($data['user']) && is_array($data['user']) ? User::fromArray($data['user']) : null,
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
            'slug' => $this->slug,
            'created_at' => $this->createdAt?->toIso8601String(),
            'updated_at' => $this->updatedAt?->toIso8601String(),
            'promoted_at' => $this->promotedAt?->toIso8601String(),
            'width' => $this->width,
            'height' => $this->height,
            'color' => $this->color,
            'blur_hash' => $this->blurHash,
            'description' => $this->description,
            'alt_description' => $this->altDescription,
            'urls' => $this->urls?->toArray(),
            'links' => $this->links?->toArray(),
            'likes' => $this->likes,
            'liked_by_user' => $this->likedByUser,
            'bookmarked' => $this->bookmarked,
            'user' => $this->user?->toArray(),
            'asset_type' => $this->assetType,
        ], static function ($value): bool {
            return $value !== null;
        });
    }
}
