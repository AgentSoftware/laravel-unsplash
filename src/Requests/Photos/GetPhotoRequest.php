<?php

declare(strict_types=1);

namespace AgentSoftware\Unsplash\Requests\Photos;

use AgentSoftware\Unsplash\Requests\Request;

final class GetPhotoRequest extends Request
{
    public function __construct(public readonly string $photoId) {}

    public function endpoint(): string
    {
        return '/photos/'.$this->photoId;
    }

    public function query(): array
    {
        return [];
    }
}
