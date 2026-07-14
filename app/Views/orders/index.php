<?php
/**
 * @var list<array<string, mixed>> $orders
 * @var array<string, string> $statusLabels
 */
?>
<section class="page-section">
    <div class="container">
        <div class="section-heading">
            <p class="section-kicker">Suivi client</p>
            <h1>Mes commandes</h1>
            <p class="muted-text">Suivez les statuts et retrouvez le detail de vos commandes.</p>
        </div>

        <div class="page-actions">
            <a class="primary-link" href="/commandes/creation">Nouvelle commande</a>
        </div>

        <?php if ($orders === []): ?>
            <div class="page-panel">
                <p>Aucune commande n'est encore rattachee a votre compte.</p>
                <a class="primary-link" href="/menus">Choisir un menu</a>
            </div>
        <?php else: ?>
            <div class="table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Commande</th>
                            <th>Menu</th>
                            <th>Date</th>
                            <th>Statut</th>
                            <th>Total</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $order): ?>
                            <tr>
                                <td>#<?= (int) $order['id_commande'] ?></td>
                                <td><?= $this->e($order['menu_titre']) ?></td>
                                <td><?= $this->e($order['date_prestation']) ?> a <?= $this->e(substr((string) $order['heure_livraison'], 0, 5)) ?></td>
                                <td><span class="status-badge"><?= $this->e($statusLabels[$order['statut_actuel']] ?? $order['statut_actuel']) ?></span></td>
                                <td><?= number_format((float) $order['prix_total'], 2, ',', ' ') ?> EUR</td>
                                <td><a class="secondary-link" href="/commandes/<?= (int) $order['id_commande'] ?>">Detail</a></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</section>
