<?php
/**
 * @var array{summary: array<string, mixed>, menuStats: list<array<string, mixed>>, monthlyStats: list<array<string, mixed>>, source: string} $dashboard
 * @var list<array<string, mixed>> $menus
 * @var array{menu: int, start: string, end: string} $filters
 */
?>
<section class="page-section">
    <div class="container">
        <a class="back-link" href="/admin">Retour admin</a>

        <div class="section-heading">
            <p class="section-kicker">Statistiques</p>
            <h1>Analyse par menu et periode</h1>
            <p class="muted-text"><?= $this->e($dashboard['source']) ?></p>
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
                <label for="start">Debut</label>
                <input id="start" name="start" type="date" value="<?= $this->e($filters['start']) ?>">
            </div>
            <div>
                <label for="end">Fin</label>
                <input id="end" name="end" type="date" value="<?= $this->e($filters['end']) ?>">
            </div>
            <div class="menu-filters-actions">
                <button class="primary-link" type="submit">Filtrer</button>
                <a class="secondary-link" href="/admin/statistiques">Reinitialiser</a>
            </div>
        </form>

        <div class="table-wrapper">
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
            </table>
        </div>

        <div class="chart-panel">
            <h2>Evolution mensuelle</h2>
            <div class="compact-list">
                <?php foreach ($dashboard['monthlyStats'] as $month): ?>
                    <span>
                        <strong><?= $this->e($month['month']) ?></strong>
                        <?= (int) $month['orders'] ?> commandes -
                        <?= number_format((float) $month['revenue'], 2, ',', ' ') ?> EUR
                    </span>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>
