<?php

declare(strict_types=1);

/**
 * Configuration de la base de donnees SQL.
 *
 * Les donnees metier restent dans MySQL/MariaDB via PDO. Les statistiques
 * administrateur peuvent lire les agregats MongoDB avec mongosh, avec SQL en
 * secours quand MongoDB est indisponible en local.
 */

return [
    'default' => getenv('DB_CONNECTION') ?: 'mysql',
    'connections' => [
        'mysql' => [
            'driver' => 'mysql',
            'host' => getenv('DB_HOST') ?: 'localhost',
            'port' => getenv('DB_PORT') ?: '3306',
            'database' => getenv('DB_NAME') ?: 'vite_gourmand',
            'username' => getenv('DB_USER') ?: 'root',
            'password' => getenv('DB_PASSWORD') ?: '',
            'charset' => 'utf8mb4',
            'options' => [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                \PDO::ATTR_EMULATE_PREPARES => false,
                \PDO::ATTR_STRINGIFY_FETCHES => false,
                \PDO::ATTR_PERSISTENT => false,
            ],
        ],
    ],
];
