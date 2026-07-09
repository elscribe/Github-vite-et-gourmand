<?php

declare(strict_types=1);

namespace App\Core;

/**
 * Helpers securite generiques, sans logique d'authentification.
 */
final class Security
{
    public static function escape(mixed $value): string
    {
        return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }

    public static function csrfToken(): string
    {
        $token = Session::get('_csrf_token');

        if (is_string($token) && $token !== '') {
            return $token;
        }

        $token = self::randomToken();
        Session::put('_csrf_token', $token);

        return $token;
    }

    public static function verifyCsrfToken(?string $token): bool
    {
        $sessionToken = Session::get('_csrf_token');

        return is_string($sessionToken)
            && is_string($token)
            && hash_equals($sessionToken, $token);
    }

    public static function randomToken(int $bytes = 32): string
    {
        return bin2hex(random_bytes($bytes));
    }

    public static function hashToken(string $token): string
    {
        return hash('sha256', $token);
    }
}
