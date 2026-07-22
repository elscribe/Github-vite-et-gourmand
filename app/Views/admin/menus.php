<?php
/**
 * @var list<array<string, mixed>> $menus
 * @var list<array<string, mixed>> $themes
 * @var list<array<string, mixed>> $regimes
 * @var list<array<string, mixed>> $dishes
 * @var list<array<string, mixed>> $allergens
 * @var array<int, list<int>> $selectedDishIds
 * @var array<int, list<int>> $selectedAllergenIds
 * @var array<string, mixed>|null $selectedMenu
 * @var list<array<string, mixed>> $selectedMenuDishes
 * @var array<string, mixed>|null $selectedDish
 * @var array<string, mixed> $old
 * @var array<string, string> $errors
 * @var array<string, mixed> $dishOld
 * @var array<string, string> $dishErrors
 */
$typeLabels = ['entree' => 'Entrée', 'plat' => 'Plat', 'dessert' => 'Dessert'];
$selectedMenuId = $selectedMenu === null ? 0 : (int) $selectedMenu['id_menu'];
$selectedDishId = $selectedDish === null ? 0 : (int) $selectedDish['id_plat'];
$currentMenuDishIds = $selectedDishIds[$selectedMenuId] ?? [];
$currentDishAllergenIds = $selectedAllergenIds[$selectedDishId] ?? [];
$newMenuThemeId = (int) ($old['id_theme'] ?? ($themes[0]['id_theme'] ?? 0));
$newMenuRegimeId = (int) ($old['id_regime'] ?? ($regimes[0]['id_regime'] ?? 0));
$newDishAllergenIds = $selectedAllergenIds[0] ?? [];
$groupedMenuDishes = ['entree' => [], 'plat' => [], 'dessert' => []];

foreach ($selectedMenuDishes as $dish) {
    $type = (string) ($dish['type_plat'] ?? '');
    $groupedMenuDishes[$type][] = $dish;
}

