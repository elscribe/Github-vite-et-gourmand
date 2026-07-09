<?php

declare(strict_types=1);

namespace App\Core;

/**
 * Encapsule la configuration et les acces de base a la session PHP.
 */
final class Session
{
    /**
     * @param array<string, mixed> $config
     */
    public static function configure(array $config): void
    {
        if (session_status() !== PHP_SESSION_NONE) {
            return;
        }

        session_name((string) $config['name']);

        session_set_cookie_params([
            'lifetime' => (int) $config['lifetime_minutes'] * 60,
            'path' => '/',
            'domain' => '',
            'secure' => (bool) $config['secure'],
            'httponly' => (bool) $config['http_only'],
            'samesite' => (string) $config['same_site'],
        ]);
    }

    public static function start(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        return $_SESSION[$key] ?? $default;
    }

    public static function put(string $key, mixed $value): void
    {
        $_SESSION[$key] = $value;
    }

    public static function forget(string $key): void
    {
        unset($_SESSION[$key]);
    }

    public static function regenerate(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_regenerate_id(true);
        }
    }

    public static function flash(string $key, string $message): void
    {
        $_SESSION['_flash'][$key] = $message;
    }

    public static function pullFlash(string $key): ?string
    {
        $message = $_SESSION['_flash'][$key] ?? null;
        unset($_SESSION['_flash'][$key]);

        return is_string($message) ? $message : null;
    }
}
