<?php

declare(strict_types=1);

use AgentSoftware\Unsplash\Mappers\Photo\PhotoDownloadMapper;

it('maps photo download payloads', function (): void {
    $mapper = new PhotoDownloadMapper;

    $download = $mapper->map([
        'url' => 'https://images.unsplash.com/download/test',
        'status' => 'ok',
    ]);

    expect($download->url)->toBe('https://images.unsplash.com/download/test');
    expect($download->raw)->toBe([
        'url' => 'https://images.unsplash.com/download/test',
        'status' => 'ok',
    ]);
});

it('handles missing download url', function (): void {
    $mapper = new PhotoDownloadMapper;

    $download = $mapper->map(['status' => 'ok']);

    expect($download->url)->toBeNull();
    expect($download->raw)->toBe(['status' => 'ok']);
});
