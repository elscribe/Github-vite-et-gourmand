<?php
/**
 * @var array<string, mixed> $order
 * @var array<string, mixed> $old
 * @var array<string, string> $errors
 */
$deliveryCity = (string) ($old['ville_livraison'] ?? '');
$isBordeauxDelivery = strtolower(trim($deliveryCity)) === 'bordeaux';
?>
<section class="client-page client-order-form-page">
    <div class="container client-container">
        <a class="client-back-link" href="/commandes/<?= (int) $order['id_commande'] ?>">Retour à la commande</a>

        <header class="client-hero">
            <p class="client-kicker">Commande #<?= (int) $order['id_commande'] ?></p>
            <h1>Modifier la commande</h1>
            <span>La modification est possible uniquement tant que la commande reste en attente de validation.</span>
        </header>

        <?php if (!empty($errors['statut'])): ?>
            <p class="alert-message error-message"><?= $this->e($errors['statut']) ?></p>
        <?php endif; ?>

        <div class="client-order-form-layout">
            <form
                class="client-form-card client-order-form-card"
                action="/commandes/<?= (int) $order['id_commande'] ?>/modifier"
                method="post"
                data-order-form
                data-order-fixed-min="<?= (int) $order['nombre_personnes_minimum'] ?>"
                data-order-fixed-price="<?= (float) $order['prix_minimum'] ?>"
                novalidate
            >
                <input type="hidden" name="_csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
                <h2>Prestation à ajuster</h2>

                <div class="client-order-current-menu">
                    <strong><?= $this->e($order['menu_titre']) ?></strong>
                    <span>Minimum <?= (int) $order['nombre_personnes_minimum'] ?> personnes</span>
                </div>

                <div class="client-form-grid">
                    <div class="form-field">
                        <label for="date_prestation">Date de prestation</label>
                        <input id="date_prestation" name="date_prestation" type="date" value="<?= $this->e($old['date_prestation'] ?? '') ?>" required>
                        <?php if (!empty($errors['date_prestation'])): ?><p class="form-error"><?= $this->e($errors['date_prestation']) ?></p><?php endif; ?>
                    </div>
                    <div class="form-field">
                        <label for="heure_livraison">Heure souhaitée</label>
                        <input id="heure_livraison" name="heure_livraison" type="time" value="<?= $this->e(substr((string) ($old['heure_livraison'] ?? ''), 0, 5)) ?>" required>
                        <?php if (!empty($errors['heure_livraison'])): ?><p class="form-error"><?= $this->e($errors['heure_livraison']) ?></p><?php endif; ?>
                    </div>
                    <div class="form-field">
                        <label for="nombre_personnes">Nombre de personnes</label>
                        <input id="nombre_personnes" name="nombre_personnes" type="number" min="<?= (int) $order['nombre_personnes_minimum'] ?>" data-order-people value="<?= $this->e($old['nombre_personnes'] ?? '') ?>" required>
                        <?php if (!empty($errors['nombre_personnes'])): ?><p class="form-error"><?= $this->e($errors['nombre_personnes']) ?></p><?php endif; ?>
                    </div>
                    <p class="client-form-section-label client-field-wide">Lieu de livraison</p>
                    <div class="form-field client-field-wide">
                        <label for="adresse_livraison">Adresse</label>
                        <input id="adresse_livraison" name="adresse_livraison" type="text" placeholder="Ex : 9 chemin des Vignes" value="<?= $this->e($old['adresse_livraison'] ?? '') ?>" required>
                        <?php if (!empty($errors['adresse_livraison'])): ?><p class="form-error"><?= $this->e($errors['adresse_livraison']) ?></p><?php endif; ?>
                    </div>
                    <div class="form-field">
                        <label for="code_postal_livraison">Code postal</label>
                        <input id="code_postal_livraison" name="code_postal_livraison" type="text" inputmode="numeric" maxlength="5" pattern="\d{5}" value="<?= $this->e($old['code_postal_livraison'] ?? '33000') ?>" required>
                        <?php if (!empty($errors['code_postal_livraison'])): ?><p class="form-error"><?= $this->e($errors['code_postal_livraison']) ?></p><?php endif; ?>
                    </div>
                    <div class="form-field">
                        <label for="ville_livraison">Ville</label>
                        <input id="ville_livraison" name="ville_livraison" type="text" data-order-city value="<?= $this->e($deliveryCity) ?>" required>
                        <?php if (!empty($errors['ville_livraison'])): ?><p class="form-error"><?= $this->e($errors['ville_livraison']) ?></p><?php endif; ?>
                    </div>
                    <div class="form-field client-field-wide client-distance-field<?= $isBordeauxDelivery ? ' is-hidden' : '' ?>" data-order-distance-field>
                        <label for="distance_km">Distance hors Bordeaux en km</label>
                        <p>La livraison est offerte dans Bordeaux. Hors Bordeaux, indiquez une distance approximative depuis Bordeaux ; l'équipe la vérifiera avant validation.</p>
                        <div class="client-distance-control">
                            <input id="distance_km" name="distance_km" type="number" min="0" step="0.01" data-order-distance value="<?= $this->e($old['distance_km'] ?? '0') ?>"<?= $isBordeauxDelivery ? ' disabled' : '' ?>>
                            <span>km depuis Bordeaux</span>
                        </div>
                        <?php if (!empty($errors['distance_km'])): ?><p class="form-error"><?= $this->e($errors['distance_km']) ?></p><?php endif; ?>
                    </div>
                    <p class="client-form-section-label client-field-wide">Demandes particulières</p>
                    <div class="form-field client-field-wide">
                        <label for="commentaire_client">Commentaire pour l'équipe</label>
                        <textarea id="commentaire_client" name="commentaire_client" rows="4" maxlength="1000" placeholder="Ex : allergène à signaler, code d'accès, étage, interphone, horaire à éviter..."><?= $this->e($old['commentaire_client'] ?? '') ?></textarea>
                        <?php if (!empty($errors['commentaire_client'])): ?><p class="form-error"><?= $this->e($errors['commentaire_client']) ?></p><?php endif; ?>
                    </div>
                </div>

                <button class="client-button client-button-primary" type="submit">Enregistrer les modifications</button>
            </form>

            <aside class="client-card client-order-preview-card" aria-live="polite" data-order-preview>
                <p class="client-kicker">Estimation</p>
                <h2>Nouveau récapitulatif</h2>
                <div class="client-price-lines">
                    <div><span>Menu</span><strong data-order-preview-menu>0,00 €</strong></div>
                    <div><span>Remise</span><strong data-order-preview-discount>0,00 €</strong></div>
                    <div><span>Livraison</span><strong data-order-preview-delivery>0,00 €</strong></div>
                    <div class="client-price-total"><span>Total estimé</span><strong data-order-preview-total>0,00 €</strong></div>
                </div>
                <p class="client-muted" data-order-preview-message>La nouvelle estimation se met à jour automatiquement.</p>
            </aside>
        </div>
    </div>
</section>
