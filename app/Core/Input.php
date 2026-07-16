<?php

declare(strict_types=1);

namespace App\Core;

/**
 * Acces centralise aux donnees HTTP brutes.
 */
final class Input
{
    public static function method(): string
    {
        return strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
    }

    public static function path(): string
    {
        return parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
    }

    public static function getString(string $key, string $default = ''): string
    {
        $value = $_GET[$key] ?? $default;

        return is_scalar($value) ? trim((string) $value) : $default;
    }

    public static function postString(string $key, string $default = ''): string
    {
        $value = $_POST[$key] ?? $default;

        return is_scalar($value) ? trim((string) $value) : $default;
    }

    public static function postInt(string $key, int $default = 0): int
    {
        $value = $_POST[$key] ?? null;

        return filter_var($value, FILTER_VALIDATE_INT) === false ? $default : (int) $value;
    }

    /**
     * @return list<mixed>
     */
    public static function postArray(string $key): array
    {
        $value = $_POST[$key] ?? [];

        return is_array($value) ? array_values($value) : [];
    }
}
