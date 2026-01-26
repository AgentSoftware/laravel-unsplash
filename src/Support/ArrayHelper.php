<?php

declare(strict_types=1);

namespace AgentSoftware\Unsplash\Support;

final class ArrayHelper
{
    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public static function filterNulls(array $data): array
    {
        return array_filter($data, static fn ($value) => $value !== null);
    }
}
