# Laravel Unsplash

Laravel Unsplash wrapper focused on the Photos API.

## Install
```bash
composer require agentsoftware/laravel-unsplash
```

Publish config:
```bash
php artisan vendor:publish --tag=unsplash-config
```

Set your key:
```env
UNSPLASH_ACCESS_KEY=your_access_key
```

## Usage
```php
use AgentSoftware\Unsplash\Enums\OrderBy;
use AgentSoftware\Unsplash\Requests\Photos\ListPhotosRequest;
use AgentSoftware\Unsplash\Unsplash;

$unsplash = app(Unsplash::class);
$photos = $unsplash->photos()->list(new ListPhotosRequest(
    page: 1,
    perPage: 10,
    orderBy: OrderBy::Latest
));

// PhotoCollection is iterable and countable
foreach ($photos as $photo) {
    echo $photo->id;
}

echo count($photos);
```

Facade usage:
```php
use AgentSoftware\Unsplash\Enums\OrderBy;
use AgentSoftware\Unsplash\Facades\Unsplash;
use AgentSoftware\Unsplash\Requests\Photos\ListPhotosRequest;

$photos = Unsplash::photos()->list(new ListPhotosRequest(
    page: 1,
    perPage: 10,
    orderBy: OrderBy::Latest
));
```

### Search
```php
use AgentSoftware\Unsplash\Enums\Color;
use AgentSoftware\Unsplash\Enums\Orientation;
use AgentSoftware\Unsplash\Requests\Photos\SearchPhotosRequest;

$results = $unsplash->photos()->search(new SearchPhotosRequest(
    query: 'nature',
    orientation: Orientation::Landscape,
    color: Color::Blue
));
```

### Get a photo
```php
use AgentSoftware\Unsplash\Requests\Photos\GetPhotoRequest;

$photoResource = $unsplash->photos()->get(new GetPhotoRequest('SLgCDEqHav4'));
$photo = $photoResource->photo();
```

### Random photos
```php
use AgentSoftware\Unsplash\Requests\Photos\RandomPhotoRequest;

$photos = $unsplash->photos()->random(new RandomPhotoRequest(
    query: 'ocean',
    count: 3
));

// Always returns PhotoCollection
foreach ($photos as $photo) {
    echo $photo->urls->regular;
}
```

### Photo statistics
```php
use AgentSoftware\Unsplash\Requests\Photos\GetPhotoStatisticsRequest;

$stats = $unsplash->photos()->statistics(new GetPhotoStatisticsRequest(
    photoId: 'SLgCDEqHav4',
    quantity: 30
));

echo $stats->totalDownloads;
echo $stats->totalViews;
echo $stats->downloadsChange; // Downloads in period
echo $stats->viewsChange;     // Views in period
```

### Download tracking
Unsplash requires triggering the download endpoint when a photo is downloaded. Use the queued event flow:
```php
$photoResource->requestDownload();
```

Or call it synchronously:
```php
$photoResource->trackDownload();
```

### Imgix parameters
```php
use AgentSoftware\Unsplash\Enums\ImgixAuto;
use AgentSoftware\Unsplash\Enums\ImgixFormat;
use AgentSoftware\Unsplash\Images\ImgixParams;

$params = new ImgixParams(
    width: 1200,
    quality: 80,
    format: ImgixFormat::Jpg,
    auto: [ImgixAuto::Compress, ImgixAuto::Format]
);

// Use with photo URLs
$photo = $unsplash->photos()->get(new GetPhotoRequest('photo-id'))->photo();
$customUrl = $photo->urls->customUrl($params);
// https://images.unsplash.com/photo-xxx?w=1200&q=80&fm=jpg&auto=compress,format
```

## Error Handling

The package throws specific exceptions for API errors:

```php
use AgentSoftware\Unsplash\Exceptions\AuthenticationException;
use AgentSoftware\Unsplash\Exceptions\NotFoundException;
use AgentSoftware\Unsplash\Exceptions\RateLimitExceededException;
use AgentSoftware\Unsplash\Exceptions\UnsplashApiException;

try {
    $photo = $unsplash->photos()->get(new GetPhotoRequest('invalid-id'));
} catch (NotFoundException $e) {
    // Photo not found (404)
} catch (AuthenticationException $e) {
    // Invalid API key (401)
} catch (RateLimitExceededException $e) {
    // Rate limit exceeded (429)
    echo $e->limit;     // Requests per hour
    echo $e->remaining; // Remaining requests
} catch (UnsplashApiException $e) {
    // Other API errors
    echo $e->statusCode;
    echo $e->errors;
}
```

## Rate Limits

Rate limit information is available on every response:

```php
$response = $unsplash->send($request);

echo $response->rateLimitLimit();     // Requests per hour (e.g., 50)
echo $response->rateLimitRemaining(); // Remaining requests
```

## Cache

Caching is disabled by default. Enable it in `config/unsplash.php`:
```php
'cache' => [
    'enabled' => true,
    'ttl_seconds' => 86400, // 24 hours
    'store' => \App\Cache\YourCache::class, // Optional
],
```

If no `cache.store` is set, the package resolves `Psr\SimpleCache\CacheInterface` from the container.

## References
- https://unsplash.com/documentation
- https://github.com/unsplash/unsplash-php
