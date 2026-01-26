<?php

declare(strict_types=1);

use AgentSoftware\Unsplash\Mappers\Photo\PhotoCollectionMapper;

it('maps list response into a photo collection', function (): void {
    $payload = json_decode(
        (string) file_get_contents(__DIR__.'/../Fixtures/list-photos-response.json'),
        true
    );

    $mapper = new PhotoCollectionMapper;
    $collection = $mapper->map($payload);

    expect($collection->items)->not->toBeEmpty();
    expect($collection->items[0]->id)->toBe('SLgCDEqHav4');
    expect($collection->items[0]->urls?->raw)->toContain('images.unsplash.com');
});
