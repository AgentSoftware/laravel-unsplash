<?php

declare(strict_types=1);

use AgentSoftware\Unsplash\Cache\CacheKeyBuilder;
use AgentSoftware\Unsplash\Contracts\UnsplashRequest;

final class TestRequest implements UnsplashRequest
{
    public function method(): string
    {
        return 'GET';
    }

    public function endpoint(): string
    {
        return '/photos';
    }

    public function query(): array
    {
        return [
            'b' => 2,
            'a' => 1,
            'filters' => [
                'size' => [
                    'max' => 100,
                    'min' => 10,
                ],
                'tags' => ['summer', 'beach'],
            ],
        ];
    }
}

it('creates stable cache keys regardless of parameter order', function (): void {
    $builder = new CacheKeyBuilder;
    $request = new TestRequest;

    $first = $builder->build($request, 'test:');
    $second = $builder->build($request, 'test:');

    expect($first)->toBe($second);
    expect($first)->toStartWith('test:');
});
