<?php

declare(strict_types=1);

use AgentSoftware\Unsplash\Models\Photo\PhotoLinks;

it('returns attribution url with utm params', function (): void {
    $links = new PhotoLinks(
        self: 'https://api.unsplash.com/photos/abc123',
        html: 'https://unsplash.com/photos/abc123',
        download: 'https://unsplash.com/photos/abc123/download',
        downloadLocation: 'https://api.unsplash.com/photos/abc123/download'
    );

    $result = $links->attributionUrl('my_app', 'referral');

    expect($result)->toBe('https://unsplash.com/photos/abc123?utm_source=my_app&utm_medium=referral');
});

it('returns attribution url with only utm source', function (): void {
    $links = new PhotoLinks(
        self: null,
        html: 'https://unsplash.com/photos/abc123',
        download: null,
        downloadLocation: null
    );

    $result = $links->attributionUrl('my_app');

    expect($result)->toBe('https://unsplash.com/photos/abc123?utm_source=my_app');
});

it('returns attribution url with only utm medium', function (): void {
    $links = new PhotoLinks(
        self: null,
        html: 'https://unsplash.com/photos/abc123',
        download: null,
        downloadLocation: null
    );

    $result = $links->attributionUrl(null, 'referral');

    expect($result)->toBe('https://unsplash.com/photos/abc123?utm_medium=referral');
});

it('returns plain html url when no utm params provided', function (): void {
    $links = new PhotoLinks(
        self: null,
        html: 'https://unsplash.com/photos/abc123',
        download: null,
        downloadLocation: null
    );

    $result = $links->attributionUrl();

    expect($result)->toBe('https://unsplash.com/photos/abc123');
});

it('returns null when html is null', function (): void {
    $links = new PhotoLinks(
        self: null,
        html: null,
        download: null,
        downloadLocation: null
    );

    $result = $links->attributionUrl('my_app', 'referral');

    expect($result)->toBeNull();
});

it('handles url with existing query string', function (): void {
    $links = new PhotoLinks(
        self: null,
        html: 'https://unsplash.com/photos/abc123?existing=param',
        download: null,
        downloadLocation: null
    );

    $result = $links->attributionUrl('my_app', 'referral');

    expect($result)->toBe('https://unsplash.com/photos/abc123?existing=param&utm_source=my_app&utm_medium=referral');
});
