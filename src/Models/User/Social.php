<?php

declare(strict_types=1);

namespace AgentSoftware\Unsplash\Models\User;

use Illuminate\Contracts\Support\Arrayable;

/**
 * @implements Arrayable<string, mixed>
 */
final class Social implements Arrayable
{
    public function __construct(
        public readonly ?string $instagramUsername,
        public readonly ?string $portfolioUrl,
        public readonly ?string $twitterUsername,
        public readonly ?string $paypalEmail
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            isset($data['instagram_username']) ? (string) $data['instagram_username'] : null,
            isset($data['portfolio_url']) ? (string) $data['portfolio_url'] : null,
            isset($data['twitter_username']) ? (string) $data['twitter_username'] : null,
            isset($data['paypal_email']) ? (string) $data['paypal_email'] : null
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return array_filter([
            'instagram_username' => $this->instagramUsername,
            'portfolio_url' => $this->portfolioUrl,
            'twitter_username' => $this->twitterUsername,
            'paypal_email' => $this->paypalEmail,
        ], static function ($value): bool {
            return $value !== null;
        });
    }
}
