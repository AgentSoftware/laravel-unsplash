<?php

declare(strict_types=1);

namespace AgentSoftware\Unsplash\Models\Concerns;

trait HasAttributionUrl
{
    public function attributionUrl(?string $utmSource = null, ?string $utmMedium = null): ?string
    {
        if ($this->html === null) {
            return null;
        }

        $params = array_filter([
            'utm_source' => $utmSource,
            'utm_medium' => $utmMedium,
        ]);

        if ($params === []) {
            return $this->html;
        }

        $separator = str_contains($this->html, '?') ? '&' : '?';

        return $this->html.$separator.http_build_query($params);
    }
}
