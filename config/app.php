<?php

declare(strict_types=1);

/**
 * Configuration principale de l'application.
 *
 * Ce fichier garde des reglages simples en dehors des controleurs. Les valeurs
 * peuvent venir des variables d'environnement pour differencier les
 * configurations locale, de test et de production sans modifier le code source.
 */

return [
    'name' => getenv('APP_NAME') ?: 'Vite & Gourmand',
    'env' => getenv('APP_ENV') ?: 'local',
    'debug' => filter_var(getenv('APP_DEBUG') ?: false, FILTER_VALIDATE_BOOL),
    'display_errors' => filter_var(getenv('APP_DISPLAY_ERRORS') ?: false, FILTER_VALIDATE_BOOL),
    'log_errors' => filter_var(getenv('APP_LOG_ERRORS') ?: true, FILTER_VALIDATE_BOOL),
    'log_path' => getenv('APP_LOG_PATH') ?: dirname(__DIR__) . '/storage/logs/app.log',
    'timezone' => getenv('APP_TIMEZONE') ?: 'Europe/Paris',
    'url' => getenv('APP_URL') ?: 'http://localhost:8000',
];
