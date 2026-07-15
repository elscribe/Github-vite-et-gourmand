<?php
/**
 * @var list<array<string, mixed>> $orders
 * @var array<string, string> $statusLabels
 */
$formatDate = static fn (mixed $value): string => $value === null || $value === '' ? '' : date('d/m/Y', strtotime((string) $value));
?>
<section class="client-page client-orders-page">
    <div class="container client-container">
        <a class="client-back-link" href="/mon-compte">Retour à mon espace</a>

        <header class="client-hero client-hero-split">
            <div>
                <p class="client-kicker">Suivi client</p>
                <h1>Mes commandes</h1>
                <span>Consultez vos prestations, suivez les statuts et retrouvez chaque détail utile avant votre réception.</span>
            </div>
            <a class="client-button client-button-primary" href="/commandes/creation">Nouvelle commande</a>
        </header>

        <?php if ($orders === []): ?>
            <article class="client-card client-empty-state">
                <h2>Aucune commande pour le moment</h2>
                <p>Choisissez un menu pour créer votre première demande de prestation.</p>
                <a class="client-button client-button-primary" href="/menus">Choisir un menu</a>
            </article>
        <?php else: ?>
            <div class="client-orders-list">
                <?php foreach ($orders as $order): ?>
                    <?php $status = (string) $order['statut_actuel']; ?>
                    <article class="client-order-row">
                        <div class="client-order-row-main">
                            <div>
                                <p class="client-order-id">#CMD-<?= str_pad((string) (int) $order['id_commande'], 4, '0', STR_PAD_LEFT) ?></p>
                                <h2><?= $this->e($order['menu_titre']) ?></h2>
                            </div>
                            <span class="client-status-badge status-<?= $this->e($status) ?>"><?= $this->e($statusLabels[$status] ?? $status) ?></span>
                        </div>

                        <dl class="client-order-row-meta">
                            <div>
                                <dt>Date</dt>
                                <dd><?= $this->e($formatDate($order['date_prestation'])) ?> à <?= $this->e(substr((string) $order['heure_livraison'], 0, 5)) ?></dd>
                            </div>
                            <div>
                                <dt>Convives</dt>
                                <dd><?= (int) $order['nombre_personnes'] ?> personnes</dd>
                            </div>
                            <div>
                                <dt>Total</dt>
                                <dd><?= number_format((float) $order['prix_total'], 2, ',', ' ') ?> €</dd>
                            </div>
                        </dl>

                        <div class="client-order-row-actions">
                            <?php if ($status === 'en_attente'): ?>
                                <span>Modification possible avant acceptation.</span>
                            <?php elseif ($status === 'terminee' && empty($order['avis_id'])): ?>
                                <span>Avis disponible après prestation.</span>
                            <?php else: ?>
                                <span>Historique disponible.</span>
                            <?php endif; ?>
                            <?php if ($status === 'terminee' && empty($order['avis_id'])): ?>
                                <a class="client-button client-button-primary" href="/avis/creation/<?= (int) $order['id_commande'] ?>">Laisser un avis</a>
                            <?php endif; ?>
                            <a class="client-button client-button-secondary" href="/commandes/<?= (int) $order['id_commande'] ?>">Détail</a>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
