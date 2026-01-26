<?php

declare(strict_types=1);

namespace AgentSoftware\Unsplash\Models\Photo;

use Illuminate\Contracts\Support\Arrayable;

/**
 * @implements Arrayable<string, mixed>
 */
final class PhotoStatistics implements Arrayable
{
    public function __construct(
        public readonly string $id,
        public readonly int $totalDownloads,
        public readonly int $totalViews,
        public readonly int $downloadsChange,
        public readonly int $viewsChange
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        $downloads = isset($data['downloads']) && is_array($data['downloads']) ? $data['downloads'] : [];
        $views = isset($data['views']) && is_array($data['views']) ? $data['views'] : [];

        $downloadsHistorical = isset($downloads['historical']) && is_array($downloads['historical']) ? $downloads['historical'] : [];
        $viewsHistorical = isset($views['historical']) && is_array($views['historical']) ? $views['historical'] : [];

        return new self(
            (string) ($data['id'] ?? ''),
            (int) ($downloads['total'] ?? 0),
            (int) ($views['total'] ?? 0),
            (int) ($downloadsHistorical['change'] ?? 0),
            (int) ($viewsHistorical['change'] ?? 0)
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'total_downloads' => $this->totalDownloads,
            'total_views' => $this->totalViews,
            'downloads_change' => $this->downloadsChange,
            'views_change' => $this->viewsChange,
        ];
    }
}
