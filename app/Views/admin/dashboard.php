<?php
/**
 * @var array{summary: array<string, mixed>, menuStats: list<array<string, mixed>>, monthlyStats: list<array<string, mixed>>, source: string} $dashboard
 * @var list<array<string, mixed>> $menus
 * @var array{menu: int, start: string, end: string} $filters
 */
$summary = $dashboard['summary'];
$maxRevenue = max(1, ...array_map(static fn (array $row): float => (float) $row['revenue'], $dashboard['menuStats']));
?>
<section class="page-section">
    <div class="container">
        <div class="section-heading">
            <p class="section-kicker">Administration</p>
            <h1>Tableau de bord</h1>
            <p class="muted-text"><?= $this->e($dashboard['source']) ?></p>
        </div>

        <form class="menu-filters" action="/admin" method="get">
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
                <label for="start">Debut</label>
                <input id="start" name="start" type="date" value="<?= $this->e($filters['start']) ?>">
            </div>
            <div>
                <label for="end">Fin</label>
                <input id="end" name="end" type="date" value="<?= $this->e($filters['end']) ?>">
            </div>
            <div class="menu-filters-actions">
                <button class="primary-link" type="submit">Filtrer</button>
                <a class="secondary-link" href="/admin">Reinitialiser</a>
            </div>
        </form>

        <div class="stats-grid">
            <div class="stat-card"><span>Commandes</span><strong><?= (int) $summary['total_orders'] ?></strong></div>
            <div class="stat-card"><span>Chiffre d'affaires</span><strong><?= number_format((float) $summary['total_revenue'], 2, ',', ' ') ?> EUR</strong></div>
            <div class="stat-card"><span>Panier moyen</span><strong><?= number_format((float) $summary['average_basket'], 2, ',', ' ') ?> EUR</strong></div>
            <div class="stat-card"><span>Menus actifs</span><strong><?= (int) $summary['active_menus'] ?></strong></div>
            <div class="stat-card"><span>Meilleur menu</span><strong><?= $this->e($summary['top_menu']) ?></strong></div>
            <div class="stat-card"><span>Note moyenne</span><strong><?= number_format((float) $summary['average_rating'], 1, ',', ' ') ?>/5</strong></div>
        </div>

        <div class="chart-panel">
            <h2>Chiffre d'affaires par menu</h2>
            <div class="bar-chart">
                <?php foreach ($dashboard['menuStats'] as $row): ?>
                    <?php $width = ((float) $row['revenue'] / $maxRevenue) * 100; ?>
                    <div class="bar-row">
                        <span><?= $this->e($row['menu_title']) ?></span>
                        <div><i style="width: <?= number_format($width, 2, '.', '') ?>%"></i></div>
                        <strong><?= number_format((float) $row['revenue'], 2, ',', ' ') ?> EUR</strong>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <a class="primary-link" href="/admin/statistiques">Voir le tableau detaille</a>
        <a class="secondary-link employee-dashboard-link" href="/admin/menus">Menus</a>
        <a class="secondary-link employee-dashboard-link" href="/admin/plats">Plats</a>
        <a class="secondary-link employee-dashboard-link" href="/admin/employes">Comptes employes</a>
        <a class="secondary-link employee-dashboard-link" href="/admin/horaires">Horaires</a>
    </div>
</section>
