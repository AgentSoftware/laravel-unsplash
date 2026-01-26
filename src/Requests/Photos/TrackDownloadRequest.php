<?php

declare(strict_types=1);

namespace AgentSoftware\Unsplash\Requests\Photos;

use AgentSoftware\Unsplash\Exceptions\InvalidRequestParameter;
use AgentSoftware\Unsplash\Requests\Request;

final class TrackDownloadRequest extends Request
{
    public function __construct(public readonly string $downloadLocation)
    {
        if ($this->downloadLocation === '') {
            throw new InvalidRequestParameter('downloadLocation cannot be empty.');
        }
    }

    public function endpoint(): string
    {
        return $this->downloadLocation;
    }

    public function query(): array
    {
        return [];
    }
}
