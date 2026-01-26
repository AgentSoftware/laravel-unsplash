<?php

declare(strict_types=1);

namespace AgentSoftware\Unsplash\Enums;

enum Orientation: string
{
    case Landscape = 'landscape';
    case Portrait = 'portrait';
    case Squarish = 'squarish';
}
