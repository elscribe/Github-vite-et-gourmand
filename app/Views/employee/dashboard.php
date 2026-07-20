<?php
/**
 * @var list<array<string, mixed>> $orders
 * @var array<string, string> $statusLabels
 */
$pendingCount = count(array_filter($orders, static fn (array $order): bool => $order['statut_actuel'] === 'en_attente'));
$activeCount = count(array_filter($orders, static fn (array $order): bool => !in_array($order['statut_actuel'], ['terminee', 'annulee'], true)));
?>
<section class="page-section">
    <div class="container">
        <div class="section-heading">
            <p class="section-kicker">Espace employe</p>
            <h1>Tableau de bord</h1>
            <p class="muted-text">Vue rapide des commandes a traiter.</p>
        </div>

        <div class="stats-grid">
            <div class="stat-card"><span>Commandes actives</span><strong><?= $activeCount ?></strong></div>
            <div class="stat-card"><span>En attente</span><strong><?= $pendingCount ?></strong></div>
            <div class="stat-card"><span>Total suivi</span><strong><?= count($orders) ?></strong></div>
        </div>

        <a class="primary-link" href="/employe/commandes">Gerer les commandes</a>
        <a class="secondary-link employee-dashboard-link" href="/employe/avis">Moderer les avis</a>
    </div>
</section>
