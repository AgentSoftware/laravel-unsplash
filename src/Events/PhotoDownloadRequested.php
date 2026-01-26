<?php

declare(strict_types=1);

namespace AgentSoftware\Unsplash\Events;

final class PhotoDownloadRequested
{
    public function __construct(public readonly string $downloadLocation) {}
}
