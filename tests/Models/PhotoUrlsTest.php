<?php

declare(strict_types=1);

use AgentSoftware\Unsplash\Enums\ImgixFormat;
use AgentSoftware\Unsplash\Images\ImgixParams;
use AgentSoftware\Unsplash\Models\Photo\PhotoUrls;

it('builds custom url with imgix params', function (): void {
    $urls = PhotoUrls::fromArray([
        'raw' => 'https://images.unsplash.com/photo-123',
    ]);

    $params = new ImgixParams(width: 800, quality: 80, format: ImgixFormat::Jpg);

    $result = $urls->customUrl($params);

    expect($result)->toBe('https://images.unsplash.com/photo-123?w=800&q=80&fm=jpg');
});

it('returns raw url when params are empty', function (): void {
    $urls = PhotoUrls::fromArray([
        'raw' => 'https://images.unsplash.com/photo-123',
    ]);

    $params = new ImgixParams;

    expect($urls->customUrl($params))->toBe('https://images.unsplash.com/photo-123');
});

it('returns null when raw url is missing', function (): void {
    $urls = PhotoUrls::fromArray([]);

    $params = new ImgixParams(width: 800);

    expect($urls->customUrl($params))->toBeNull();
});

it('appends params with ampersand when url has query string', function (): void {
    $urls = PhotoUrls::fromArray([
        'raw' => 'https://images.unsplash.com/photo-123?ixid=abc',
    ]);

    $params = new ImgixParams(width: 800);

    $result = $urls->customUrl($params);

    expect($result)->toBe('https://images.unsplash.com/photo-123?ixid=abc&w=800');
});
