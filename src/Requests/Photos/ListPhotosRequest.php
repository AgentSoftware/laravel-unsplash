<?php

declare(strict_types=1);

namespace AgentSoftware\Unsplash\Requests\Photos;

use AgentSoftware\Unsplash\Enums\OrderBy;
use AgentSoftware\Unsplash\Exceptions\InvalidRequestParameter;
use AgentSoftware\Unsplash\Requests\Request;
use AgentSoftware\Unsplash\Support\ArrayHelper;

final class ListPhotosRequest extends Request
{
    public function __construct(
        public readonly ?int $page = null,
        public readonly ?int $perPage = null,
        public readonly ?OrderBy $orderBy = null
    ) {
        if ($this->perPage !== null && ($this->perPage < 1 || $this->perPage > 30)) {
            throw new InvalidRequestParameter('perPage must be between 1 and 30.');
        }
    }

    public function endpoint(): string
    {
        return '/photos';
    }

    public function query(): array
    {
        return ArrayHelper::filterNulls([
            'page' => $this->page,
            'per_page' => $this->perPage,
            'order_by' => $this->orderBy?->value,
        ]);
    }
}
