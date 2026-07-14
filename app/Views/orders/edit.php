<?php
/**
 * @var array<string, mixed> $order
 * @var array<string, mixed> $old
 * @var array<string, string> $errors
 */
?>
<section class="page-section">
    <div class="container">
        <a class="back-link" href="/commandes/<?= (int) $order['id_commande'] ?>">Retour a la commande</a>

        <div class="section-heading">
            <p class="section-kicker">Commande #<?= (int) $order['id_commande'] ?></p>
            <h1>Modifier la commande</h1>
            <p class="muted-text">La modification est possible uniquement tant que la commande reste en attente.</p>
        </div>

        <?php if (!empty($errors['statut'])): ?>
            <p class="alert-message error-message"><?= $this->e($errors['statut']) ?></p>
        <?php endif; ?>

        <form class="order-form" action="/commandes/<?= (int) $order['id_commande'] ?>/modifier" method="post" novalidate>
            <input type="hidden" name="_csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">

            <div class="page-panel order-current-menu">
                <strong><?= $this->e($order['menu_titre']) ?></strong>
                <span>Minimum <?= (int) $order['nombre_personnes_minimum'] ?> personnes</span>
            </div>

            <div class="form-grid">
                <div class="form-field">
                    <label for="date_prestation">Date de prestation</label>
                    <input id="date_prestation" name="date_prestation" type="date" value="<?= $this->e($old['date_prestation'] ?? '') ?>" required>
                    <?php if (!empty($errors['date_prestation'])): ?><p class="form-error"><?= $this->e($errors['date_prestation']) ?></p><?php endif; ?>
                </div>
                <div class="form-field">
                    <label for="heure_livraison">Heure de livraison</label>
                    <input id="heure_livraison" name="heure_livraison" type="time" value="<?= $this->e(substr((string) ($old['heure_livraison'] ?? ''), 0, 5)) ?>" required>
                    <?php if (!empty($errors['heure_livraison'])): ?><p class="form-error"><?= $this->e($errors['heure_livraison']) ?></p><?php endif; ?>
                </div>
            </div>

            <div class="form-grid">
                <div class="form-field">
                    <label for="nombre_personnes">Nombre de personnes</label>
                    <input id="nombre_personnes" name="nombre_personnes" type="number" min="<?= (int) $order['nombre_personnes_minimum'] ?>" value="<?= $this->e($old['nombre_personnes'] ?? '') ?>" required>
                    <?php if (!empty($errors['nombre_personnes'])): ?><p class="form-error"><?= $this->e($errors['nombre_personnes']) ?></p><?php endif; ?>
                </div>
                <div class="form-field">
                    <label for="distance_km">Distance hors Bordeaux en km</label>
                    <input id="distance_km" name="distance_km" type="number" min="0" step="0.01" value="<?= $this->e($old['distance_km'] ?? '0') ?>">
                </div>
            </div>

            <div class="form-field">
                <label for="adresse_livraison">Adresse de livraison</label>
                <input id="adresse_livraison" name="adresse_livraison" type="text" value="<?= $this->e($old['adresse_livraison'] ?? '') ?>" required>
                <?php if (!empty($errors['adresse_livraison'])): ?><p class="form-error"><?= $this->e($errors['adresse_livraison']) ?></p><?php endif; ?>
            </div>

            <div class="form-field">
                <label for="ville_livraison">Ville</label>
                <input id="ville_livraison" name="ville_livraison" type="text" value="<?= $this->e($old['ville_livraison'] ?? '') ?>" required>
                <?php if (!empty($errors['ville_livraison'])): ?><p class="form-error"><?= $this->e($errors['ville_livraison']) ?></p><?php endif; ?>
            </div>

            <button class="primary-link" type="submit">Enregistrer les modifications</button>
        </form>
    </div>
</section>
