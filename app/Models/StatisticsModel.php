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
        $mongoDashboard = $this->mongoDashboard($filters);

        if ($mongoDashboard !== null) {
            return $mongoDashboard;
        }

        $menuStats = $this->menuStatistics($filters);

        return [
            'summary' => $this->summary($filters, $menuStats),
            'menuStats' => $menuStats,
            'monthlyStats' => $this->monthlyStatistics($filters),
            'source' => 'Secours SQL local : MongoDB indisponible pour cette requete',
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
     * @return array{summary: array<string, mixed>, menuStats: list<array<string, mixed>>, monthlyStats: list<array<string, mixed>>, source: string}|null
     */
    private function mongoDashboard(array $filters): ?array
    {
        foreach ($this->mongoDatabaseCandidates() as $database) {
            $payload = $this->runMongoScript($database, $this->mongoDashboardScript($filters));

            if ($payload === null) {
                continue;
            }

            $dashboard = $this->normalizeMongoDashboard($payload, $database);

            if ($dashboard !== null) {
                return $dashboard;
            }
        }

        return null;
    }

    /**
     * @return list<string>
     */
    private function mongoDatabaseCandidates(): array
    {
        $configured = getenv('NOSQL_DATABASE') ?: 'vite_gourmand';
        $candidates = [$configured, 'vite_gourmand'];

        return array_values(array_unique(array_filter($candidates)));
    }

    /**
     * @param array{menu?: int, start?: string, end?: string} $filters
     */
    private function mongoDashboardScript(array $filters): string
    {
        $payload = [
            'menu' => (int) ($filters['menu'] ?? 0),
            'startMonth' => $this->monthFromDate((string) ($filters['start'] ?? '')),
            'endMonth' => $this->monthFromDate((string) ($filters['end'] ?? '')),
        ];

        $json = json_encode($payload, JSON_THROW_ON_ERROR);

        return <<<JS
            const filters = {$json};
            const match = {};

            if (filters.menu > 0) {
              match.menuId = filters.menu;
            }

            if (filters.startMonth || filters.endMonth) {
              match.month = {};
              if (filters.startMonth) {
                match.month.\$gte = filters.startMonth;
              }
              if (filters.endMonth) {
                match.month.\$lte = filters.endMonth;
              }
            }

            const menuStats = db.menu_monthly_statistics.aggregate([
              { \$match: match },
              {
                \$group: {
                  _id: { menuId: '\$menuId', menuTitle: '\$menuTitle' },
                  orders: { \$sum: '\$orders' },
                  revenue: { \$sum: '\$revenue' },
                  averageBasket: { \$avg: '\$averageBasket' },
                  averageRating: { \$avg: '\$averageRating' },
                  lastOrder: { \$max: '\$lastOrder' }
                }
              },
              {
                \$project: {
                  _id: 0,
                  id_menu: '\$_id.menuId',
                  menu_title: '\$_id.menuTitle',
                  orders: 1,
                  revenue: 1,
                  average_basket: '\$averageBasket',
                  average_rating: '\$averageRating',
                  last_order: '\$lastOrder'
                }
              },
              { \$sort: { revenue: -1, orders: -1, menu_title: 1 } }
            ]).toArray();

            const monthlyStats = db.menu_monthly_statistics.aggregate([
              { \$match: match },
              {
                \$group: {
                  _id: '\$month',
                  orders: { \$sum: '\$orders' },
                  revenue: { \$sum: '\$revenue' },
                  averageBasket: { \$avg: '\$averageBasket' }
                }
              },
              {
                \$project: {
                  _id: 0,
                  month: '\$_id',
                  orders: 1,
                  revenue: 1,
                  average_basket: '\$averageBasket'
                }
              },
              { \$sort: { month: 1 } }
            ]).toArray();

            const totalOrders = menuStats.reduce((total, row) => total + Number(row.orders || 0), 0);
            const totalRevenue = menuStats.reduce((total, row) => total + Number(row.revenue || 0), 0);
            const ratingRows = menuStats.filter((row) => Number(row.average_rating || 0) > 0);
            const averageRating = ratingRows.length
              ? ratingRows.reduce((total, row) => total + Number(row.average_rating || 0), 0) / ratingRows.length
              : 0;

            print(JSON.stringify({
              summary: {
                total_orders: totalOrders,
                total_revenue: totalRevenue,
                average_basket: totalOrders > 0 ? totalRevenue / totalOrders : 0,
                average_rating: averageRating,
                active_menus: db.menu_statistics.countDocuments(),
                top_menu: menuStats[0]?.menu_title || 'Aucun menu'
              },
              menuStats,
              monthlyStats
            }));
        JS;
    }

    /**
     * @return array<string, mixed>|null
     */
    private function runMongoScript(string $database, string $script): ?array
    {
        if (!function_exists('proc_open')) {
            return null;
        }

        $process = @proc_open(
            ['mongosh', '--quiet', $database, '--eval', $script],
            [
                1 => ['pipe', 'w'],
                2 => ['pipe', 'w'],
            ],
            $pipes,
            dirname(__DIR__, 2)
        );

        if (!is_resource($process)) {
            return null;
        }

        $output = stream_get_contents($pipes[1]);
        $error = stream_get_contents($pipes[2]);
        fclose($pipes[1]);
        fclose($pipes[2]);

        $exitCode = proc_close($process);

        if ($exitCode !== 0 || trim((string) $output) === '' || trim((string) $error) !== '') {
            return null;
        }

        $decoded = json_decode(trim((string) $output), true);

        return is_array($decoded) ? $decoded : null;
    }

    /**
     * @param array<string, mixed> $payload
     *
     * @return array{summary: array<string, mixed>, menuStats: list<array<string, mixed>>, monthlyStats: list<array<string, mixed>>, source: string}|null
     */
    private function normalizeMongoDashboard(array $payload, string $database): ?array
    {
        $menuStats = $this->normalizeMongoRows($payload['menuStats'] ?? []);
        $monthlyStats = $this->normalizeMongoRows($payload['monthlyStats'] ?? []);

        if ($menuStats === [] && $monthlyStats === []) {
            return null;
        }

        $summary = is_array($payload['summary'] ?? null) ? $payload['summary'] : [];

        return [
            'summary' => [
                'total_orders' => (int) ($summary['total_orders'] ?? 0),
                'total_revenue' => (float) ($summary['total_revenue'] ?? 0),
                'average_basket' => (float) ($summary['average_basket'] ?? 0),
                'average_rating' => (float) ($summary['average_rating'] ?? 0),
                'active_menus' => (int) ($summary['active_menus'] ?? 0),
                'top_menu' => (string) ($summary['top_menu'] ?? 'Aucun menu'),
            ],
            'menuStats' => $menuStats,
            'monthlyStats' => $monthlyStats,
            'source' => 'Agregats MongoDB lus depuis la base ' . $database,
        ];
    }

    /**
     * @param mixed $rows
     *
     * @return list<array<string, mixed>>
     */
    private function normalizeMongoRows(mixed $rows): array
    {
        if (!is_array($rows)) {
            return [];
        }

        $normalized = [];

        foreach ($rows as $row) {
            if (!is_array($row)) {
                continue;
            }

            if (isset($row['last_order']) && is_string($row['last_order'])) {
                $row['last_order'] = substr($row['last_order'], 0, 10);
            }

            $normalized[] = $row;
        }

        return $normalized;
    }

    private function monthFromDate(string $date): string
    {
        return preg_match('/^\d{4}-\d{2}/', $date) === 1 ? substr($date, 0, 7) : '';
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
