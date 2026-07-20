<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\BaseModel;
use PDO;

/**
 * Acces aux commandes et aux regles de prix associees.
 */
final class OrderModel extends BaseModel
{
    /**
     * @return array<string, string>
     */
    public function statusLabels(): array
    {
        return [
            'en_attente' => 'En attente',
            'acceptee' => 'Acceptée',
            'en_preparation' => 'En préparation',
            'en_cours_de_livraison' => 'En cours de livraison',
            'livre' => 'Livrée',
            'en_attente_retour_materiel' => 'Retour matériel',
            'terminee' => 'Terminée',
            'annulee' => 'Annulée',
        ];
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function findForUser(int $userId): array
    {
        $sql = <<<SQL
            SELECT
                c.*,
                m.titre AS menu_titre,
                a.id_avis AS avis_id,
                a.statut AS avis_statut
            FROM commandes c
            INNER JOIN menus m ON m.id_menu = c.id_menu
            LEFT JOIN avis a ON a.id_commande = c.id_commande
            WHERE c.id_utilisateur = :user_id
            ORDER BY c.date_commande DESC, c.id_commande DESC
        SQL;

        $statement = $this->pdo()->prepare($sql);
        $statement->execute(['user_id' => $userId]);

        return $statement->fetchAll();
    }

    /**
     * @return array<string, mixed>|null
     */
    public function findCurrentForUser(int $userId): ?array
    {
        $sql = <<<SQL
            SELECT
                c.*,
                m.titre AS menu_titre,
                a.id_avis AS avis_id,
                a.statut AS avis_statut
            FROM commandes c
            INNER JOIN menus m ON m.id_menu = c.id_menu
            LEFT JOIN avis a ON a.id_commande = c.id_commande
            WHERE c.id_utilisateur = :user_id
              AND c.statut_actuel NOT IN ('terminee', 'annulee')
            ORDER BY
                FIELD(c.statut_actuel, 'en_attente', 'acceptee', 'en_preparation', 'en_cours_de_livraison', 'livre', 'en_attente_retour_materiel') DESC,
                c.date_prestation ASC,
                c.id_commande DESC
            LIMIT 1
        SQL;

        $statement = $this->pdo()->prepare($sql);
        $statement->execute(['user_id' => $userId]);
        $order = $statement->fetch();

        return $order === false ? null : $order;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function findReviewableForUser(int $userId): ?array
    {
        $sql = <<<SQL
            SELECT
                c.*,
                m.titre AS menu_titre,
                a.id_avis AS avis_id,
                a.statut AS avis_statut
            FROM commandes c
            INNER JOIN menus m ON m.id_menu = c.id_menu
            LEFT JOIN avis a ON a.id_commande = c.id_commande
            WHERE c.id_utilisateur = :user_id
              AND c.statut_actuel = 'terminee'
              AND a.id_avis IS NULL
            ORDER BY c.date_prestation DESC, c.id_commande DESC
            LIMIT 1
        SQL;

        $statement = $this->pdo()->prepare($sql);
        $statement->execute(['user_id' => $userId]);
        $order = $statement->fetch();

        return $order === false ? null : $order;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function findOneForUser(int $orderId, int $userId): ?array
    {
        $sql = <<<SQL
            SELECT
                c.*,
                m.titre AS menu_titre,
                m.nombre_personnes_minimum,
                m.prix_minimum,
                a.id_avis AS avis_id,
                a.statut AS avis_statut
            FROM commandes c
            INNER JOIN menus m ON m.id_menu = c.id_menu
            LEFT JOIN avis a ON a.id_commande = c.id_commande
            WHERE c.id_commande = :order_id
              AND c.id_utilisateur = :user_id
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

    /**
     * @param array{status?: string, customer?: string} $filters
     *
     * @return list<array<string, mixed>>
     */
    public function findAll(array $filters = []): array
    {
        $conditions = [];
        $parameters = [];

        if (($filters['status'] ?? '') !== '') {
            $conditions[] = 'c.statut_actuel = :status';
            $parameters['status'] = $filters['status'];
        }

        if (($filters['customer'] ?? '') !== '') {
            $conditions[] = '(u.email LIKE :customer OR u.nom LIKE :customer OR u.prenom LIKE :customer)';
            $parameters['customer'] = '%' . $filters['customer'] . '%';
        }

        $whereClause = $conditions === [] ? '' : 'WHERE ' . implode(' AND ', $conditions);

        $sql = <<<SQL
            SELECT
                c.*,
                m.titre AS menu_titre,
                u.email AS client_email,
                u.nom AS client_nom,
                u.prenom AS client_prenom
            FROM commandes c
            INNER JOIN menus m ON m.id_menu = c.id_menu
            INNER JOIN utilisateurs u ON u.id_utilisateur = c.id_utilisateur
            {$whereClause}
            ORDER BY c.date_prestation ASC, c.heure_livraison ASC, c.id_commande ASC
        SQL;

        $statement = $this->pdo()->prepare($sql);
        $statement->execute($parameters);

        return $statement->fetchAll();
    }

    /**
     * @return array<string, mixed>|null
     */
    public function findOneForEmployee(int $orderId): ?array
    {
        $sql = <<<SQL
            SELECT
                c.*,
                m.titre AS menu_titre,
                u.email AS client_email,
                u.nom AS client_nom,
                u.prenom AS client_prenom,
                u.telephone AS client_telephone
            FROM commandes c
            INNER JOIN menus m ON m.id_menu = c.id_menu
            INNER JOIN utilisateurs u ON u.id_utilisateur = c.id_utilisateur
            WHERE c.id_commande = :order_id
            LIMIT 1
        SQL;

        $statement = $this->pdo()->prepare($sql);
        $statement->execute(['order_id' => $orderId]);

        $order = $statement->fetch();

        return $order === false ? null : $order;
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function findHistory(int $orderId): array
    {
        $sql = <<<SQL
            SELECT
                s.*,
                u.nom,
                u.prenom,
                u.email
            FROM commande_statuts s
            LEFT JOIN utilisateurs u ON u.id_utilisateur = s.id_utilisateur
            WHERE s.id_commande = :order_id
            ORDER BY s.created_at ASC, s.id_statut ASC
        SQL;

        $statement = $this->pdo()->prepare($sql);
        $statement->execute(['order_id' => $orderId]);

        return $statement->fetchAll();
    }

    /**
     * @param array{date_prestation: string, heure_livraison: string, adresse_livraison: string, code_postal_livraison: string, ville_livraison: string, distance_km: float, commentaire_client: string, nombre_personnes: int} $data
     */
    public function create(int $userId, int $menuId, array $data): int
    {
        $menu = $this->findMenuForOrder($menuId);

        if ($menu === null) {
            return 0;
        }

        $totals = $this->calculateTotals(
            $menu,
            $data['nombre_personnes'],
            $data['ville_livraison'],
            $data['distance_km']
        );

        $this->pdo()->beginTransaction();

        try {
            $sql = <<<SQL
                INSERT INTO commandes (
                    id_utilisateur,
                    id_menu,
                    date_prestation,
                    heure_livraison,
                    adresse_livraison,
                    code_postal_livraison,
                    ville_livraison,
                    distance_km,
                    commentaire_client,
                    nombre_personnes,
                    prix_menu,
                    remise,
                    prix_livraison,
                    prix_total,
                    statut_actuel
                ) VALUES (
                    :id_utilisateur,
                    :id_menu,
                    :date_prestation,
                    :heure_livraison,
                    :adresse_livraison,
                    :code_postal_livraison,
                    :ville_livraison,
                    :distance_km,
                    :commentaire_client,
                    :nombre_personnes,
                    :prix_menu,
                    :remise,
                    :prix_livraison,
                    :prix_total,
                    'en_attente'
                )
            SQL;

            $statement = $this->pdo()->prepare($sql);
            $statement->execute([
                'id_utilisateur' => $userId,
                'id_menu' => $menuId,
                'date_prestation' => $data['date_prestation'],
                'heure_livraison' => $data['heure_livraison'],
                'adresse_livraison' => $data['adresse_livraison'],
                'code_postal_livraison' => $data['code_postal_livraison'],
                'ville_livraison' => $data['ville_livraison'],
                'distance_km' => $data['distance_km'],
                'commentaire_client' => $data['commentaire_client'],
                'nombre_personnes' => $data['nombre_personnes'],
                'prix_menu' => $totals['prix_menu'],
                'remise' => $totals['remise'],
                'prix_livraison' => $totals['prix_livraison'],
                'prix_total' => $totals['prix_total'],
            ]);

            $orderId = (int) $this->pdo()->lastInsertId();
            $this->insertStatus($orderId, $userId, 'en_attente', 'Commande creee par le client.');
            $this->pdo()->commit();

            return $orderId;
        } catch (\Throwable $exception) {
            $this->pdo()->rollBack();
            throw $exception;
        }
    }

    /**
     * @param array{date_prestation: string, heure_livraison: string, adresse_livraison: string, code_postal_livraison: string, ville_livraison: string, distance_km: float, commentaire_client: string, nombre_personnes: int} $data
     */
    public function updatePendingForUser(int $orderId, int $userId, array $data): bool
    {
        $order = $this->findOneForUser($orderId, $userId);

        if ($order === null || $order['statut_actuel'] !== 'en_attente') {
            return false;
        }

        $menu = $this->findMenuForOrder((int) $order['id_menu']);

        if ($menu === null) {
            return false;
        }

        $totals = $this->calculateTotals(
            $menu,
            $data['nombre_personnes'],
            $data['ville_livraison'],
            $data['distance_km']
        );

        $this->pdo()->beginTransaction();

        try {
            $sql = <<<SQL
                UPDATE commandes
                SET
                    date_prestation = :date_prestation,
                    heure_livraison = :heure_livraison,
                    adresse_livraison = :adresse_livraison,
                    code_postal_livraison = :code_postal_livraison,
                    ville_livraison = :ville_livraison,
                    distance_km = :distance_km,
                    commentaire_client = :commentaire_client,
                    nombre_personnes = :nombre_personnes,
                    prix_menu = :prix_menu,
                    remise = :remise,
                    prix_livraison = :prix_livraison,
                    prix_total = :prix_total
                WHERE id_commande = :order_id
                  AND id_utilisateur = :user_id
                  AND statut_actuel = 'en_attente'
            SQL;

            $statement = $this->pdo()->prepare($sql);
            $statement->execute([
                'date_prestation' => $data['date_prestation'],
                'heure_livraison' => $data['heure_livraison'],
                'adresse_livraison' => $data['adresse_livraison'],
                'code_postal_livraison' => $data['code_postal_livraison'],
                'ville_livraison' => $data['ville_livraison'],
                'distance_km' => $data['distance_km'],
                'commentaire_client' => $data['commentaire_client'],
                'nombre_personnes' => $data['nombre_personnes'],
                'prix_menu' => $totals['prix_menu'],
                'remise' => $totals['remise'],
                'prix_livraison' => $totals['prix_livraison'],
                'prix_total' => $totals['prix_total'],
                'order_id' => $orderId,
                'user_id' => $userId,
            ]);

            $this->insertStatus($orderId, $userId, 'en_attente', 'Commande modifiee par le client.');
            $this->pdo()->commit();

            return true;
        } catch (\Throwable $exception) {
            $this->pdo()->rollBack();
            throw $exception;
        }
    }

    public function cancelPendingForUser(int $orderId, int $userId, string $motif): bool
    {
        $this->pdo()->beginTransaction();

        try {
            $statement = $this->pdo()->prepare(
                "UPDATE commandes
                 SET statut_actuel = 'annulee', motif_annulation = :motif
                 WHERE id_commande = :order_id
                   AND id_utilisateur = :user_id
                   AND statut_actuel = 'en_attente'"
            );
            $statement->execute([
                'motif' => $motif,
                'order_id' => $orderId,
                'user_id' => $userId,
            ]);

            if ($statement->rowCount() !== 1) {
                $this->pdo()->rollBack();
                return false;
            }

            $this->insertStatus($orderId, $userId, 'annulee', $motif);
            $this->pdo()->commit();

            return true;
        } catch (\Throwable $exception) {
            $this->pdo()->rollBack();
            throw $exception;
        }
    }

    public function changeStatusByEmployee(int $orderId, int $userId, string $status, string $comment): bool
    {
        if (!array_key_exists($status, $this->statusLabels())) {
            return false;
        }

        $this->pdo()->beginTransaction();

        try {
            $statement = $this->pdo()->prepare(
                'UPDATE commandes SET statut_actuel = :status WHERE id_commande = :order_id'
            );
            $statement->execute([
                'status' => $status,
                'order_id' => $orderId,
            ]);

            if ($statement->rowCount() !== 1) {
                $this->pdo()->rollBack();
                return false;
            }

            $this->insertStatus($orderId, $userId, $status, $comment);
            $this->pdo()->commit();

            return true;
        } catch (\Throwable $exception) {
            $this->pdo()->rollBack();
            throw $exception;
        }
    }

    public function cancelByEmployee(int $orderId, int $userId, string $modeContact, string $motif): bool
    {
        if (!in_array($modeContact, ['gsm', 'email'], true) || $motif === '') {
            return false;
        }

        $this->pdo()->beginTransaction();

        try {
            $statement = $this->pdo()->prepare(
                "UPDATE commandes
                 SET statut_actuel = 'annulee',
                     mode_contact_modification = :mode_contact,
                     motif_annulation = :motif
                 WHERE id_commande = :order_id"
            );
            $statement->execute([
                'mode_contact' => $modeContact,
                'motif' => $motif,
                'order_id' => $orderId,
            ]);

            if ($statement->rowCount() !== 1) {
                $this->pdo()->rollBack();
                return false;
            }

            $this->insertStatus($orderId, $userId, 'annulee', $motif);
            $this->pdo()->commit();

            return true;
        } catch (\Throwable $exception) {
            $this->pdo()->rollBack();
            throw $exception;
        }
    }

    /**
     * @param array<string, mixed> $menu
     *
     * @return array{prix_menu: float, remise: float, prix_livraison: float, prix_total: float}
     */
    public function calculateTotals(array $menu, int $people, string $city, float $distanceKm): array
    {
        $minimumPeople = max(1, (int) $menu['nombre_personnes_minimum']);
        $minimumPrice = (float) $menu['prix_minimum'];
        $pricePerPerson = $minimumPrice / $minimumPeople;
        $menuPrice = round($pricePerPerson * $people, 2);
        $discount = $people >= $minimumPeople + 5 ? round($menuPrice * 0.10, 2) : 0.00;
        $deliveryPrice = strtolower(trim($city)) === 'bordeaux' ? 0.00 : round(5 + ($distanceKm * 0.59), 2);
        $totalPrice = round($menuPrice - $discount + $deliveryPrice, 2);

        return [
            'prix_menu' => $menuPrice,
            'remise' => $discount,
            'prix_livraison' => $deliveryPrice,
            'prix_total' => $totalPrice,
        ];
    }

    /**
     * @return array<string, mixed>|null
     */
    public function findMenuForOrder(int $menuId): ?array
    {
        $statement = $this->pdo()->prepare(
            'SELECT id_menu, titre, prix_minimum, nombre_personnes_minimum, stock_disponible FROM menus WHERE id_menu = :menu_id AND actif = 1 LIMIT 1'
        );
        $statement->execute(['menu_id' => $menuId]);

        $menu = $statement->fetch();

        return $menu === false ? null : $menu;
    }

    private function insertStatus(int $orderId, int $userId, string $status, string $comment): void
    {
        $statement = $this->pdo()->prepare(
            'INSERT INTO commande_statuts (id_commande, id_utilisateur, statut, commentaire) VALUES (:order_id, :user_id, :status, :comment)'
        );
        $statement->execute([
            'order_id' => $orderId,
            'user_id' => $userId,
            'status' => $status,
            'comment' => $comment,
        ]);
    }
}
