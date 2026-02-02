<?php

declare(strict_types=1);

use AgentSoftware\Unsplash\Models\User\UserLinks;

it('returns attribution url with utm params', function (): void {
    $links = new UserLinks(
        self: 'https://api.unsplash.com/users/johndoe',
        html: 'https://unsplash.com/@johndoe',
        photos: 'https://api.unsplash.com/users/johndoe/photos',
        likes: 'https://api.unsplash.com/users/johndoe/likes',
        portfolio: 'https://johndoe.com'
    );

    $result = $links->attributionUrl('my_app', 'referral');

    expect($result)->toBe('https://unsplash.com/@johndoe?utm_source=my_app&utm_medium=referral');
});

it('returns attribution url with only utm source', function (): void {
    $links = new UserLinks(
        self: null,
        html: 'https://unsplash.com/@johndoe',
        photos: null,
        likes: null,
        portfolio: null
    );

    $result = $links->attributionUrl('my_app');

    expect($result)->toBe('https://unsplash.com/@johndoe?utm_source=my_app');
});

it('returns attribution url with only utm medium', function (): void {
    $links = new UserLinks(
        self: null,
        html: 'https://unsplash.com/@johndoe',
        photos: null,
        likes: null,
        portfolio: null
    );

    $result = $links->attributionUrl(null, 'referral');

    expect($result)->toBe('https://unsplash.com/@johndoe?utm_medium=referral');
});

it('returns plain html url when no utm params provided', function (): void {
    $links = new UserLinks(
        self: null,
        html: 'https://unsplash.com/@johndoe',
        photos: null,
        likes: null,
        portfolio: null
    );

    $result = $links->attributionUrl();

    expect($result)->toBe('https://unsplash.com/@johndoe');
});

it('returns null when html is null', function (): void {
    $links = new UserLinks(
        self: null,
        html: null,
        photos: null,
        likes: null,
        portfolio: null
    );

    $result = $links->attributionUrl('my_app', 'referral');

    expect($result)->toBeNull();
});

it('handles url with existing query string', function (): void {
    $links = new UserLinks(
        self: null,
        html: 'https://unsplash.com/@johndoe?existing=param',
        photos: null,
        likes: null,
        portfolio: null
    );

    $result = $links->attributionUrl('my_app', 'referral');

    expect($result)->toBe('https://unsplash.com/@johndoe?existing=param&utm_source=my_app&utm_medium=referral');
});
