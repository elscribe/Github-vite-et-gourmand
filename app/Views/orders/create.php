<?php
/**
 * @var list<array<string, mixed>> $menus
 * @var int $selectedMenuId
 * @var array<string, mixed> $old
 * @var array<string, string> $errors
 */
$deliveryCity = (string) ($old['ville_livraison'] ?? 'Bordeaux');
$isBordeauxDelivery = strtolower(trim($deliveryCity)) === 'bordeaux';
$currentMenuId = (int) ($old['id_menu'] ?? $selectedMenuId);
$selectedMenu = null;

foreach ($menus as $menu) {
    if ((int) $menu['id_menu'] === $currentMenuId) {
        $selectedMenu = $menu;
        break;
    }
}
?>
<section class="client-page client-order-form-page">
    <div class="container client-container">
        <a class="client-back-link" href="/commandes">Retour à mes commandes</a>

        <header class="client-hero">
            <p class="client-kicker">Passer commande</p>
            <h1>Validation de votre commande</h1>
            <span>Complétez les détails de votre événement pour soumettre votre demande de commande à notre équipe.</span>
        </header>

        <div class="client-order-form-layout">
            <form class="client-form-card client-order-form-card" action="/commandes" method="post" data-order-form novalidate>
                <input type="hidden" name="_csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
                <h2>Informations de prestation</h2>

                <div class="form-field">
                    <label for="id_menu">Menu</label>
                    <select id="id_menu" name="id_menu" data-order-menu required>
                        <option value="">Choisir un menu</option>
                        <?php foreach ($menus as $menu): ?>
                            <?php $isSelected = (int) ($old['id_menu'] ?? $selectedMenuId) === (int) $menu['id_menu']; ?>
                            <option
                                value="<?= (int) $menu['id_menu'] ?>"
                                data-min="<?= (int) $menu['nombre_personnes_minimum'] ?>"
                                data-price="<?= (float) $menu['prix_minimum'] ?>"
                                data-title="<?= $this->e($menu['titre']) ?>"
                                data-description="<?= $this->e($menu['description']) ?>"
                                data-conditions="<?= $this->e($menu['conditions'] ?? '') ?>"
                                data-stock="<?= (int) $menu['stock_disponible'] ?>"
                                <?= $isSelected ? 'selected' : '' ?>
                            >
                                <?= $this->e($menu['titre']) ?> - minimum <?= (int) $menu['nombre_personnes_minimum'] ?> pers.
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (!empty($errors['id_menu'])): ?><p class="form-error"><?= $this->e($errors['id_menu']) ?></p><?php endif; ?>
                </div>

                <div class="client-menu-conditions<?= $selectedMenu === null ? ' is-empty' : '' ?>" data-order-menu-conditions>
                    <h3>Conditions importantes</h3>
                    <p data-order-menu-conditions-text><?= $this->e((string) ($selectedMenu['conditions'] ?? 'Sélectionnez un menu pour afficher les conditions importantes de commande.')) ?></p>
                </div>

                <div class="client-form-grid">
                    <div class="form-field">
                        <label for="date_prestation">Date de prestation</label>
                        <input id="date_prestation" name="date_prestation" type="date" value="<?= $this->e($old['date_prestation'] ?? '') ?>" required>
                        <?php if (!empty($errors['date_prestation'])): ?><p class="form-error"><?= $this->e($errors['date_prestation']) ?></p><?php endif; ?>
                    </div>
                    <div class="form-field">
                        <label for="heure_livraison">Heure souhaitée</label>
                        <input id="heure_livraison" name="heure_livraison" type="time" value="<?= $this->e($old['heure_livraison'] ?? '') ?>" required>
                        <?php if (!empty($errors['heure_livraison'])): ?><p class="form-error"><?= $this->e($errors['heure_livraison']) ?></p><?php endif; ?>
                    </div>
                    <div class="form-field">
                        <label for="nombre_personnes">Nombre de personnes</label>
                        <input id="nombre_personnes" name="nombre_personnes" type="number" min="1" data-order-people value="<?= $this->e($old['nombre_personnes'] ?? '') ?>" required>
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

                <button class="client-button client-button-primary" type="submit">Valider la commande</button>
            </form>

            <aside class="client-card client-order-preview-card" aria-live="polite" data-order-preview>
                <p class="client-kicker">Votre menu sélectionné</p>
                <h2 data-order-preview-title><?= $this->e((string) ($selectedMenu['titre'] ?? 'Choisir un menu')) ?></h2>
                <p class="client-order-preview-description" data-order-preview-description><?= $this->e((string) ($selectedMenu['description'] ?? 'Le récapitulatif se mettra à jour selon votre sélection.')) ?></p>
                <div class="client-price-lines client-price-lines-detailed">
                    <div><span>Prix estimé / pers.</span><strong data-order-preview-unit>0 € / pers.</strong></div>
                    <div><span>Nombre de personnes</span><strong data-order-preview-people>0</strong></div>
                    <div><span>Sous-total</span><strong data-order-preview-menu>0 €</strong></div>
                    <div><span>Remise éventuelle</span><strong class="client-price-discount" data-order-preview-discount>- 0 €</strong></div>
                    <div><span>Frais de livraison</span><strong data-order-preview-delivery>0 €</strong></div>
                    <div class="client-price-total"><span>Total estimé</span><strong data-order-preview-total>0,00 €</strong></div>
                </div>
                <div class="client-order-preview-meta">
                    <div><span>Minimum requis :</span><strong data-order-preview-minimum><?= $selectedMenu === null ? '0 personne' : (int) $selectedMenu['nombre_personnes_minimum'] . ' personnes' ?></strong></div>
                    <div><span>Stock disponible :</span><strong data-order-preview-stock><?= $selectedMenu === null ? '0 commande restante' : (int) $selectedMenu['stock_disponible'] . ' commandes restantes' ?></strong></div>
                </div>
                <p class="client-muted" data-order-preview-message>Sélectionnez un menu et un nombre de personnes pour afficher le prix estimé.</p>
            </aside>
        </div>
    </div>
</section>
