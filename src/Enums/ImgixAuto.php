<?php

declare(strict_types=1);

namespace AgentSoftware\Unsplash\Enums;

enum ImgixAuto: string
{
    case Compress = 'compress';
    case Enhance = 'enhance';
    case Format = 'format';
    case Redeye = 'redeye';
}
