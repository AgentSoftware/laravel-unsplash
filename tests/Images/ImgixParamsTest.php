<?php

declare(strict_types=1);

use AgentSoftware\Unsplash\Enums\ImgixAuto;
use AgentSoftware\Unsplash\Enums\ImgixCrop;
use AgentSoftware\Unsplash\Enums\ImgixFit;
use AgentSoftware\Unsplash\Enums\ImgixFormat;
use AgentSoftware\Unsplash\Images\ImgixParams;

it('builds imgix query parameters', function (): void {
    $params = new ImgixParams(
        width: 1200,
        height: 800,
        quality: 80,
        dpr: 2.0,
        format: ImgixFormat::Jpg,
        fit: ImgixFit::Max,
        crop: ImgixCrop::Entropy,
        auto: [ImgixAuto::Compress, ImgixAuto::Format]
    );

    expect($params->toQuery())->toMatchArray([
        'w' => 1200,
        'h' => 800,
        'q' => 80,
        'dpr' => 2.0,
        'fm' => 'jpg',
        'fit' => 'max',
        'crop' => 'entropy',
        'auto' => 'compress,format',
    ]);
});
