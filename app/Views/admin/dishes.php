<?php
/**
 * @var list<array<string, mixed>> $dishes
 * @var array<string, mixed> $old
 * @var array<string, string> $errors
 */
$types = ['entree' => 'Entree', 'plat' => 'Plat', 'dessert' => 'Dessert'];
?>
<section class="page-section">
    <div class="container">
        <a class="back-link" href="/admin">Retour admin</a>

        <div class="section-heading">
            <p class="section-kicker">Administration</p>
            <h1>Gestion des plats</h1>
            <p class="muted-text">Les plats sont reutilisables dans plusieurs menus.</p>
        </div>

        <form class="auth-form admin-create-form" action="/admin/plats" method="post" novalidate>
            <input type="hidden" name="_csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
            <h2>Nouveau plat</h2>

            <div class="form-grid">
                <div class="form-field">
                    <label for="titre_plat">Titre</label>
                    <input id="titre_plat" name="titre_plat" type="text" value="<?= $this->e($old['titre_plat'] ?? '') ?>">
                    <?php if (!empty($errors['titre_plat'])): ?><p class="form-error"><?= $this->e($errors['titre_plat']) ?></p><?php endif; ?>
                </div>
                <div class="form-field">
                    <label for="type_plat">Type</label>
                    <select id="type_plat" name="type_plat">
                        <?php foreach ($types as $value => $label): ?>
                            <option value="<?= $this->e($value) ?>" <?= ($old['type_plat'] ?? '') === $value ? 'selected' : '' ?>><?= $this->e($label) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-field">
                <label for="description">Description</label>
                <textarea id="description" name="description" rows="3"><?= $this->e($old['description'] ?? '') ?></textarea>
            </div>

            <button class="primary-link" type="submit">Creer le plat</button>
        </form>

        <div class="admin-edit-list">
            <?php foreach ($dishes as $dish): ?>
                <form class="admin-edit-card" action="/admin/plats/<?= (int) $dish['id_plat'] ?>" method="post">
                    <input type="hidden" name="_csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
                    <div class="form-grid">
                        <div class="form-field">
                            <label for="titre-<?= (int) $dish['id_plat'] ?>">Titre</label>
                            <input id="titre-<?= (int) $dish['id_plat'] ?>" name="titre_plat" type="text" value="<?= $this->e($dish['titre_plat']) ?>">
                        </div>
                        <div class="form-field">
                            <label for="type-<?= (int) $dish['id_plat'] ?>">Type</label>
                            <select id="type-<?= (int) $dish['id_plat'] ?>" name="type_plat">
                                <?php foreach ($types as $value => $label): ?>
                                    <option value="<?= $this->e($value) ?>" <?= $dish['type_plat'] === $value ? 'selected' : '' ?>><?= $this->e($label) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-field">
                        <label for="description-<?= (int) $dish['id_plat'] ?>">Description</label>
                        <textarea id="description-<?= (int) $dish['id_plat'] ?>" name="description" rows="3"><?= $this->e($dish['description']) ?></textarea>
                    </div>
                    <button class="primary-link" type="submit">Enregistrer</button>
                </form>
            <?php endforeach; ?>
        </div>
    </div>
</section>
