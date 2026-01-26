<?php

declare(strict_types=1);

namespace AgentSoftware\Unsplash\Enums;

enum ImgixFormat: string
{
    case Avif = 'avif';
    case Jp2 = 'jp2';
    case Jpg = 'jpg';
    case Json = 'json';
    case Jxr = 'jxr';
    case Pjpg = 'pjpg';
    case Png = 'png';
    case Png8 = 'png8';
    case Png32 = 'png32';
    case Webp = 'webp';
}
