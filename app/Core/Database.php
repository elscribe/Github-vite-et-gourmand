<?php

declare(strict_types=1);

namespace App\Core;

use PDO;

/**
 * Classe d'aide pour la connexion PDO a la base MySQL.
 *
 * Les modeles utiliseront cette classe plus tard lorsqu'ils devront lire ou
 * ecrire des donnees SQL. Aucune requete metier n'est implementee ici : cette
 * classe centralise seulement la connexion technique a la base de donnees.
 */
final class Database
{
    private ?PDO $connection = null;

    /**
     * @param array<string, mixed> $config Configuration optionnelle pour les tests.
     */
    public function __construct(private array $config = [])
    {
    }

    /**
     * Retourne une connexion PDO partagee pour la requete courante.
     */
    public function getConnection(): PDO
    {
        if ($this->connection instanceof PDO) {
            return $this->connection;
        }

        $config = $this->config ?: require dirname(__DIR__, 2) . '/config/database.php';
        $mysql = $config['connections']['mysql'];

        $dsn = sprintf(
            'mysql:host=%s;port=%s;dbname=%s;charset=%s',
            $mysql['host'],
            $mysql['port'],
            $mysql['database'],
            $mysql['charset']
        );

        $this->connection = new PDO(
            $dsn,
            $mysql['username'],
            $mysql['password'],
            $mysql['options']
        );

        return $this->connection;
    }
}
