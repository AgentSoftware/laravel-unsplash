<?php

declare(strict_types=1);

namespace AgentSoftware\Unsplash\Images;

use AgentSoftware\Unsplash\Enums\ImgixAuto;
use AgentSoftware\Unsplash\Enums\ImgixColorSpace;
use AgentSoftware\Unsplash\Enums\ImgixCrop;
use AgentSoftware\Unsplash\Enums\ImgixFit;
use AgentSoftware\Unsplash\Enums\ImgixFormat;
use AgentSoftware\Unsplash\Support\ArrayHelper;

final class ImgixParams
{
    /**
     * @param  array<int, ImgixAuto>  $auto
     */
    public function __construct(
        public readonly ?int $width = null,
        public readonly ?int $height = null,
        public readonly ?int $quality = null,
        public readonly ?float $dpr = null,
        public readonly ?int $blur = null,
        public readonly ?string $rect = null,
        public readonly ?string $trim = null,
        public readonly ?string $background = null,
        public readonly ?string $fill = null,
        public readonly ?ImgixFormat $format = null,
        public readonly ?ImgixFit $fit = null,
        public readonly ?ImgixCrop $crop = null,
        public readonly ?ImgixColorSpace $colorSpace = null,
        public readonly array $auto = []
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function toQuery(): array
    {
        $autoValue = $this->auto === [] ? null : implode(',', array_map(
            static fn (ImgixAuto $item) => $item->value,
            $this->auto
        ));

        return ArrayHelper::filterNulls([
            'w' => $this->width,
            'h' => $this->height,
            'q' => $this->quality,
            'dpr' => $this->dpr,
            'blur' => $this->blur,
            'rect' => $this->rect,
            'trim' => $this->trim,
            'bg' => $this->background,
            'fill' => $this->fill,
            'fm' => $this->format?->value,
            'fit' => $this->fit?->value,
            'crop' => $this->crop?->value,
            'cs' => $this->colorSpace?->value,
            'auto' => $autoValue,
        ]);
    }
}
