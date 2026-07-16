<?php
/**
 * @var list<array<string, mixed>> $orders
 * @var array<string, string> $statusLabels
 * @var array{status: string, customer: string} $filters
 */
?>
<section class="page-section">
    <div class="container">
        <div class="section-heading">
            <p class="section-kicker">Espace employe</p>
            <h1>Gestion des commandes</h1>
            <p class="muted-text">Filtrez les commandes, mettez a jour les statuts et annulez apres contact client.</p>
        </div>

        <form class="menu-filters" action="/employe/commandes" method="get">
            <div>
                <label for="status">Statut</label>
                <select id="status" name="status">
                    <option value="">Tous</option>
                    <?php foreach ($statusLabels as $value => $label): ?>
                        <option value="<?= $this->e($value) ?>" <?= $filters['status'] === $value ? 'selected' : '' ?>><?= $this->e($label) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label for="customer">Client</label>
                <input id="customer" name="customer" type="search" value="<?= $this->e($filters['customer']) ?>">
            </div>
            <div class="menu-filters-actions">
                <button class="primary-link" type="submit">Filtrer</button>
                <a class="secondary-link" href="/employe/commandes">Reinitialiser</a>
            </div>
        </form>

        <?php if ($orders === []): ?>
            <div class="page-panel">
                <p>Aucune commande ne correspond aux filtres.</p>
            </div>
        <?php else: ?>
            <div class="employee-order-list">
                <?php foreach ($orders as $order): ?>
                    <?php $isClosed = in_array($order['statut_actuel'], ['terminee', 'annulee'], true); ?>
                    <article class="employee-order-card<?= $isClosed ? ' employee-order-card-closed' : '' ?>">
                        <div>
                            <p class="section-kicker">#<?= (int) $order['id_commande'] ?> - <?= $this->e($statusLabels[$order['statut_actuel']] ?? $order['statut_actuel']) ?></p>
                            <h2><?= $this->e($order['menu_titre']) ?></h2>
                            <p><?= $this->e($order['client_prenom']) ?> <?= $this->e($order['client_nom']) ?> - <?= $this->e($order['client_email']) ?></p>
                            <p>Tel. <?= $this->e($order['client_telephone']) ?> - <?= $this->e($order['ville_livraison']) ?></p>
                            <p><?= $this->e($order['date_prestation']) ?> a <?= $this->e(substr((string) $order['heure_livraison'], 0, 5)) ?> - <?= (int) $order['nombre_personnes'] ?> personnes</p>
                            <p><?= number_format((float) $order['prix_total'], 2, ',', ' ') ?> EUR</p>
                        </div>

                        <?php if ($isClosed): ?>
                            <div class="employee-order-locked">
                                <strong>Commande cloturee</strong>
                                <span>Aucune action employe n'est necessaire.</span>
                            </div>
                        <?php else: ?>
                            <form action="/employe/commandes/<?= (int) $order['id_commande'] ?>/statut" method="post" class="employee-action-form">
                                <input type="hidden" name="_csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
                                <label for="statut-<?= (int) $order['id_commande'] ?>">Nouveau statut</label>
                                <select id="statut-<?= (int) $order['id_commande'] ?>" name="statut">
                                    <?php foreach ($statusLabels as $value => $label): ?>
                                        <?php if ($value === 'annulee') {
                                            continue;
                                        } ?>
                                        <option value="<?= $this->e($value) ?>" <?= $order['statut_actuel'] === $value ? 'selected' : '' ?>><?= $this->e($label) ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <label for="commentaire-<?= (int) $order['id_commande'] ?>">Commentaire</label>
                                <input id="commentaire-<?= (int) $order['id_commande'] ?>" name="commentaire" type="text" placeholder="Precision interne">
                                <button class="primary-link" type="submit">Mettre a jour</button>
                            </form>

                            <form action="/employe/commandes/<?= (int) $order['id_commande'] ?>/annuler" method="post" class="employee-action-form">
                                <input type="hidden" name="_csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
                                <label for="mode-<?= (int) $order['id_commande'] ?>">Contact client</label>
                                <select id="mode-<?= (int) $order['id_commande'] ?>" name="mode_contact_modification">
                                    <option value="email">Email</option>
                                    <option value="gsm">GSM</option>
                                </select>
                                <label for="motif-<?= (int) $order['id_commande'] ?>">Motif</label>
                                <input id="motif-<?= (int) $order['id_commande'] ?>" name="motif_annulation" type="text" placeholder="Motif obligatoire">
                                <button class="secondary-button" type="submit">Annuler</button>
                            </form>
                        <?php endif; ?>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
