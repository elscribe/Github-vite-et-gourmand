<?php
/**
 * @var array{summary: array<string, mixed>, menuStats: list<array<string, mixed>>, monthlyStats: list<array<string, mixed>>, source: string} $dashboard
 * @var list<array<string, mixed>> $menus
 * @var array{menu: int, start: string, end: string} $filters
 */
$maxRevenue = max(1, ...array_map(static fn (array $row): float => (float) $row['revenue'], $dashboard['menuStats']));
$maxMonthlyRevenue = max(1, ...array_map(static fn (array $row): float => (float) $row['revenue'], $dashboard['monthlyStats']));
$monthlyCount = count($dashboard['monthlyStats']);
$trendPoints = [];
$totalOrders = 0;
$totalRevenue = 0.0;
$totalBasket = 0.0;
$totalRating = 0.0;
$ratedRows = 0;
$bestMonth = null;
$latestMonth = null;
$formatMonth = static function (string $month): string {
    return strlen($month) >= 7 ? substr($month, 5, 2) . '/' . substr($month, 0, 4) : $month;
};

foreach ($dashboard['menuStats'] as $row) {
    $totalOrders += (int) $row['orders'];
    $totalRevenue += (float) $row['revenue'];
    $totalBasket += (float) $row['average_basket'];

    if ((float) $row['average_rating'] > 0) {
        $totalRating += (float) $row['average_rating'];
        $ratedRows++;
    }
}

foreach ($dashboard['monthlyStats'] as $index => $month) {
    $x = $monthlyCount <= 1 ? 0 : ($index / ($monthlyCount - 1)) * 100;
    $y = 100 - (((float) $month['revenue'] / $maxMonthlyRevenue) * 88);
    $trendPoints[] = number_format($x, 2, '.', '') . ',' . number_format($y, 2, '.', '');
    $latestMonth = $month;

    if ($bestMonth === null || (float) $month['revenue'] > (float) $bestMonth['revenue']) {
        $bestMonth = $month;
    }
}
?>
<section class="page-section">
    <div class="container">
        <a class="back-link" href="/admin">Retour admin</a>

        <div class="section-heading">
            <h1>Statistiques d&eacute;taill&eacute;es</h1>
            <p class="muted-text">Analysez les performances de vos ventes et l'&eacute;volution du chiffre d'affaires.</p>
            <p class="muted-text backoffice-source"><?= $this->e($dashboard['source']) ?></p>
        </div>

        <form class="menu-filters" action="/admin/statistiques" method="get">
            <div>
                <label for="menu">Menu</label>
                <select id="menu" name="menu">
                    <option value="">Tous</option>
                    <?php foreach ($menus as $menu): ?>
                        <option value="<?= (int) $menu['id_menu'] ?>" <?= $filters['menu'] === (int) $menu['id_menu'] ? 'selected' : '' ?>><?= $this->e($menu['titre']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label for="start">D&eacute;but</label>
                <input id="start" name="start" type="date" value="<?= $this->e($filters['start']) ?>">
            </div>
            <div>
                <label for="end">Fin</label>
                <input id="end" name="end" type="date" value="<?= $this->e($filters['end']) ?>">
            </div>
            <div class="menu-filters-actions">
                <button class="primary-link" type="submit">Filtrer</button>
                <a class="secondary-link" href="/admin/statistiques">R&eacute;initialiser</a>
            </div>
        </form>

        <section class="table-wrapper backoffice-table-card">
            <h2>Performance des menus</h2>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Menu</th>
                        <th>Commandes</th>
                        <th>Chiffre d'affaires</th>
                        <th>Panier moyen</th>
                        <th>Note moyenne</th>
                        <th>Derniere commande</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($dashboard['menuStats'] as $row): ?>
                        <tr>
                            <td><?= $this->e($row['menu_title']) ?></td>
                            <td><?= (int) $row['orders'] ?></td>
                            <td><?= number_format((float) $row['revenue'], 2, ',', ' ') ?> EUR</td>
                            <td><?= number_format((float) $row['average_basket'], 2, ',', ' ') ?> EUR</td>
                            <td><?= number_format((float) $row['average_rating'], 1, ',', ' ') ?>/5</td>
                            <td><?= $this->e($row['last_order'] ?? 'Aucune') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th>Total global</th>
                        <th><?= $totalOrders ?></th>
                        <th><?= number_format($totalRevenue, 2, ',', ' ') ?> EUR</th>
                        <th><?= number_format($totalOrders > 0 ? $totalRevenue / $totalOrders : 0, 2, ',', ' ') ?> EUR</th>
                        <th><?= number_format($ratedRows > 0 ? $totalRating / $ratedRows : 0, 1, ',', ' ') ?>/5</th>
                        <th>-</th>
                    </tr>
                </tfoot>
            </table>
        </section>

        <div class="backoffice-chart-grid">
            <section class="chart-panel">
                <h2>Chiffre d'affaires par menu</h2>
                <div class="revenue-bar-list">
                    <?php foreach ($dashboard['menuStats'] as $row): ?>
                        <?php $width = max(4, ((float) $row['revenue'] / $maxRevenue) * 100); ?>
                        <article class="revenue-bar-row">
                            <div class="revenue-bar-heading">
                                <strong><?= $this->e($row['menu_title']) ?></strong>
                                <span><?= number_format((float) $row['revenue'], 2, ',', ' ') ?> EUR</span>
                            </div>
                            <div class="revenue-bar-track" aria-hidden="true">
                                <i style="width: <?= number_format($width, 2, '.', '') ?>%"></i>
                            </div>
                            <small><?= (int) $row['orders'] ?> commandes - panier moyen <?= number_format((float) $row['average_basket'], 2, ',', ' ') ?> EUR</small>
                        </article>
                    <?php endforeach; ?>
                </div>
            </section>

            <section class="chart-panel">
                <h2>Evolution mensuelle</h2>
                <div class="figma-line-chart">
                    <svg viewBox="0 0 100 100" role="img" aria-label="Evolution mensuelle du chiffre d'affaires">
                        <line x1="0" y1="20" x2="100" y2="20"></line>
                        <line x1="0" y1="50" x2="100" y2="50"></line>
                        <line x1="0" y1="80" x2="100" y2="80"></line>
                        <?php if ($trendPoints !== []): ?>
                            <polyline points="<?= $this->e(implode(' ', $trendPoints)) ?>"></polyline>
                            <?php foreach ($trendPoints as $point): ?>
                                <?php [$x, $y] = explode(',', $point); ?>
                                <circle cx="<?= $this->e($x) ?>" cy="<?= $this->e($y) ?>" r="1.4"></circle>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </svg>
                    <div>
                        <?php foreach ($dashboard['monthlyStats'] as $month): ?>
                            <span><?= $this->e(substr((string) $month['month'], 5, 2)) ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="monthly-summary-grid">
                    <div>
                        <span>Meilleur mois</span>
                        <strong><?= $bestMonth === null ? '-' : $this->e($formatMonth((string) $bestMonth['month'])) ?></strong>
                        <small><?= $bestMonth === null ? '0,00 EUR' : number_format((float) $bestMonth['revenue'], 2, ',', ' ') . ' EUR' ?></small>
                    </div>
                    <div>
                        <span>Dernier mois affich&eacute;</span>
                        <strong><?= $latestMonth === null ? '-' : $this->e($formatMonth((string) $latestMonth['month'])) ?></strong>
                        <small><?= $latestMonth === null ? '0 commande' : (int) $latestMonth['orders'] . ' commandes' ?></small>
                    </div>
                </div>
            </section>
        </div>
    </div>
</section>
