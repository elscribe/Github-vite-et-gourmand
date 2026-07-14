<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\BaseModel;

/**
 * Agregats statistiques pour le tableau de bord administrateur.
 */
final class StatisticsModel extends BaseModel
{
    /**
     * @param array{menu?: int, start?: string, end?: string} $filters
     *
     * @return array{summary: array<string, mixed>, menuStats: list<array<string, mixed>>, monthlyStats: list<array<string, mixed>>, source: string}
     */
    public function dashboard(array $filters = []): array
    {
        $menuStats = $this->menuStatistics($filters);

        return [
            'summary' => $this->summary($filters, $menuStats),
            'menuStats' => $menuStats,
            'monthlyStats' => $this->monthlyStatistics($filters),
            'source' => extension_loaded('mongodb')
                ? 'Agregats SQL, prets pour synchronisation MongoDB'
                : 'Agregats MariaDB locaux, extension PHP MongoDB absente',
        ];
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function menus(): array
    {
        $statement = $this->pdo()->query('SELECT id_menu, titre FROM menus WHERE actif = 1 ORDER BY titre ASC');

        return $statement->fetchAll();
    }

    /**
     * @param array{menu?: int, start?: string, end?: string} $filters
     *
     * @return list<array<string, mixed>>
     */
    private function menuStatistics(array $filters): array
    {
        $parameters = [];
        $joinConditions = ["c.id_menu = m.id_menu", "c.statut_actuel <> 'annulee'"];
        $whereConditions = ['m.actif = 1'];

        if (!empty($filters['menu'])) {
            $whereConditions[] = 'm.id_menu = :menu';
            $parameters['menu'] = $filters['menu'];
        }

        if (!empty($filters['start'])) {
            $joinConditions[] = 'c.date_commande >= :start_date';
            $parameters['start_date'] = $filters['start'] . ' 00:00:00';
        }

        if (!empty($filters['end'])) {
            $joinConditions[] = 'c.date_commande <= :end_date';
            $parameters['end_date'] = $filters['end'] . ' 23:59:59';
        }

        $joinClause = implode(' AND ', $joinConditions);
        $whereClause = implode(' AND ', $whereConditions);

        $sql = <<<SQL
            SELECT
                m.id_menu,
                m.titre AS menu_title,
                COUNT(c.id_commande) AS orders,
                COALESCE(SUM(c.prix_total), 0) AS revenue,
                COALESCE(AVG(c.prix_total), 0) AS average_basket,
                COALESCE(AVG(CASE WHEN a.statut = 'valide' THEN a.note END), 0) AS average_rating,
                MAX(c.date_prestation) AS last_order
            FROM menus m
            LEFT JOIN commandes c ON {$joinClause}
            LEFT JOIN avis a ON a.id_commande = c.id_commande
            WHERE {$whereClause}
            GROUP BY m.id_menu, m.titre
            ORDER BY revenue DESC, orders DESC, m.titre ASC
        SQL;

        $statement = $this->pdo()->prepare($sql);
        $statement->execute($parameters);

        return $statement->fetchAll();
    }

    /**
     * @param array{menu?: int, start?: string, end?: string} $filters
     * @param list<array<string, mixed>> $menuStats
     *
     * @return array<string, mixed>
     */
    private function summary(array $filters, array $menuStats): array
    {
        $parameters = [];
        $conditions = ["c.statut_actuel <> 'annulee'"];

        if (!empty($filters['menu'])) {
            $conditions[] = 'c.id_menu = :menu';
            $parameters['menu'] = $filters['menu'];
        }

        if (!empty($filters['start'])) {
            $conditions[] = 'c.date_commande >= :start_date';
            $parameters['start_date'] = $filters['start'] . ' 00:00:00';
        }

        if (!empty($filters['end'])) {
            $conditions[] = 'c.date_commande <= :end_date';
            $parameters['end_date'] = $filters['end'] . ' 23:59:59';
        }

        $sql = 'SELECT COUNT(*) AS total_orders, COALESCE(SUM(c.prix_total), 0) AS total_revenue, COALESCE(AVG(c.prix_total), 0) AS average_basket FROM commandes c WHERE ' . implode(' AND ', $conditions);
        $statement = $this->pdo()->prepare($sql);
        $statement->execute($parameters);
        $summary = $statement->fetch();

        $ratingStatement = $this->pdo()->query("SELECT COALESCE(AVG(note), 0) FROM avis WHERE statut = 'valide'");
        $activeMenusStatement = $this->pdo()->query('SELECT COUNT(*) FROM menus WHERE actif = 1');
        $topMenu = $menuStats[0]['menu_title'] ?? 'Aucun menu';

        return [
            'total_orders' => (int) ($summary['total_orders'] ?? 0),
            'total_revenue' => (float) ($summary['total_revenue'] ?? 0),
            'average_basket' => (float) ($summary['average_basket'] ?? 0),
            'average_rating' => (float) $ratingStatement->fetchColumn(),
            'active_menus' => (int) $activeMenusStatement->fetchColumn(),
            'top_menu' => $topMenu,
        ];
    }

    /**
     * @param array{menu?: int, start?: string, end?: string} $filters
     *
     * @return list<array<string, mixed>>
     */
    private function monthlyStatistics(array $filters): array
    {
        $parameters = [];
        $conditions = ["c.statut_actuel <> 'annulee'"];

        if (!empty($filters['menu'])) {
            $conditions[] = 'c.id_menu = :menu';
            $parameters['menu'] = $filters['menu'];
        }

        if (!empty($filters['start'])) {
            $conditions[] = 'c.date_commande >= :start_date';
            $parameters['start_date'] = $filters['start'] . ' 00:00:00';
        }

        if (!empty($filters['end'])) {
            $conditions[] = 'c.date_commande <= :end_date';
            $parameters['end_date'] = $filters['end'] . ' 23:59:59';
        }

        $sql = <<<SQL
            SELECT
                DATE_FORMAT(c.date_commande, '%Y-%m') AS month,
                COUNT(*) AS orders,
                COALESCE(SUM(c.prix_total), 0) AS revenue,
                COALESCE(AVG(c.prix_total), 0) AS average_basket
            FROM commandes c
            WHERE {conditions}
            GROUP BY DATE_FORMAT(c.date_commande, '%Y-%m')
            ORDER BY month ASC
        SQL;
        $sql = str_replace('{conditions}', implode(' AND ', $conditions), $sql);

        $statement = $this->pdo()->prepare($sql);
        $statement->execute($parameters);

        return $statement->fetchAll();
    }
}
