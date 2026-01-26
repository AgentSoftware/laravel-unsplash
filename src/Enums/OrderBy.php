<?php

declare(strict_types=1);

namespace AgentSoftware\Unsplash\Enums;

enum OrderBy: string
{
    case Latest = 'latest';
    case Oldest = 'oldest';
    case Popular = 'popular';
    case Relevant = 'relevant';
}
