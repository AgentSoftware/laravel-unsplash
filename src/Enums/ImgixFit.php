<?php

declare(strict_types=1);

namespace AgentSoftware\Unsplash\Enums;

enum ImgixFit: string
{
    case Clamp = 'clamp';
    case Crop = 'crop';
    case FaceArea = 'facearea';
    case Fill = 'fill';
    case FillMax = 'fillmax';
    case Max = 'max';
    case Min = 'min';
    case Scale = 'scale';
}
