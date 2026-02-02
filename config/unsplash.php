<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | Access Credentials
    |--------------------------------------------------------------------------
    |
    | Unsplash access key used to authenticate requests.
    |
    */
    'access_key' => env('UNSPLASH_ACCESS_KEY'),
    'secret_key' => env('UNSPLASH_SECRET_KEY'),

    /*
    |--------------------------------------------------------------------------
    | API Base URI
    |--------------------------------------------------------------------------
    |
    | Base endpoint for the Unsplash API.
    |
    */
    'base_uri' => env('UNSPLASH_BASE_URI', 'https://api.unsplash.com'),

    /*
    |--------------------------------------------------------------------------
    | Request Headers
    |--------------------------------------------------------------------------
    |
    | Default headers sent with every request.
    |
    */
    'headers' => [
        'Accept-Version' => 'v1',
        'User-Agent' => 'AgentSoftware/laravel-unsplash',
    ],

    /*
    |--------------------------------------------------------------------------
    | HTTP Client Options
    |--------------------------------------------------------------------------
    |
    | Options forwarded to the HTTP client.
    |
    */
    'http' => [
        'timeout' => 10,
        'connect_timeout' => 5,
    ],

    /*
    |--------------------------------------------------------------------------
    | Cache
    |--------------------------------------------------------------------------
    |
    | PSR-16 cache settings for response caching.
    |
    */
    'cache' => [
        'enabled' => false,
        'ttl_seconds' => 86400,
        'prefix' => 'unsplash:',
        'store' => null,
    ],

    /*
    |--------------------------------------------------------------------------
    | Download Tracking
    |--------------------------------------------------------------------------
    |
    | Queue settings for download tracking events.
    |
    */
    'downloads' => [
        'queue' => env('UNSPLASH_DOWNLOAD_QUEUE', null),
        'enabled' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Attribution
    |--------------------------------------------------------------------------
    |
    | UTM parameters for Unsplash attribution links. Per Unsplash guidelines,
    | you must provide attribution when using their photos. These parameters
    | are appended to photographer and Unsplash links.
    |
    | @see https://help.unsplash.com/en/articles/2511315-guideline-attribution
    |
    */
    'attribution' => [
        'utm_source' => env('UNSPLASH_UTM_SOURCE', 'your_app_name'),
        'utm_medium' => env('UNSPLASH_UTM_MEDIUM', 'referral'),
    ],
];
