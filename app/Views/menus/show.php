<?php

use App\Services\MenuPresentation;

/**
 * @var array<string, mixed> $menu
 * @var list<array<string, mixed>> $images
 * @var list<array<string, mixed>> $dishes
 * @var list<array<string, mixed>> $allergens
 */

$presentation = MenuPresentation::forMenu($menu);
$detailImages = $presentation['detail_images'] ?? [];

if (!is_array($detailImages) || $detailImages === []) {
    $detailImages = [[
        'src' => $presentation['image'] ?? '/images/home/menu-noel-tradition.png',
        'alt' => $presentation['image_alt'] ?? $presentation['title'],
    ]];
}

$mainImage = $detailImages[0];
$galleryImages = array_slice($detailImages, 1);
$importantConditions = $presentation['important_conditions'] ?? [];
$detailSections = $presentation['detail_sections'] ?? [];
?>
<section class="menu-detail-page">
    <div class="container menu-detail-container">
        <nav class="menu-breadcrumb" aria-label="Fil d'Ariane">
            <a href="/">Accueil</a>
            <span aria-hidden="true">&gt;</span>
            <a href="/menus">Nos menus</a>
            <span aria-hidden="true">&gt;</span>
            <strong><?= $this->e($presentation['title']) ?></strong>
        </nav>

        <h1 class="menu-detail-title"><?= $this->e($presentation['title']) ?></h1>

        <div class="menu-detail-layout">
            <div class="menu-detail-left">
                <img
                    class="menu-detail-hero-image"
                    src="<?= $this->e($mainImage['src']) ?>"
                    alt="<?= $this->e($mainImage['alt']) ?>"
                >

                <?php if ($galleryImages !== []): ?>
                    <section class="menu-detail-gallery" aria-labelledby="menu-gallery-title">
                        <h2 id="menu-gallery-title">Galerie des plats</h2>
                        <div class="menu-detail-thumbs">
                            <?php foreach ($galleryImages as $image): ?>
                                <img src="<?= $this->e($image['src']) ?>" alt="<?= $this->e($image['alt']) ?>">
                            <?php endforeach; ?>
                        </div>
                    </section>
                <?php endif; ?>

                <section class="menu-detail-description" aria-labelledby="menu-description-title">
                    <h2 id="menu-description-title">Description du menu</h2>
                    <p><?= $this->e($presentation['detail_description'] ?? $presentation['description']) ?></p>
                </section>

                <section class="menu-detail-composition" aria-labelledby="menu-composition-title">
                    <h2 id="menu-composition-title">Composition de la prestation</h2>

                    <?php if (is_array($detailSections) && $detailSections !== []): ?>
                        <?php foreach ($detailSections as $section): ?>
                            <div class="menu-composition-group">
                                <h3><?= $this->e($section['title']) ?></h3>
                                <div class="menu-composition-table">
                                    <?php foreach ($section['items'] as $item): ?>
                                        <div class="menu-composition-row">
                                            <span><?= $this->e($item['name']) ?></span>
                                            <?php if (($item['tags'] ?? []) !== []): ?>
                                                <span class="menu-composition-tags">
                                                    <?php foreach ($item['tags'] as $tag): ?>
                                                        <em><?= $this->e($tag) ?></em>
                                                    <?php endforeach; ?>
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php elseif ($dishes !== []): ?>
                        <div class="menu-composition-table">
                            <?php foreach ($dishes as $dish): ?>
                                <div class="menu-composition-row">
                                    <span><?= $this->e($dish['titre_plat']) ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </section>
            </div>

            <aside class="menu-detail-sidebar" aria-label="Informations principales du menu">
                <dl class="menu-detail-facts">
                    <div>
                        <dt>Thème</dt>
                        <dd><?= $this->e($presentation['detail_theme'] ?? $presentation['theme_label']) ?></dd>
                    </div>
                    <div>
                        <dt>Régime</dt>
                        <dd><?= $this->e($presentation['regime_label']) ?></dd>
                    </div>
                    <div>
                        <dt>Minimum</dt>
                        <dd><?= (int) $presentation['people'] ?> pers.</dd>
                    </div>
                    <div>
                        <dt>Prix</dt>
                        <dd><?= number_format((float) $presentation['price'], 0, ',', ' ') ?> €</dd>
                    </div>
                    <div class="menu-detail-stock">
                        <dt>Disponibilité / stock</dt>
                        <dd><?= $this->e($presentation['detail_status'] ?? $presentation['status']) ?></dd>
                    </div>
                </dl>

                <?php if (is_array($importantConditions) && $importantConditions !== []): ?>
                    <section class="menu-detail-warning" aria-labelledby="menu-conditions-title">
                        <h2 id="menu-conditions-title">
                            <i class="bi bi-exclamation-circle" aria-hidden="true"></i>
                            Conditions importantes
                        </h2>
                        <ul>
                            <?php foreach ($importantConditions as $condition): ?>
                                <li><?= $this->e($condition) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </section>
                <?php endif; ?>

                <div class="menu-detail-actions">
                    <a class="menu-detail-order-button" href="/commandes/creation/<?= (int) $menu['id_menu'] ?>">
                        Commander ce menu
                    </a>
                    <p>Si vous n'êtes pas connecté, vous devrez vous connecter ou créer un compte avant de commander.</p>
                    <a class="menu-detail-outline-button" href="/contact">Contacter le traiteur</a>
                    <a class="menu-detail-outline-button" href="/menus">&larr; Retour aux menus</a>
                </div>
            </aside>
        </div>
    </div>
</section>
