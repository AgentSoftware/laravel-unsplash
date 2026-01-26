<?php

declare(strict_types=1);

namespace AgentSoftware\Unsplash\Requests\Photos;

use AgentSoftware\Unsplash\Enums\Color;
use AgentSoftware\Unsplash\Enums\ContentFilter;
use AgentSoftware\Unsplash\Enums\OrderBy;
use AgentSoftware\Unsplash\Enums\Orientation;
use AgentSoftware\Unsplash\Exceptions\InvalidRequestParameter;
use AgentSoftware\Unsplash\Requests\Request;
use AgentSoftware\Unsplash\Support\ArrayHelper;

final class SearchPhotosRequest extends Request
{
    public function __construct(
        public readonly string $query,
        public readonly ?int $page = null,
        public readonly ?int $perPage = null,
        public readonly ?OrderBy $orderBy = null,
        public readonly ?Orientation $orientation = null,
        public readonly ?ContentFilter $contentFilter = null,
        public readonly ?Color $color = null
    ) {
        if (trim($this->query) === '') {
            throw new InvalidRequestParameter('Search query cannot be empty.');
        }

        if ($this->perPage !== null && ($this->perPage < 1 || $this->perPage > 30)) {
            throw new InvalidRequestParameter('perPage must be between 1 and 30.');
        }
    }

    public function endpoint(): string
    {
        return '/search/photos';
    }

    public function query(): array
    {
        return ArrayHelper::filterNulls([
            'query' => $this->query,
            'page' => $this->page,
            'per_page' => $this->perPage,
            'order_by' => $this->orderBy?->value,
            'orientation' => $this->orientation?->value,
            'content_filter' => $this->contentFilter?->value,
            'color' => $this->color?->value,
        ]);
    }
}
