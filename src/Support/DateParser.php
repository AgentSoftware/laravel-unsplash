<?php

declare(strict_types=1);

namespace AgentSoftware\Unsplash\Support;

use Exception;
use Illuminate\Support\Carbon;

final class DateParser
{
    public static function parse(?string $value): ?Carbon
    {
        if ($value === null || $value === '') {
            return null;
        }

        try {
            return Carbon::parse($value);
        } catch (Exception) {
            return null;
        }
    }
}