$formatPrice = static fn (mixed $price): string => number_format((float) $price, 2, ',', ' ') . ' EUR';
?>
<section class="admin-menu-workspace" aria-labelledby="admin-menu-title">
    <header class="admin-menu-title-row">
        <div>
            <p class="section-kicker">Administration</p>
            <h1 id="admin-menu-title">Menus, plats &amp; composition</h1>
            <p>Page desktop pour choisir les menus visibles, composer le menu sélectionné et entretenir la base des plats.</p>
        </div>
        <a class="admin-menu-quiet-link" href="/menus" target="_blank" rel="noreferrer">Voir le catalogue public</a>
    </header>

    <section class="admin-menu-panel admin-menu-current-panel" aria-labelledby="admin-menu-current-title">
        <div class="admin-menu-panel-heading">
            <div class="admin-menu-heading-copy">
                <i class="bi bi-calendar-check" aria-hidden="true"></i>
                <div>
                    <h2 id="admin-menu-current-title">Menus du moment</h2>
                    <p>Cochez les menus visibles sur la page publique. Cliquez sur une carte pour modifier sa composition en bas.</p>
                </div>
            </div>
            <div class="admin-menu-heading-actions">
                <button class="admin-menu-button" type="submit" form="admin-public-menu-selection">
                    <i class="bi bi-check2-square" aria-hidden="true"></i> Enregistrer la sélection
                </button>
                <details class="admin-menu-create-drawer" <?= $errors !== [] ? 'open' : '' ?>>
                    <summary><i class="bi bi-plus-circle" aria-hidden="true"></i> Nouveau menu</summary>
                    <form class="admin-menu-create-form" action="/admin/menus" method="post" novalidate>
                        <input type="hidden" name="_csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
                        <?php if (!empty($errors['taxonomy'])): ?><p class="form-error"><?= $this->e($errors['taxonomy']) ?></p><?php endif; ?>
                        <div class="admin-menu-form-grid">
                            <div class="form-field">
                                <label for="create-menu-title">Titre</label>
                                <input id="create-menu-title" name="titre" type="text" value="<?= $this->e($old['titre'] ?? '') ?>" required>
                                <?php if (!empty($errors['titre'])): ?><p class="form-error"><?= $this->e($errors['titre']) ?></p><?php endif; ?>
                            </div>
                            <div class="form-field">
                                <label for="create-menu-price">Prix minimum</label>
                                <input id="create-menu-price" name="prix_minimum" type="number" min="0" step="0.01" value="<?= $this->e($old['prix_minimum'] ?? '0') ?>">
                            </div>
                            <div class="form-field">
                                <label for="create-menu-theme">Thème</label>
                                <select id="create-menu-theme" name="id_theme">
                                    <?php foreach ($themes as $theme): ?>
                                        <option value="<?= (int) $theme['id_theme'] ?>" <?= $newMenuThemeId === (int) $theme['id_theme'] ? 'selected' : '' ?>><?= $this->e($theme['libelle']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-field">
                                <label for="create-menu-regime">Régime</label>
                                <select id="create-menu-regime" name="id_regime">
                                    <?php foreach ($regimes as $regime): ?>
                                        <option value="<?= (int) $regime['id_regime'] ?>" <?= $newMenuRegimeId === (int) $regime['id_regime'] ? 'selected' : '' ?>><?= $this->e($regime['libelle']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-field">
                                <label for="create-menu-stock">Stock disponible</label>
                                <input id="create-menu-stock" name="stock_disponible" type="number" min="0" value="<?= $this->e($old['stock_disponible'] ?? '0') ?>">
                            </div>
                            <div class="form-field">
                                <label for="create-menu-minimum">Minimum personnes</label>
                                <input id="create-menu-minimum" name="nombre_personnes_minimum" type="number" min="1" value="<?= $this->e($old['nombre_personnes_minimum'] ?? '1') ?>">
                            </div>
                        </div>
                        <div class="form-field">
                            <label for="create-menu-description">Description</label>
                            <textarea id="create-menu-description" name="description" rows="2"><?= $this->e($old['description'] ?? '') ?></textarea>
                        </div>
                        <div class="form-field">
                            <label for="create-menu-conditions">Conditions</label>
                            <textarea id="create-menu-conditions" name="conditions" rows="2"><?= $this->e($old['conditions'] ?? '') ?></textarea>
                        </div>
                        <input type="hidden" name="actif" value="0">
                        <button class="admin-menu-button admin-menu-button-primary" type="submit">Créer le menu</button>
                    </form>
                </details>
            </div>
        </div>

        <label class="admin-menu-search">
            <i class="bi bi-search" aria-hidden="true"></i>
            <span class="visually-hidden">Rechercher un menu</span>
            <input type="search" placeholder="Rechercher un menu..." data-admin-menu-search>
        </label>

        <form id="admin-public-menu-selection" class="admin-menu-selection-form" action="/admin/menus/selection" method="post">
            <input type="hidden" name="_csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
            <input type="hidden" name="selected_menu_id" value="<?= $selectedMenuId ?>">
            <input type="hidden" name="selected_dish_id" value="<?= $selectedDishId ?>">
            <div class="admin-menu-card-grid">
            <?php foreach ($menus as $menu): ?>
                <?php
                $menuId = (int) $menu['id_menu'];
                $isSelected = $menuId === $selectedMenuId;
                $isActive = (int) $menu['actif'] === 1;
                ?>
                <article
                    class="admin-menu-card<?= $isSelected ? ' is-selected' : '' ?>"
                    data-admin-menu-item
                    data-admin-menu-url="/admin/menus?menu=<?= $menuId ?><?= $selectedDishId > 0 ? '&amp;dish=' . $selectedDishId : '' ?>"
                    data-search-text="<?= $this->e(strtolower((string) $menu['titre'] . ' ' . $menu['theme'] . ' ' . $menu['regime'])) ?>"
                >
                    <span class="admin-menu-card-head">
                        <label class="admin-menu-public-check">
                            <input
                                type="checkbox"
                                name="public_menu_ids[]"
                                value="<?= $menuId ?>"
                                <?= $isActive ? 'checked' : '' ?>
                                aria-label="Afficher <?= $this->e($menu['titre']) ?> sur la page publique"
                            >
                        </label>
                        <a class="admin-menu-card-title" href="/admin/menus?menu=<?= $menuId ?><?= $selectedDishId > 0 ? '&amp;dish=' . $selectedDishId : '' ?>">
                            <?= $this->e($menu['titre']) ?>
                        </a>
                    </span>
                    <span class="admin-menu-card-facts">
                        <span>Prix<strong><?= $formatPrice($menu['prix_minimum']) ?></strong></span>
                        <span>Stock<strong><?= (int) $menu['stock_disponible'] ?></strong></span>
                        <span>Plats<strong><?= (int) $menu['plats_count'] ?></strong></span>
                    </span>
                </article>
            <?php endforeach; ?>
            </div>
        </form>
    </section>

    <div class="admin-menu-lower-grid">
        <section class="admin-menu-panel admin-menu-square-panel" aria-labelledby="admin-menu-composition-title">
            <div class="admin-menu-panel-heading">
                <div class="admin-menu-heading-copy">
                    <i class="bi bi-ui-checks-grid" aria-hidden="true"></i>
                    <div>
                        <h2 id="admin-menu-composition-title">Composition du menu sélectionné</h2>
                        <p>Modifier les informations du menu sans perdre les plats associés.</p>
                    </div>
                </div>
                <?php if ($selectedMenu !== null): ?>
                    <span class="admin-menu-chip"><?= $this->e($selectedMenu['titre']) ?></span>
                <?php endif; ?>
            </div>

            <?php if ($selectedMenu === null): ?>
                <p class="muted-text">Créez un menu pour commencer.</p>
            <?php else: ?>
                <form class="admin-menu-edit-form" action="/admin/menus/<?= $selectedMenuId ?>" method="post">
                    <input type="hidden" name="_csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
                    <?php foreach ($currentMenuDishIds as $dishId): ?>
                        <input type="hidden" name="dish_ids[]" value="<?= (int) $dishId ?>">
                    <?php endforeach; ?>
                    <div class="admin-menu-form-grid">
                        <div class="form-field">
                            <label for="menu-title-<?= $selectedMenuId ?>">Titre</label>
                            <input id="menu-title-<?= $selectedMenuId ?>" name="titre" type="text" value="<?= $this->e($selectedMenu['titre']) ?>">
                        </div>
                        <div class="form-field">
                            <label for="menu-price-<?= $selectedMenuId ?>">Prix minimum</label>
                            <input id="menu-price-<?= $selectedMenuId ?>" name="prix_minimum" type="number" min="0" step="0.01" value="<?= $this->e($selectedMenu['prix_minimum']) ?>">
                        </div>
                        <div class="form-field">
                            <label for="menu-theme-<?= $selectedMenuId ?>">Thème</label>
                            <select id="menu-theme-<?= $selectedMenuId ?>" name="id_theme">
                                <?php foreach ($themes as $theme): ?>
                                    <option value="<?= (int) $theme['id_theme'] ?>" <?= (int) $selectedMenu['id_theme'] === (int) $theme['id_theme'] ? 'selected' : '' ?>><?= $this->e($theme['libelle']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-field">
                            <label for="menu-regime-<?= $selectedMenuId ?>">Régime</label>
                            <select id="menu-regime-<?= $selectedMenuId ?>" name="id_regime">
                                <?php foreach ($regimes as $regime): ?>
                                    <option value="<?= (int) $regime['id_regime'] ?>" <?= (int) $selectedMenu['id_regime'] === (int) $regime['id_regime'] ? 'selected' : '' ?>><?= $this->e($regime['libelle']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-field">
                            <label for="menu-stock-<?= $selectedMenuId ?>">Stock disponible</label>
                            <input id="menu-stock-<?= $selectedMenuId ?>" name="stock_disponible" type="number" min="0" value="<?= (int) $selectedMenu['stock_disponible'] ?>">
                        </div>
                        <div class="form-field">
                            <label for="menu-minimum-<?= $selectedMenuId ?>">Minimum personnes</label>
                            <input id="menu-minimum-<?= $selectedMenuId ?>" name="nombre_personnes_minimum" type="number" min="1" value="<?= (int) $selectedMenu['nombre_personnes_minimum'] ?>">
                        </div>
                    </div>
                    <div class="form-field">
                        <label for="menu-description-<?= $selectedMenuId ?>">Description</label>
                        <textarea id="menu-description-<?= $selectedMenuId ?>" name="description" rows="3"><?= $this->e($selectedMenu['description']) ?></textarea>
                    </div>
                    <div class="form-field">
                        <label for="menu-conditions-<?= $selectedMenuId ?>">Conditions</label>
                        <textarea id="menu-conditions-<?= $selectedMenuId ?>" name="conditions" rows="2"><?= $this->e($selectedMenu['conditions']) ?></textarea>
                    </div>
                    <label class="admin-menu-switch">
                        <input type="checkbox" name="actif" value="1" <?= (int) $selectedMenu['actif'] === 1 ? 'checked' : '' ?>>
                        <span>Menu actif sur le catalogue</span>
                    </label>
                    <button class="admin-menu-button admin-menu-button-primary" type="submit"><i class="bi bi-save" aria-hidden="true"></i> Enregistrer le menu</button>
                </form>

                <div class="admin-menu-composition">
                    <?php foreach ($typeLabels as $type => $label): ?>
                        <div class="admin-menu-composition-group">
                            <h3><?= $this->e($label) ?>s (<?= count($groupedMenuDishes[$type] ?? []) ?>)</h3>
                            <?php if (($groupedMenuDishes[$type] ?? []) === []): ?>
                                <p class="admin-menu-empty">Aucun plat associé.</p>
                            <?php endif; ?>
                            <?php foreach ($groupedMenuDishes[$type] ?? [] as $dish): ?>
                                <article class="admin-menu-composition-row">
                                    <span>
                                        <strong><?= $this->e($dish['titre_plat']) ?></strong>
                                        <small><?= $this->e($dish['description'] ?? '') ?></small>
                                    </span>
                                    <form action="/admin/menus/<?= $selectedMenuId ?>/plats/<?= (int) $dish['id_plat'] ?>/retirer" method="post">
                                        <input type="hidden" name="_csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
                                        <button class="admin-menu-button" type="submit">Retirer</button>
                                    </form>
                                </article>
                            <?php endforeach; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>

        <section class="admin-menu-panel admin-menu-square-panel" aria-labelledby="admin-dish-management-title">
            <div class="admin-menu-panel-heading">
                <div class="admin-menu-heading-copy">
                    <i class="bi bi-clipboard2-plus" aria-hidden="true"></i>
                    <div>
                        <h2 id="admin-dish-management-title">Création et édition des plats</h2>
                        <p>Un plat correspond ici à la recette réutilisable dans plusieurs menus.</p>
                    </div>
                </div>
                <details class="admin-dish-create-drawer" <?= $dishErrors !== [] ? 'open' : '' ?>>
                    <summary>Créer un plat</summary>
                    <form class="admin-dish-create-form" action="/admin/plats" method="post" novalidate>
                        <input type="hidden" name="_csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
                        <input type="hidden" name="selected_menu_id" value="<?= $selectedMenuId ?>">
                        <div class="admin-menu-form-grid">
                            <div class="form-field">
                                <label for="create-dish-title">Titre</label>
                                <input id="create-dish-title" name="titre_plat" type="text" value="<?= $this->e($dishOld['titre_plat'] ?? '') ?>">
                                <?php if (!empty($dishErrors['titre_plat'])): ?><p class="form-error"><?= $this->e($dishErrors['titre_plat']) ?></p><?php endif; ?>
                            </div>
                            <div class="form-field">
                                <label for="create-dish-type">Type</label>
                                <select id="create-dish-type" name="type_plat">
                                    <?php foreach ($typeLabels as $value => $label): ?>
                                        <option value="<?= $this->e($value) ?>" <?= ($dishOld['type_plat'] ?? '') === $value ? 'selected' : '' ?>><?= $this->e($label) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-field">
                            <label for="create-dish-description">Description recette</label>
                            <textarea id="create-dish-description" name="description" rows="2"><?= $this->e($dishOld['description'] ?? '') ?></textarea>
                        </div>
                        <fieldset class="form-field">
                            <legend>Allergènes</legend>
                            <div class="admin-allergen-grid">
                                <?php foreach ($allergens as $allergen): ?>
                                    <?php $allergenId = (int) $allergen['id_allergene']; ?>
                                    <label>
                                        <input type="checkbox" name="allergen_ids[]" value="<?= $allergenId ?>" <?= in_array($allergenId, $newDishAllergenIds, true) ? 'checked' : '' ?>>
                                        <?= $this->e($allergen['libelle']) ?>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </fieldset>
                        <button class="admin-menu-button admin-menu-button-primary" type="submit">Créer le plat</button>
                    </form>
                </details>
            </div>

            <label class="admin-menu-search">
                <i class="bi bi-search" aria-hidden="true"></i>
                <span class="visually-hidden">Rechercher un plat</span>
                <input type="search" placeholder="Rechercher un plat..." data-admin-dish-search>
            </label>

            <div class="admin-dish-filter-row" aria-label="Types de plats">
                <button class="is-active" type="button" data-admin-dish-type-filter="all" aria-pressed="true">Tous</button>
                <?php foreach ($typeLabels as $type => $label): ?>
                    <button type="button" data-admin-dish-type-filter="<?= $this->e($type) ?>" aria-pressed="false"><?= $this->e($label) ?></button>
                <?php endforeach; ?>
            </div>

            <div class="admin-dish-list">
                <?php foreach ($dishes as $dish): ?>
                    <?php
                    $dishId = (int) $dish['id_plat'];
                    $isInSelectedMenu = in_array($dishId, $currentMenuDishIds, true);
                    ?>
                    <article
                        class="admin-dish-row<?= $dishId === $selectedDishId ? ' is-selected' : '' ?>"
                        data-admin-dish-item
                        data-dish-type="<?= $this->e($dish['type_plat']) ?>"
                        data-search-text="<?= $this->e(strtolower((string) $dish['titre_plat'] . ' ' . $dish['type_plat'] . ' ' . $dish['description'])) ?>"
                    >
                        <span class="admin-dish-type"><?= $this->e($typeLabels[(string) $dish['type_plat']] ?? (string) $dish['type_plat']) ?></span>
                        <span class="admin-dish-copy">
                            <strong><?= $this->e($dish['titre_plat']) ?></strong>
                            <small><?= $this->e($dish['description'] ?? '') ?></small>
                        </span>
                        <span class="admin-dish-actions">
                            <a class="admin-menu-button" href="/admin/menus?menu=<?= $selectedMenuId ?>&amp;dish=<?= $dishId ?>">Modifier</a>
                            <form action="/admin/menus/<?= $selectedMenuId ?>/plats" method="post">
                                <input type="hidden" name="_csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
                                <input type="hidden" name="id_plat" value="<?= $dishId ?>">
                                <button class="admin-menu-button admin-menu-button-primary" type="submit" <?= $selectedMenuId <= 0 || $isInSelectedMenu ? 'disabled' : '' ?>><?= $isInSelectedMenu ? 'Dans ce menu' : 'Ajouter' ?></button>
                            </form>
                        </span>
                    </article>
                <?php endforeach; ?>
            </div>

            <?php if ($selectedDish !== null): ?>
                <form class="admin-dish-edit-form" action="/admin/plats/<?= $selectedDishId ?>" method="post">
                    <input type="hidden" name="_csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
                    <input type="hidden" name="selected_menu_id" value="<?= $selectedMenuId ?>">
                    <input type="hidden" name="selected_dish_id" value="<?= $selectedDishId ?>">
                    <h3>Éditer le plat sélectionné</h3>
                    <div class="admin-menu-form-grid">
                        <div class="form-field">
                            <label for="dish-title-<?= $selectedDishId ?>">Titre</label>
                            <input id="dish-title-<?= $selectedDishId ?>" name="titre_plat" type="text" value="<?= $this->e($selectedDish['titre_plat']) ?>">
                        </div>
                        <div class="form-field">
                            <label for="dish-type-<?= $selectedDishId ?>">Type</label>
                            <select id="dish-type-<?= $selectedDishId ?>" name="type_plat">
                                <?php foreach ($typeLabels as $value => $label): ?>
                                    <option value="<?= $this->e($value) ?>" <?= $selectedDish['type_plat'] === $value ? 'selected' : '' ?>><?= $this->e($label) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-field">
                        <label for="dish-description-<?= $selectedDishId ?>">Description recette</label>
                        <textarea id="dish-description-<?= $selectedDishId ?>" name="description" rows="3"><?= $this->e($selectedDish['description'] ?? '') ?></textarea>
                    </div>
                    <fieldset class="form-field">
                        <legend>Allergènes</legend>
                        <div class="admin-allergen-grid">
                            <?php foreach ($allergens as $allergen): ?>
                                <?php $allergenId = (int) $allergen['id_allergene']; ?>
                                <label>
                                    <input type="checkbox" name="allergen_ids[]" value="<?= $allergenId ?>" <?= in_array($allergenId, $currentDishAllergenIds, true) ? 'checked' : '' ?>>
                                    <?= $this->e($allergen['libelle']) ?>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </fieldset>
                    <button class="admin-menu-button admin-menu-button-primary" type="submit"><i class="bi bi-save" aria-hidden="true"></i> Enregistrer le plat</button>
                </form>
            <?php endif; ?>
        </section>
    </div>
</section>
