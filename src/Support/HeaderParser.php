<?php

declare(strict_types=1);

namespace AgentSoftware\Unsplash\Support;

final class HeaderParser
{
    /**
     * @param  array<string, array<int, string>|string>  $headers
     */
    public static function getString(array $headers, string $key): ?string
    {
        $normalizedKey = strtolower($key);

        foreach ($headers as $headerKey => $values) {
            if (strtolower($headerKey) === $normalizedKey) {
                if (is_array($values) && isset($values[0])) {
                    return $values[0];
                }
                if (is_string($values)) {
                    return $values;
                }
            }
        }

        return null;
    }

    /**
     * @param  array<string, array<int, string>|string>  $headers
     */
    public static function getInt(array $headers, string $key): ?int
    {
        $value = self::getString($headers, $key);

        return $value !== null ? (int) $value : null;
    }
}
