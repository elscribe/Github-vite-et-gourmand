<?php
/**
 * @var array<string, mixed> $order
 * @var list<array<string, mixed>> $history
 * @var array<string, string> $statusLabels
 */
$status = (string) $order['statut_actuel'];
?>
<section class="page-section">
    <div class="container">
        <a class="back-link" href="/commandes">Retour aux commandes</a>

        <div class="order-detail">
            <div class="menu-detail-header">
                <p class="section-kicker">Commande #<?= (int) $order['id_commande'] ?></p>
                <h1><?= $this->e($order['menu_titre']) ?></h1>
                <p><span class="status-badge"><?= $this->e($statusLabels[$status] ?? $status) ?></span></p>
            </div>

            <div class="menu-detail-content">
                <div class="menu-detail-main">
                    <h2>Livraison</h2>
                    <p><?= $this->e($order['date_prestation']) ?> a <?= $this->e(substr((string) $order['heure_livraison'], 0, 5)) ?></p>
                    <p><?= $this->e($order['adresse_livraison']) ?>, <?= $this->e($order['ville_livraison']) ?></p>

                    <h2>Historique</h2>
                    <ol class="timeline">
                        <?php foreach ($history as $event): ?>
                            <li>
                                <strong><?= $this->e($statusLabels[$event['statut']] ?? $event['statut']) ?></strong>
                                <span><?= $this->e($event['created_at']) ?></span>
                                <?php if (!empty($event['commentaire'])): ?>
                                    <p><?= $this->e($event['commentaire']) ?></p>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; ?>
                    </ol>
                </div>

                <aside class="menu-detail-summary">
                    <dl>
                        <div><dt>Personnes</dt><dd><?= (int) $order['nombre_personnes'] ?></dd></div>
                        <div><dt>Menu</dt><dd><?= number_format((float) $order['prix_menu'], 2, ',', ' ') ?> EUR</dd></div>
                        <div><dt>Remise</dt><dd><?= number_format((float) $order['remise'], 2, ',', ' ') ?> EUR</dd></div>
                        <div><dt>Livraison</dt><dd><?= number_format((float) $order['prix_livraison'], 2, ',', ' ') ?> EUR</dd></div>
                        <div><dt>Total</dt><dd><?= number_format((float) $order['prix_total'], 2, ',', ' ') ?> EUR</dd></div>
                    </dl>

                    <?php if ($status === 'en_attente'): ?>
                        <a class="primary-link" href="/commandes/<?= (int) $order['id_commande'] ?>/modifier">Modifier</a>
                        <form class="inline-form" action="/commandes/<?= (int) $order['id_commande'] ?>/annuler" method="post">
                            <input type="hidden" name="_csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
                            <label for="motif_annulation">Motif d'annulation</label>
                            <textarea id="motif_annulation" name="motif_annulation" rows="3">Annulation demandee par le client.</textarea>
                            <button class="secondary-button" type="submit">Annuler la commande</button>
                        </form>
                    <?php elseif ($status === 'terminee' && empty($order['avis_id'])): ?>
                        <a class="primary-link" href="/avis/creation/<?= (int) $order['id_commande'] ?>">Deposer un avis</a>
                    <?php elseif (!empty($order['avis_id'])): ?>
                        <p class="muted-text">Avis : <?= $this->e($order['avis_statut']) ?></p>
                    <?php endif; ?>
                </aside>
            </div>
        </div>
    </div>
</section>
