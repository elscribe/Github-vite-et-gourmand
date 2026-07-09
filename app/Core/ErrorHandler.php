<?php

declare(strict_types=1);

namespace App\Core;

use App\Controllers\ErrorController;
use ErrorException;
use Throwable;

/**
 * Convertit les erreurs PHP en exceptions et affiche une page 500 controlee.
 */
final class ErrorHandler
{
    /**
     * @param array<string, mixed> $config
     */
    public static function register(array $config): void
    {
        error_reporting(E_ALL);
        ini_set('display_errors', (bool) $config['display_errors'] ? '1' : '0');
        ini_set('log_errors', (bool) $config['log_errors'] ? '1' : '0');

        if (!empty($config['log_path'])) {
            ini_set('error_log', (string) $config['log_path']);
        }

        set_error_handler(static function (int $severity, string $message, string $file, int $line): bool {
            if ((error_reporting() & $severity) === 0) {
                return false;
            }

            throw new ErrorException($message, 0, $severity, $file, $line);
        });

        set_exception_handler(static function (Throwable $throwable) use ($config): void {
            self::renderException($throwable, (bool) $config['debug']);
        });
    }

    private static function renderException(Throwable $throwable, bool $debug): void
    {
        if (!headers_sent()) {
            http_response_code(500);
        }

        error_log((string) $throwable);

        try {
            (new ErrorController())->serverError($debug ? $throwable : null);
        } catch (Throwable) {
            echo 'Une erreur interne est survenue.';
        }
    }
}
