<?php

declare(strict_types=1);

use AgentSoftware\Unsplash\Config\UnsplashConfig;
use AgentSoftware\Unsplash\Contracts\UnsplashRequest;
use AgentSoftware\Unsplash\Exceptions\AuthenticationException;
use AgentSoftware\Unsplash\Exceptions\NotFoundException;
use AgentSoftware\Unsplash\Exceptions\RateLimitExceededException;
use AgentSoftware\Unsplash\Exceptions\UnsplashApiException;
use AgentSoftware\Unsplash\Http\UnsplashClient;
use Illuminate\Http\Client\Factory as HttpFactory;

final class ErrorTestRequest implements UnsplashRequest
{
    public function method(): string
    {
        return 'GET';
    }

    public function endpoint(): string
    {
        return '/photos/test';
    }

    public function query(): array
    {
        return [];
    }
}

function createTestClient(HttpFactory $factory): UnsplashClient
{
    $config = UnsplashConfig::fromArray([
        'access_key' => 'test-key',
        'base_uri' => 'https://api.unsplash.com',
        'headers' => ['Authorization' => 'Client-ID test-key'],
    ]);

    return new UnsplashClient($config, null, null, $factory);
}

it('throws AuthenticationException on 401 response', function (): void {
    $factory = new HttpFactory;
    $factory->fake([
        'https://api.unsplash.com/*' => HttpFactory::response(
            ['errors' => ['OAuth error: The access token is invalid']],
            401
        ),
    ]);

    $client = createTestClient($factory);

    expect(fn () => $client->send(new ErrorTestRequest))
        ->toThrow(AuthenticationException::class, 'Unauthorized - Invalid or missing access key');
});

it('throws NotFoundException on 404 response', function (): void {
    $factory = new HttpFactory;
    $factory->fake([
        'https://api.unsplash.com/*' => HttpFactory::response(
            ['errors' => ['Couldn\'t find Photo']],
            404
        ),
    ]);

    $client = createTestClient($factory);

    expect(fn () => $client->send(new ErrorTestRequest))
        ->toThrow(NotFoundException::class, 'Resource not found');
});

it('throws RateLimitExceededException on 429 response', function (): void {
    $factory = new HttpFactory;
    $factory->fake([
        'https://api.unsplash.com/*' => HttpFactory::response(
            ['errors' => ['Rate Limit Exceeded']],
            429,
            ['X-Ratelimit-Limit' => '50', 'X-Ratelimit-Remaining' => '0']
        ),
    ]);

    $client = createTestClient($factory);

    try {
        $client->send(new ErrorTestRequest);
        $this->fail('Expected RateLimitExceededException');
    } catch (RateLimitExceededException $e) {
        expect($e->getMessage())->toContain('Rate limit exceeded');
        expect($e->limit)->toBe(50);
        expect($e->remaining)->toBe(0);
    }
});

it('throws UnsplashApiException on 400 response with error message', function (): void {
    $factory = new HttpFactory;
    $factory->fake([
        'https://api.unsplash.com/*' => HttpFactory::response(
            ['errors' => ['Query parameter is missing']],
            400
        ),
    ]);

    $client = createTestClient($factory);

    try {
        $client->send(new ErrorTestRequest);
        $this->fail('Expected UnsplashApiException');
    } catch (UnsplashApiException $e) {
        expect($e->getMessage())->toBe('Query parameter is missing');
        expect($e->statusCode)->toBe(400);
        expect($e->errors)->toBe(['Query parameter is missing']);
    }
});

it('throws UnsplashApiException on 500 response', function (): void {
    $factory = new HttpFactory;
    $factory->fake([
        'https://api.unsplash.com/*' => HttpFactory::response([], 500),
    ]);

    $client = createTestClient($factory);

    try {
        $client->send(new ErrorTestRequest);
        $this->fail('Expected UnsplashApiException');
    } catch (UnsplashApiException $e) {
        expect($e->getMessage())->toBe('Unsplash server error');
        expect($e->statusCode)->toBe(500);
    }
});

it('throws UnsplashApiException on 403 response', function (): void {
    $factory = new HttpFactory;
    $factory->fake([
        'https://api.unsplash.com/*' => HttpFactory::response(
            ['errors' => ['Access denied']],
            403
        ),
    ]);

    $client = createTestClient($factory);

    try {
        $client->send(new ErrorTestRequest);
        $this->fail('Expected UnsplashApiException');
    } catch (UnsplashApiException $e) {
        expect($e->getMessage())->toBe('Access denied');
        expect($e->statusCode)->toBe(403);
    }
});

it('returns response on 2xx status codes', function (): void {
    $factory = new HttpFactory;
    $factory->fake([
        'https://api.unsplash.com/*' => HttpFactory::response(['id' => 'abc123'], 200),
    ]);

    $client = createTestClient($factory);
    $response = $client->send(new ErrorTestRequest);

    expect($response->status())->toBe(200);
    expect($response->data())->toBe(['id' => 'abc123']);
});
