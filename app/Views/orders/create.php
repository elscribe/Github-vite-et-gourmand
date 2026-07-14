<?php
/**
 * @var list<array<string, mixed>> $menus
 * @var int $selectedMenuId
 * @var array<string, mixed> $old
 * @var array<string, string> $errors
 */
?>
<section class="page-section">
    <div class="container">
        <div class="section-heading">
            <p class="section-kicker">Commande</p>
            <h1>Commander un menu</h1>
            <p class="muted-text">Le total est calcule automatiquement selon le nombre de personnes, la remise et la livraison.</p>
        </div>

        <form class="order-form" action="/commandes" method="post" data-order-form novalidate>
            <input type="hidden" name="_csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">

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
                            <?= $isSelected ? 'selected' : '' ?>
                        >
                            <?= $this->e($menu['titre']) ?> - minimum <?= (int) $menu['nombre_personnes_minimum'] ?> pers.
                        </option>
                    <?php endforeach; ?>
                </select>
                <?php if (!empty($errors['id_menu'])): ?><p class="form-error"><?= $this->e($errors['id_menu']) ?></p><?php endif; ?>
            </div>

            <div class="form-grid">
                <div class="form-field">
                    <label for="date_prestation">Date de prestation</label>
                    <input id="date_prestation" name="date_prestation" type="date" value="<?= $this->e($old['date_prestation'] ?? '') ?>" required>
                    <?php if (!empty($errors['date_prestation'])): ?><p class="form-error"><?= $this->e($errors['date_prestation']) ?></p><?php endif; ?>
                </div>
                <div class="form-field">
                    <label for="heure_livraison">Heure de livraison</label>
                    <input id="heure_livraison" name="heure_livraison" type="time" value="<?= $this->e($old['heure_livraison'] ?? '') ?>" required>
                    <?php if (!empty($errors['heure_livraison'])): ?><p class="form-error"><?= $this->e($errors['heure_livraison']) ?></p><?php endif; ?>
                </div>
            </div>

            <div class="form-grid">
                <div class="form-field">
                    <label for="nombre_personnes">Nombre de personnes</label>
                    <input id="nombre_personnes" name="nombre_personnes" type="number" min="1" data-order-people value="<?= $this->e($old['nombre_personnes'] ?? '') ?>" required>
                    <?php if (!empty($errors['nombre_personnes'])): ?><p class="form-error"><?= $this->e($errors['nombre_personnes']) ?></p><?php endif; ?>
                </div>
                <div class="form-field">
                    <label for="distance_km">Distance hors Bordeaux en km</label>
                    <input id="distance_km" name="distance_km" type="number" min="0" step="0.01" data-order-distance value="<?= $this->e($old['distance_km'] ?? '0') ?>">
                </div>
            </div>

            <div class="form-field">
                <label for="adresse_livraison">Adresse de livraison</label>
                <input id="adresse_livraison" name="adresse_livraison" type="text" value="<?= $this->e($old['adresse_livraison'] ?? '') ?>" required>
                <?php if (!empty($errors['adresse_livraison'])): ?><p class="form-error"><?= $this->e($errors['adresse_livraison']) ?></p><?php endif; ?>
            </div>

            <div class="form-field">
                <label for="ville_livraison">Ville</label>
                <input id="ville_livraison" name="ville_livraison" type="text" data-order-city value="<?= $this->e($old['ville_livraison'] ?? 'Bordeaux') ?>" required>
                <?php if (!empty($errors['ville_livraison'])): ?><p class="form-error"><?= $this->e($errors['ville_livraison']) ?></p><?php endif; ?>
            </div>

            <aside class="order-preview" aria-live="polite" data-order-preview>
                Selectionnez un menu et un nombre de personnes pour afficher le prix estime.
            </aside>

            <button class="primary-link" type="submit">Valider la commande</button>
        </form>
    </div>
</section>
