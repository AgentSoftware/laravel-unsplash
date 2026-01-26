<?php

declare(strict_types=1);

namespace AgentSoftware\Unsplash\Models\User;

use Illuminate\Contracts\Support\Arrayable;

/**
 * @implements Arrayable<string, mixed>
 */
final class ProfileImage implements Arrayable
{
    public function __construct(
        public readonly ?string $small,
        public readonly ?string $medium,
        public readonly ?string $large
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            isset($data['small']) ? (string) $data['small'] : null,
            isset($data['medium']) ? (string) $data['medium'] : null,
            isset($data['large']) ? (string) $data['large'] : null
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return array_filter([
            'small' => $this->small,
            'medium' => $this->medium,
            'large' => $this->large,
        ], static function ($value): bool {
            return $value !== null;
        });
    }
}
