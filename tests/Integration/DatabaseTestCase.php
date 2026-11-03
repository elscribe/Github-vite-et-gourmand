<?php

declare(strict_types=1);

namespace Tests\Integration;

use PDO;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use Tests\Support\TestDatabase;

/**
 * Socle des tests d'integration : base temporaire issue du jeu d'essai.
 *
 * Utilisateurs du jeu d'essai : 1 administrateur, 3 employe, 6 client.
 * Commande 9 : client 6, materiel_prete = FALSE.
 */
abstract class DatabaseTestCase extends TestCase
{
    protected const ADMIN_ID = 1;
    protected const EMPLOYEE_ID = 3;
    protected const CUSTOMER_ID = 6;
    protected const ORDER_ID = 9;

    protected PDO $pdo;

    protected function setUp(): void
    {
        try {
            $this->pdo = TestDatabase::pdo();
        } catch (RuntimeException $exception) {
            self::markTestSkipped($exception->getMessage());
        }
    }

    protected function setOrder(string $status, bool $loanedEquipment): void
    {
        $statement = $this->pdo->prepare('UPDATE commandes SET statut_actuel = :status, materiel_prete = :loaned WHERE id_commande = :id');
        $statement->execute(['status' => $status, 'loaned' => (int) $loanedEquipment, 'id' => self::ORDER_ID]);
    }

    protected function orderStatus(): string
    {
        return (string) $this->pdo->query('SELECT statut_actuel FROM commandes WHERE id_commande = ' . self::ORDER_ID)->fetchColumn();
    }

    protected function historyCount(): int
    {
        return (int) $this->pdo->query('SELECT COUNT(*) FROM commande_statuts WHERE id_commande = ' . self::ORDER_ID)->fetchColumn();
    }

    protected function setActive(int $userId, bool $active): void
    {
        $this->pdo->prepare('UPDATE utilisateurs SET actif = :actif WHERE id_utilisateur = :id')
            ->execute(['actif' => (int) $active, 'id' => $userId]);
    }
}
