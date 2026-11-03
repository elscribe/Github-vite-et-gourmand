<?php

declare(strict_types=1);

namespace Tests\Support;

use PDO;
use RuntimeException;
use Throwable;

/**
 * Base MariaDB/MySQL temporaire pour les tests d'integration.
 *
 * Une base `vg_test_<aleatoire>` est creee a partir des scripts SQL du projet,
 * utilisee par tous les tests du processus, puis supprimee a la fin. Aucune
 * base existante n'est modifiee : le nom de la base du projet est remplace et
 * l'instruction DROP DATABASE du script est retiree avant execution.
 */
final class TestDatabase
{
    private static ?string $name = null;
    private static ?string $unavailableReason = null;

    public static function name(): string
    {
        if (self::$name !== null) {
            return self::$name;
        }

        if (self::$unavailableReason !== null) {
            throw new RuntimeException(self::$unavailableReason);
        }

        $name = 'vg_test_' . bin2hex(random_bytes(4));

        try {
            $server = self::serverConnection();
            $server->exec('CREATE DATABASE `' . $name . '` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
            self::$name = $name;
            register_shutdown_function([self::class, 'drop']);

            $server->exec('USE `' . $name . '`');
            $server->exec(self::script('create_database.sql', $name));
            $server->exec(self::script('seed_database.sql', $name));
        } catch (Throwable $exception) {
            self::$unavailableReason = 'Base de test indisponible : ' . $exception->getMessage();
            self::drop();
            throw new RuntimeException(self::$unavailableReason, 0, $exception);
        }

        putenv('DB_NAME=' . $name);
        $_ENV['DB_NAME'] = $name;

        return $name;
    }

    public static function pdo(): PDO
    {
        $pdo = self::serverConnection();
        $pdo->exec('USE `' . self::name() . '`');

        return $pdo;
    }

    public static function drop(): void
    {
        if (self::$name === null || !str_starts_with(self::$name, 'vg_test_')) {
            return;
        }

        try {
            self::serverConnection()->exec('DROP DATABASE IF EXISTS `' . self::$name . '`');
        } catch (Throwable) {
            // La suppression est tentee au mieux ; la base porte un nom jetable.
        }

        self::$name = null;
    }

    private static function serverConnection(): PDO
    {
        $dsn = sprintf(
            'mysql:host=%s;port=%s;charset=utf8mb4',
            getenv('DB_HOST') ?: 'localhost',
            getenv('DB_PORT') ?: '3306'
        );

        return new PDO($dsn, getenv('DB_USER') ?: 'root', getenv('DB_PASSWORD') ?: '', [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    }

    private static function script(string $file, string $name): string
    {
        $sql = (string) file_get_contents(dirname(__DIR__, 2) . '/database/sql/' . $file);
        $sql = (string) preg_replace('/^\s*DROP DATABASE[^;]*;\s*$/mi', '', $sql);
        $sql = (string) preg_replace('/^\s*CREATE DATABASE[^;]*;\s*$/mis', '', $sql);
        $sql = str_replace('vite_gourmand', $name, $sql);

        if (preg_match('/DROP\s+DATABASE/i', $sql) === 1 || str_contains($sql, 'vite_gourmand')) {
            throw new RuntimeException('Script SQL non isole : ' . $file);
        }

        return $sql;
    }
}
