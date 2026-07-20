<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\BaseModel;

/**
 * Acces aux avis clients et a leur moderation.
 */
final class ReviewModel extends BaseModel
{
    /**
     * @return list<array<string, mixed>>
     */
    public function findValidated(int $limit = 3): array
    {
        $sql = <<<SQL
            SELECT
                a.note,
                a.commentaire,
                a.created_at,
                u.prenom,
                u.nom,
                m.titre AS menu_titre
            FROM avis a
            INNER JOIN utilisateurs u ON u.id_utilisateur = a.id_utilisateur
            INNER JOIN commandes c ON c.id_commande = a.id_commande
            INNER JOIN menus m ON m.id_menu = c.id_menu
            WHERE a.statut = 'valide'
            ORDER BY a.created_at DESC
            LIMIT :limit
        SQL;

        $statement = $this->pdo()->prepare($sql);
        $statement->bindValue('limit', $limit, \PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll();
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function findAllForModeration(): array
    {
        $sql = <<<SQL
            SELECT
                a.*,
                u.prenom,
                u.nom,
                u.email,
                m.titre AS menu_titre
            FROM avis a
            INNER JOIN utilisateurs u ON u.id_utilisateur = a.id_utilisateur
            INNER JOIN commandes c ON c.id_commande = a.id_commande
            INNER JOIN menus m ON m.id_menu = c.id_menu
            ORDER BY
                CASE a.statut WHEN 'en_attente' THEN 0 WHEN 'valide' THEN 1 ELSE 2 END,
                a.created_at DESC
        SQL;

        $statement = $this->pdo()->query($sql);

        return $statement->fetchAll();
    }

    public function countPending(): int
    {
        $statement = $this->pdo()->query("SELECT COUNT(*) FROM avis WHERE statut = 'en_attente'");

        return (int) $statement->fetchColumn();
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function findPendingForDashboard(int $limit = 3): array
    {
        $sql = <<<SQL
            SELECT
                a.id_avis,
                a.note,
                a.commentaire,
                a.created_at,
                u.prenom,
                u.nom,
                u.email,
                m.titre AS menu_titre
            FROM avis a
            INNER JOIN utilisateurs u ON u.id_utilisateur = a.id_utilisateur
            INNER JOIN commandes c ON c.id_commande = a.id_commande
            INNER JOIN menus m ON m.id_menu = c.id_menu
            WHERE a.statut = 'en_attente'
            ORDER BY a.created_at DESC, a.id_avis DESC
            LIMIT :limit
        SQL;

        $statement = $this->pdo()->prepare($sql);
        $statement->bindValue('limit', $limit, \PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll();
    }

    /**
     * @return array<string, mixed>|null
     */
    public function findReviewableOrder(int $orderId, int $userId): ?array
    {
        $sql = <<<SQL
            SELECT
                c.id_commande,
                c.id_utilisateur,
                c.statut_actuel,
                m.titre AS menu_titre,
                a.id_avis
            FROM commandes c
            INNER JOIN menus m ON m.id_menu = c.id_menu
            LEFT JOIN avis a ON a.id_commande = c.id_commande
            WHERE c.id_commande = :order_id
              AND c.id_utilisateur = :user_id
              AND c.statut_actuel = 'terminee'
            LIMIT 1
        SQL;

        $statement = $this->pdo()->prepare($sql);
        $statement->execute([
            'order_id' => $orderId,
            'user_id' => $userId,
        ]);

        $order = $statement->fetch();

        return $order === false ? null : $order;
    }

    public function create(int $userId, int $orderId, int $note, string $commentaire): bool
    {
        $order = $this->findReviewableOrder($orderId, $userId);

        if ($order === null || $order['id_avis'] !== null || $note < 1 || $note > 5 || $commentaire === '') {
            return false;
        }

        $statement = $this->pdo()->prepare(
            "INSERT INTO avis (id_utilisateur, id_commande, note, commentaire, statut)
             VALUES (:user_id, :order_id, :note, :commentaire, 'en_attente')"
        );

        return $statement->execute([
            'user_id' => $userId,
            'order_id' => $orderId,
            'note' => $note,
            'commentaire' => $commentaire,
        ]);
    }

    public function moderate(int $reviewId, int $moderatorId, string $status): bool
    {
        if (!in_array($status, ['valide', 'refuse'], true)) {
            return false;
        }

        $statement = $this->pdo()->prepare(
            'UPDATE avis SET statut = :status, moderated_at = CURRENT_TIMESTAMP, moderated_by = :moderator_id WHERE id_avis = :review_id'
        );
        $statement->execute([
            'status' => $status,
            'moderator_id' => $moderatorId,
            'review_id' => $reviewId,
        ]);

        return $statement->rowCount() === 1;
    }
}
