<?php

declare(strict_types=1);

namespace AgentSoftware\Unsplash\Requests\Photos;

use AgentSoftware\Unsplash\Enums\ContentFilter;
use AgentSoftware\Unsplash\Enums\Orientation;
use AgentSoftware\Unsplash\Exceptions\InvalidRequestParameter;
use AgentSoftware\Unsplash\Requests\Request;
use AgentSoftware\Unsplash\Support\ArrayHelper;

final class RandomPhotoRequest extends Request
{
    /**
     * @param  array<int, string>  $collections
     * @param  array<int, string>  $topics
     */
    public function __construct(
        public readonly ?string $query = null,
        public readonly ?Orientation $orientation = null,
        public readonly ?ContentFilter $contentFilter = null,
        public readonly ?string $username = null,
        public readonly array $collections = [],
        public readonly array $topics = [],
        public readonly ?int $count = null
    ) {
        if ($this->count !== null && ($this->count < 1 || $this->count > 30)) {
            throw new InvalidRequestParameter('count must be between 1 and 30.');
        }
    }

    public function endpoint(): string
    {
        return '/photos/random';
    }

    public function query(): array
    {
        $collections = $this->collections === [] ? null : implode(',', $this->collections);
        $topics = $this->topics === [] ? null : implode(',', $this->topics);

        return ArrayHelper::filterNulls([
            'query' => $this->query,
            'orientation' => $this->orientation?->value,
            'content_filter' => $this->contentFilter?->value,
            'username' => $this->username,
            'collections' => $collections,
            'topics' => $topics,
            'count' => $this->count,
        ]);
    }
}
