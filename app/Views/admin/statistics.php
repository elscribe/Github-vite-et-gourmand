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
$lowestMonth = null;
$latestMonth = null;
$trendPointByMonth = [];
$monthlyMarkers = [];
$monthLabels = [
    '01' => 'Jan',
    '02' => 'Fév',
    '03' => 'Mar',
    '04' => 'Avr',
    '05' => 'Mai',
    '06' => 'Juin',
    '07' => 'Juil',
    '08' => 'Août',
    '09' => 'Sep',
    '10' => 'Oct',
    '11' => 'Nov',
    '12' => 'Déc',
];
$formatMonth = static function (string $month): string {
    return strlen($month) >= 7 ? substr($month, 5, 2) . '/' . substr($month, 0, 4) : $month;
};
$formatMonthLabel = static function (string $month) use ($monthLabels): string {
    if (strlen($month) < 7) {
        return $month;
    }

    $monthNumber = substr($month, 5, 2);
    $year = substr($month, 2, 2);

    return ($monthLabels[$monthNumber] ?? $monthNumber) . ' ' . $year;
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
    $trendPointByMonth[(string) $month['month']] = [
        'x' => $x,
        'y' => $y,
    ];
    $latestMonth = $month;

    if ($bestMonth === null || (float) $month['revenue'] > (float) $bestMonth['revenue']) {
        $bestMonth = $month;
    }

    if ($lowestMonth === null || (float) $month['revenue'] < (float) $lowestMonth['revenue']) {
        $lowestMonth = $month;
    }
}

$addMonthlyMarker = static function (string $label, ?array $month, string $className) use (&$monthlyMarkers, $trendPointByMonth): void {
    if ($month === null) {
        return;
    }

    $monthKey = (string) $month['month'];

    if (!isset($trendPointByMonth[$monthKey])) {
        return;
    }

    if (isset($monthlyMarkers[$monthKey])) {
        $monthlyMarkers[$monthKey]['label'] .= ' / ' . $label;
        return;
    }

    $x = (float) $trendPointByMonth[$monthKey]['x'];
    $y = (float) $trendPointByMonth[$monthKey]['y'];
    $anchor = 'middle';
    $labelX = $x;

    if ($x < 20) {
        $anchor = 'start';
        $labelX = max(2, $x);
    }

    if ($x > 80) {
        $anchor = 'end';
        $labelX = min(98, $x);
    }

    $monthlyMarkers[$monthKey] = [
        'label' => $label,
        'class' => $className,
        'x' => number_format($x, 2, '.', ''),
        'y' => number_format($y, 2, '.', ''),
        'label_x' => number_format($labelX, 2, '.', ''),
        'label_y' => number_format(max(11, $y - 11), 2, '.', ''),
        'anchor' => $anchor,
        'value' => number_format((float) $month['revenue'], 0, ',', ' ') . ' EUR',
    ];
};

$addMonthlyMarker('Meilleur', $bestMonth, 'is-best');
$addMonthlyMarker('Plus faible', $lowestMonth, 'is-lowest');
$addMonthlyMarker('Dernier', $latestMonth, 'is-latest');
?>
<section class="page-section">
    <div class="container">
        <a class="back-link" href="/admin">Retour admin</a>

        <div class="section-heading">
            <h1>Statistiques d&eacute;taill&eacute;es</h1>
            <p class="muted-text">Analysez les performances de vos ventes et l'&eacute;volution du chiffre d'affaires.</p>
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
                <div class="chart-panel-heading">
                    <div>
                        <h2>&Eacute;volution mensuelle du chiffre d'affaires</h2>
                        <p class="chart-help">Montant total factur&eacute; par mois sur la p&eacute;riode filtr&eacute;e.</p>
                    </div>
                    <span class="chart-unit">CA en EUR</span>
                </div>
                <div class="figma-line-chart monthly-revenue-chart">
                    <svg viewBox="0 0 100 100" role="img" aria-label="&Eacute;volution mensuelle du chiffre d'affaires en euros">
                        <line x1="0" y1="20" x2="100" y2="20"></line>
                        <line x1="0" y1="50" x2="100" y2="50"></line>
                        <line x1="0" y1="80" x2="100" y2="80"></line>
                        <?php if ($trendPoints !== []): ?>
                            <polyline points="<?= $this->e(implode(' ', $trendPoints)) ?>"></polyline>
                            <?php foreach ($trendPoints as $point): ?>
                                <?php [$x, $y] = explode(',', $point); ?>
                                <circle cx="<?= $this->e($x) ?>" cy="<?= $this->e($y) ?>" r="1.4"></circle>
                            <?php endforeach; ?>
                            <?php foreach ($monthlyMarkers as $marker): ?>
                                <g class="monthly-chart-marker <?= $this->e($marker['class']) ?>" aria-hidden="true">
                                    <circle class="monthly-chart-marker-dot" cx="<?= $this->e($marker['x']) ?>" cy="<?= $this->e($marker['y']) ?>" r="2.2"></circle>
                                    <text x="<?= $this->e($marker['label_x']) ?>" y="<?= $this->e($marker['label_y']) ?>" text-anchor="<?= $this->e($marker['anchor']) ?>">
                                        <tspan x="<?= $this->e($marker['label_x']) ?>"><?= $this->e($marker['label']) ?></tspan>
                                        <tspan x="<?= $this->e($marker['label_x']) ?>" dy="5"><?= $this->e($marker['value']) ?></tspan>
                                    </text>
                                </g>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </svg>
                    <div>
                        <?php foreach ($dashboard['monthlyStats'] as $month): ?>
                            <span><?= $this->e($formatMonthLabel((string) $month['month'])) ?></span>
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
                    <div>
                        <span>Mois le plus faible</span>
                        <strong><?= $lowestMonth === null ? '-' : $this->e($formatMonth((string) $lowestMonth['month'])) ?></strong>
                        <small><?= $lowestMonth === null ? '0,00 EUR' : number_format((float) $lowestMonth['revenue'], 2, ',', ' ') . ' EUR' ?></small>
                    </div>
                </div>
            </section>
        </div>
    </div>
</section>
