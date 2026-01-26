<?php

declare(strict_types=1);

namespace AgentSoftware\Unsplash\Mappers\Photo;

use AgentSoftware\Unsplash\Models\Photo\PhotoDownload;

final class PhotoDownloadMapper
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function map(array $data): PhotoDownload
    {
        return PhotoDownload::fromArray($data);
    }
}
