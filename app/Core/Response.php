<?php

declare(strict_types=1);

namespace App\Core;

/**
 * Helpers de reponse HTTP reutilisables par les controleurs.
 */
final class Response
{
    public static function status(int $code): void
    {
        http_response_code($code);
    }

    public static function redirect(string $path, int $statusCode = 302): void
    {
        http_response_code($statusCode);
        header('Location: ' . $path);
        exit;
    }

    /**
     * @param array<string, mixed> $payload
     */
    public static function json(array $payload, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode($payload, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE);
    }
}
