<?php

declare(strict_types=1);

use AgentSoftware\Unsplash\Support\HeaderParser;

it('extracts string header value from array format', function (): void {
    $headers = [
        'Content-Type' => ['application/json'],
        'X-Custom' => ['value'],
    ];

    expect(HeaderParser::getString($headers, 'Content-Type'))->toBe('application/json');
    expect(HeaderParser::getString($headers, 'X-Custom'))->toBe('value');
});

it('extracts string header value from string format', function (): void {
    $headers = [
        'Content-Type' => 'application/json',
    ];

    expect(HeaderParser::getString($headers, 'Content-Type'))->toBe('application/json');
});

it('performs case-insensitive header lookup', function (): void {
    $headers = [
        'X-Ratelimit-Limit' => ['50'],
    ];

    expect(HeaderParser::getString($headers, 'x-ratelimit-limit'))->toBe('50');
    expect(HeaderParser::getString($headers, 'X-RATELIMIT-LIMIT'))->toBe('50');
    expect(HeaderParser::getString($headers, 'X-Ratelimit-Limit'))->toBe('50');
});

it('returns null for missing headers', function (): void {
    $headers = ['Content-Type' => ['application/json']];

    expect(HeaderParser::getString($headers, 'X-Missing'))->toBeNull();
});

it('extracts integer header values', function (): void {
    $headers = [
        'X-Ratelimit-Limit' => ['50'],
        'X-Ratelimit-Remaining' => ['45'],
    ];

    expect(HeaderParser::getInt($headers, 'X-Ratelimit-Limit'))->toBe(50);
    expect(HeaderParser::getInt($headers, 'X-Ratelimit-Remaining'))->toBe(45);
});

it('returns null for missing integer headers', function (): void {
    $headers = [];

    expect(HeaderParser::getInt($headers, 'X-Missing'))->toBeNull();
});
