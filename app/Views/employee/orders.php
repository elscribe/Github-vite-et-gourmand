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

                            <form action="/employe/commandes/<?= (int) $order['id_commande'] ?>/modifier" method="post" class="employee-action-form employee-order-edit-form">
                                <input type="hidden" name="_csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
                                <h3>Modifier apres contact client</h3>
                                <div class="employee-order-edit-grid">
                                    <div>
                                        <label for="date-prestation-<?= (int) $order['id_commande'] ?>">Date</label>
                                        <input id="date-prestation-<?= (int) $order['id_commande'] ?>" name="date_prestation" type="date" value="<?= $this->e($order['date_prestation']) ?>" required>
                                    </div>
                                    <div>
                                        <label for="heure-livraison-<?= (int) $order['id_commande'] ?>">Heure</label>
                                        <input id="heure-livraison-<?= (int) $order['id_commande'] ?>" name="heure_livraison" type="time" value="<?= $this->e(substr((string) $order['heure_livraison'], 0, 5)) ?>" required>
                                    </div>
                                    <div>
                                        <label for="nombre-personnes-<?= (int) $order['id_commande'] ?>">Personnes</label>
                                        <input id="nombre-personnes-<?= (int) $order['id_commande'] ?>" name="nombre_personnes" type="number" min="1" value="<?= (int) $order['nombre_personnes'] ?>" required>
                                    </div>
                                    <div>
                                        <label for="distance-km-<?= (int) $order['id_commande'] ?>">Distance km</label>
                                        <input id="distance-km-<?= (int) $order['id_commande'] ?>" name="distance_km" type="number" min="0" step="0.1" value="<?= $this->e((string) $order['distance_km']) ?>" required>
                                    </div>
                                    <div>
                                        <label for="adresse-livraison-<?= (int) $order['id_commande'] ?>">Adresse</label>
                                        <input id="adresse-livraison-<?= (int) $order['id_commande'] ?>" name="adresse_livraison" type="text" value="<?= $this->e($order['adresse_livraison']) ?>" required>
                                    </div>
                                    <div>
                                        <label for="ville-livraison-<?= (int) $order['id_commande'] ?>">Ville</label>
                                        <input id="ville-livraison-<?= (int) $order['id_commande'] ?>" name="ville_livraison" type="text" value="<?= $this->e($order['ville_livraison']) ?>" required>
                                    </div>
                                    <div>
                                        <label for="mode-modification-<?= (int) $order['id_commande'] ?>">Contact client</label>
                                        <select id="mode-modification-<?= (int) $order['id_commande'] ?>" name="mode_contact_modification" required>
                                            <option value="email" <?= $order['mode_contact_modification'] === 'email' ? 'selected' : '' ?>>Email</option>
                                            <option value="gsm" <?= $order['mode_contact_modification'] === 'gsm' ? 'selected' : '' ?>>GSM</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label for="motif-modification-<?= (int) $order['id_commande'] ?>">Motif</label>
                                        <textarea id="motif-modification-<?= (int) $order['id_commande'] ?>" name="motif_modification" rows="2" placeholder="Motif obligatoire" required></textarea>
                                    </div>
                                </div>
                                <button class="primary-link" type="submit">Modifier apres contact</button>
                            </form>
                        <?php endif; ?>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
