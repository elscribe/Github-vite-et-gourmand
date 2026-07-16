<?php
/**
 * @var list<array<string, mixed>> $menus
 * @var list<array<string, mixed>> $themes
 * @var list<array<string, mixed>> $regimes
 * @var list<array<string, mixed>> $dishes
 * @var array<int, list<int>> $selectedDishIds
 * @var array<string, mixed> $old
 * @var array<string, string> $errors
 */
$newMenuDishIds = $selectedDishIds[0] ?? [];
?>
<section class="page-section">
    <div class="container">
        <a class="back-link" href="/admin">Retour admin</a>

        <div class="section-heading">
            <p class="section-kicker">Administration</p>
            <h1>Gestion des menus</h1>
            <p class="muted-text">Creation, modification, activation et composition des menus.</p>
        </div>

        <form class="auth-form admin-create-form" action="/admin/menus" method="post" novalidate>
            <input type="hidden" name="_csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
            <h2>Nouveau menu</h2>
            <?php if (!empty($errors['taxonomy'])): ?><p class="form-error"><?= $this->e($errors['taxonomy']) ?></p><?php endif; ?>

            <div class="form-grid">
                <div class="form-field">
                    <label for="titre">Titre</label>
                    <input id="titre" name="titre" type="text" value="<?= $this->e($old['titre'] ?? '') ?>" required>
                </div>
                <div class="form-field">
                    <label for="stock_disponible">Stock disponible</label>
                    <input id="stock_disponible" name="stock_disponible" type="number" min="0" value="<?= $this->e($old['stock_disponible'] ?? '0') ?>">
                </div>
            </div>

            <div class="form-grid">
                <div class="form-field">
                    <label for="id_theme">Theme</label>
                    <select id="id_theme" name="id_theme">
                        <?php foreach ($themes as $theme): ?>
                            <option value="<?= (int) $theme['id_theme'] ?>"><?= $this->e($theme['libelle']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-field">
                    <label for="id_regime">Regime</label>
                    <select id="id_regime" name="id_regime">
                        <?php foreach ($regimes as $regime): ?>
                            <option value="<?= (int) $regime['id_regime'] ?>"><?= $this->e($regime['libelle']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-grid">
                <div class="form-field">
                    <label for="nombre_personnes_minimum">Minimum personnes</label>
                    <input id="nombre_personnes_minimum" name="nombre_personnes_minimum" type="number" min="1" value="<?= $this->e($old['nombre_personnes_minimum'] ?? '1') ?>">
                </div>
                <div class="form-field">
                    <label for="prix_minimum">Prix minimum</label>
                    <input id="prix_minimum" name="prix_minimum" type="number" min="0" step="0.01" value="<?= $this->e($old['prix_minimum'] ?? '0') ?>">
                </div>
            </div>

            <div class="form-field">
                <label for="description">Description</label>
                <textarea id="description" name="description" rows="3"><?= $this->e($old['description'] ?? '') ?></textarea>
            </div>

            <div class="form-field">
                <label for="conditions">Conditions</label>
                <textarea id="conditions" name="conditions" rows="3"><?= $this->e($old['conditions'] ?? '') ?></textarea>
            </div>

            <fieldset class="form-field">
                <legend>Plats associes</legend>
                <div class="checkbox-grid">
                    <?php foreach ($dishes as $dish): ?>
                        <?php $dishId = (int) $dish['id_plat']; ?>
                        <label class="checkbox-label">
                            <input
                                type="checkbox"
                                name="dish_ids[]"
                                value="<?= $dishId ?>"
                                <?= in_array($dishId, $newMenuDishIds, true) ? 'checked' : '' ?>
                            >
                            <?= $this->e($dish['titre_plat']) ?>
                            <span><?= $this->e($dish['type_plat']) ?></span>
                        </label>
                    <?php endforeach; ?>
                </div>
            </fieldset>

            <input type="hidden" name="actif" value="1">
            <button class="primary-link" type="submit">Creer le menu</button>
        </form>

        <div class="admin-edit-list">
            <?php foreach ($menus as $menu): ?>
                <?php $menuDishIds = $selectedDishIds[(int) $menu['id_menu']] ?? []; ?>
                <form class="admin-edit-card" action="/admin/menus/<?= (int) $menu['id_menu'] ?>" method="post">
                    <input type="hidden" name="_csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
                    <h2><?= $this->e($menu['titre']) ?></h2>
                    <p class="admin-card-meta">
                        <?= (int) $menu['plats_count'] ?> plats associes -
                        <?= (int) $menu['images_count'] ?> images referencees -
                        <?= ((int) $menu['actif'] === 1) ? 'Actif' : 'Inactif' ?>
                    </p>

                    <div class="form-grid">
                        <div class="form-field">
                            <label for="titre-<?= (int) $menu['id_menu'] ?>">Titre</label>
                            <input id="titre-<?= (int) $menu['id_menu'] ?>" name="titre" type="text" value="<?= $this->e($menu['titre']) ?>">
                        </div>
                        <div class="form-field">
                            <label for="stock-<?= (int) $menu['id_menu'] ?>">Stock</label>
                            <input id="stock-<?= (int) $menu['id_menu'] ?>" name="stock_disponible" type="number" min="0" value="<?= (int) $menu['stock_disponible'] ?>">
                        </div>
                    </div>

                    <div class="form-grid">
                        <div class="form-field">
                            <label for="theme-<?= (int) $menu['id_menu'] ?>">Theme</label>
                            <select id="theme-<?= (int) $menu['id_menu'] ?>" name="id_theme">
                                <?php foreach ($themes as $theme): ?>
                                    <option value="<?= (int) $theme['id_theme'] ?>" <?= (int) $menu['id_theme'] === (int) $theme['id_theme'] ? 'selected' : '' ?>><?= $this->e($theme['libelle']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-field">
                            <label for="regime-<?= (int) $menu['id_menu'] ?>">Regime</label>
                            <select id="regime-<?= (int) $menu['id_menu'] ?>" name="id_regime">
                                <?php foreach ($regimes as $regime): ?>
                                    <option value="<?= (int) $regime['id_regime'] ?>" <?= (int) $menu['id_regime'] === (int) $regime['id_regime'] ? 'selected' : '' ?>><?= $this->e($regime['libelle']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-grid">
                        <div class="form-field">
                            <label for="minimum-<?= (int) $menu['id_menu'] ?>">Minimum</label>
                            <input id="minimum-<?= (int) $menu['id_menu'] ?>" name="nombre_personnes_minimum" type="number" min="1" value="<?= (int) $menu['nombre_personnes_minimum'] ?>">
                        </div>
                        <div class="form-field">
                            <label for="prix-<?= (int) $menu['id_menu'] ?>">Prix minimum</label>
                            <input id="prix-<?= (int) $menu['id_menu'] ?>" name="prix_minimum" type="number" min="0" step="0.01" value="<?= $this->e($menu['prix_minimum']) ?>">
                        </div>
                    </div>

                    <div class="form-field">
                        <label for="description-<?= (int) $menu['id_menu'] ?>">Description</label>
                        <textarea id="description-<?= (int) $menu['id_menu'] ?>" name="description" rows="3"><?= $this->e($menu['description']) ?></textarea>
                    </div>

                    <div class="form-field">
                        <label for="conditions-<?= (int) $menu['id_menu'] ?>">Conditions</label>
                        <textarea id="conditions-<?= (int) $menu['id_menu'] ?>" name="conditions" rows="3"><?= $this->e($menu['conditions']) ?></textarea>
                    </div>

                    <fieldset class="form-field">
                        <legend>Plats associes</legend>
                        <div class="checkbox-grid">
                            <?php foreach ($dishes as $dish): ?>
                                <?php $dishId = (int) $dish['id_plat']; ?>
                                <label class="checkbox-label">
                                    <input
                                        type="checkbox"
                                        name="dish_ids[]"
                                        value="<?= $dishId ?>"
                                        <?= in_array($dishId, $menuDishIds, true) ? 'checked' : '' ?>
                                    >
                                    <?= $this->e($dish['titre_plat']) ?>
                                    <span><?= $this->e($dish['type_plat']) ?></span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </fieldset>

                    <label class="checkbox-label">
                        <input type="checkbox" name="actif" value="1" <?= (int) $menu['actif'] === 1 ? 'checked' : '' ?>>
                        Menu actif
                    </label>

                    <button class="primary-link" type="submit">Enregistrer</button>
                </form>
            <?php endforeach; ?>
        </div>
    </div>
</section>
