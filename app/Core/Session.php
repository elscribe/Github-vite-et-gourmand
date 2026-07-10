<?php

declare(strict_types=1);

namespace App\Core;

/**
 * Centralise la configuration de session PHP, le stockage temporaire,
 * les messages flash et les informations d'authentification.
 */
final class Session
{
    private const USER_ID_KEY = 'auth_user_id';
    private const USER_ROLE_KEY = 'auth_user_role';

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

    public static function login(int $userId, string $role): void
    {
        self::regenerate();

        $_SESSION[self::USER_ID_KEY] = $userId;
        $_SESSION[self::USER_ROLE_KEY] = $role;
    }

    public static function logout(): void
    {
        unset($_SESSION[self::USER_ID_KEY], $_SESSION[self::USER_ROLE_KEY]);

        self::regenerate();
    }

    public static function isAuthenticated(): bool
    {
        return self::userId() !== null;
    }

    public static function userId(): ?int
    {
        $userId = $_SESSION[self::USER_ID_KEY] ?? null;

        return is_int($userId) ? $userId : null;
    }

    public static function role(): ?string
    {
        $role = $_SESSION[self::USER_ROLE_KEY] ?? null;

        return is_string($role) && $role !== '' ? $role : null;
    }

    /**
     * @param list<string> $roles
     */
    public static function hasRole(array $roles): bool
    {
        $currentRole = self::role();

        return $currentRole !== null && in_array($currentRole, $roles, true);
    }

    public static function destroy(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            return;
        }

        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $cookieParams = session_get_cookie_params();

            setcookie(
                session_name(),
                '',
                [
                    'expires' => time() - 42000,
                    'path' => $cookieParams['path'],
                    'domain' => $cookieParams['domain'],
                    'secure' => $cookieParams['secure'],
                    'httponly' => $cookieParams['httponly'],
                    'samesite' => $cookieParams['samesite'] ?? 'Lax',
                ]
            );
        }

        session_destroy();
    }
}
