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

        <div class="employee-order-list">
            <?php foreach ($orders as $order): ?>
                <article class="employee-order-card">
                    <div>
                        <p class="section-kicker">#<?= (int) $order['id_commande'] ?> - <?= $this->e($statusLabels[$order['statut_actuel']] ?? $order['statut_actuel']) ?></p>
                        <h2><?= $this->e($order['menu_titre']) ?></h2>
                        <p><?= $this->e($order['client_prenom']) ?> <?= $this->e($order['client_nom']) ?> - <?= $this->e($order['client_email']) ?></p>
                        <p><?= $this->e($order['date_prestation']) ?> a <?= $this->e(substr((string) $order['heure_livraison'], 0, 5)) ?> - <?= (int) $order['nombre_personnes'] ?> personnes</p>
                    </div>

                    <form action="/employe/commandes/<?= (int) $order['id_commande'] ?>/statut" method="post" class="employee-action-form">
                        <input type="hidden" name="_csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
                        <label for="statut-<?= (int) $order['id_commande'] ?>">Nouveau statut</label>
                        <select id="statut-<?= (int) $order['id_commande'] ?>" name="statut">
                            <?php foreach ($statusLabels as $value => $label): ?>
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
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
