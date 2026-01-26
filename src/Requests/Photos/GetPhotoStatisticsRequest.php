<?php

declare(strict_types=1);

namespace AgentSoftware\Unsplash\Requests\Photos;

use AgentSoftware\Unsplash\Exceptions\InvalidRequestParameter;
use AgentSoftware\Unsplash\Requests\Request;
use AgentSoftware\Unsplash\Support\ArrayHelper;

final class GetPhotoStatisticsRequest extends Request
{
    public function __construct(
        public readonly string $photoId,
        public readonly ?int $quantity = null
    ) {
        if (trim($this->photoId) === '') {
            throw new InvalidRequestParameter('photoId cannot be empty.');
        }

        if ($this->quantity !== null && ($this->quantity < 1 || $this->quantity > 30)) {
            throw new InvalidRequestParameter('quantity must be between 1 and 30.');
        }
    }

    public function endpoint(): string
    {
        return '/photos/'.$this->photoId.'/statistics';
    }

    public function query(): array
    {
        return ArrayHelper::filterNulls([
            'quantity' => $this->quantity,
        ]);
    }
}
