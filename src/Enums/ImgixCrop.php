<?php

declare(strict_types=1);

namespace AgentSoftware\Unsplash\Enums;

enum ImgixCrop: string
{
    case Top = 'top';
    case Bottom = 'bottom';
    case Left = 'left';
    case Right = 'right';
    case Faces = 'faces';
    case Focalpoint = 'focalpoint';
    case Edges = 'edges';
    case Entropy = 'entropy';
}
