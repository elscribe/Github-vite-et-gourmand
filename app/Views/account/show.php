<?php
/**
 * @var array<string, mixed>|null $user
 * @var list<array<string, mixed>> $orders
 * @var array<string, string> $errors
 */
?>
<section class="page-section">
    <div class="container">
        <div class="section-heading">
            <p class="section-kicker">Espace personnel</p>
            <h1>Mon compte</h1>
            <p class="muted-text">Retrouvez vos informations et vos dernieres commandes.</p>
        </div>

        <div class="account-layout">
            <form class="auth-form" action="/mon-compte" method="post" novalidate>
                <input type="hidden" name="_csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">

                <h2>Informations personnelles</h2>
                <p class="muted-text"><?= $this->e($user['email'] ?? '') ?></p>

                <div class="form-grid">
                    <div class="form-field">
                        <label for="prenom">Prenom</label>
                        <input id="prenom" name="prenom" type="text" value="<?= $this->e($user['prenom'] ?? '') ?>" required>
                        <?php if (!empty($errors['prenom'])): ?><p class="form-error"><?= $this->e($errors['prenom']) ?></p><?php endif; ?>
                    </div>
                    <div class="form-field">
                        <label for="nom">Nom</label>
                        <input id="nom" name="nom" type="text" value="<?= $this->e($user['nom'] ?? '') ?>" required>
                        <?php if (!empty($errors['nom'])): ?><p class="form-error"><?= $this->e($errors['nom']) ?></p><?php endif; ?>
                    </div>
                </div>

                <div class="form-field">
                    <label for="telephone">Telephone</label>
                    <input id="telephone" name="telephone" type="tel" value="<?= $this->e($user['telephone'] ?? '') ?>" required>
                    <?php if (!empty($errors['telephone'])): ?><p class="form-error"><?= $this->e($errors['telephone']) ?></p><?php endif; ?>
                </div>

                <div class="form-field">
                    <label for="adresse_postale">Adresse</label>
                    <input id="adresse_postale" name="adresse_postale" type="text" value="<?= $this->e($user['adresse_postale'] ?? '') ?>" required>
                    <?php if (!empty($errors['adresse_postale'])): ?><p class="form-error"><?= $this->e($errors['adresse_postale']) ?></p><?php endif; ?>
                </div>

                <div class="form-grid">
                    <div class="form-field">
                        <label for="ville">Ville</label>
                        <input id="ville" name="ville" type="text" value="<?= $this->e($user['ville'] ?? '') ?>" required>
                        <?php if (!empty($errors['ville'])): ?><p class="form-error"><?= $this->e($errors['ville']) ?></p><?php endif; ?>
                    </div>
                    <div class="form-field">
                        <label for="pays">Pays</label>
                        <input id="pays" name="pays" type="text" value="<?= $this->e($user['pays'] ?? 'France') ?>" required>
                        <?php if (!empty($errors['pays'])): ?><p class="form-error"><?= $this->e($errors['pays']) ?></p><?php endif; ?>
                    </div>
                </div>

                <button class="primary-link" type="submit">Mettre a jour</button>
            </form>

            <aside class="page-panel account-orders">
                <h2>Dernieres commandes</h2>
                <?php if ($orders === []): ?>
                    <p class="muted-text">Aucune commande pour le moment.</p>
                    <a class="primary-link" href="/menus">Voir les menus</a>
                <?php else: ?>
                    <div class="compact-list">
                        <?php foreach ($orders as $order): ?>
                            <a href="/commandes/<?= (int) $order['id_commande'] ?>">
                                <strong>#<?= (int) $order['id_commande'] ?> - <?= $this->e($order['menu_titre']) ?></strong>
                                <span><?= $this->e($order['statut_actuel']) ?> - <?= number_format((float) $order['prix_total'], 2, ',', ' ') ?> EUR</span>
                            </a>
                        <?php endforeach; ?>
                    </div>
                    <a class="secondary-link" href="/commandes">Voir toutes mes commandes</a>
                <?php endif; ?>
            </aside>
        </div>
    </div>
</section>
