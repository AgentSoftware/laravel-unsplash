<?php

declare(strict_types=1);

namespace AgentSoftware\Unsplash\Models\User;

use Illuminate\Contracts\Support\Arrayable;

/**
 * @implements Arrayable<string, mixed>
 */
final class UserLinks implements Arrayable
{
    public function __construct(
        public readonly ?string $self,
        public readonly ?string $html,
        public readonly ?string $photos,
        public readonly ?string $likes,
        public readonly ?string $portfolio
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            isset($data['self']) ? (string) $data['self'] : null,
            isset($data['html']) ? (string) $data['html'] : null,
            isset($data['photos']) ? (string) $data['photos'] : null,
            isset($data['likes']) ? (string) $data['likes'] : null,
            isset($data['portfolio']) ? (string) $data['portfolio'] : null
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
            'photos' => $this->photos,
            'likes' => $this->likes,
            'portfolio' => $this->portfolio,
        ], static function ($value): bool {
            return $value !== null;
        });
    }
}
