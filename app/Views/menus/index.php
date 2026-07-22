<?php

/**
 * @var list<array<string, mixed>> $menus
 * @var list<array<string, mixed>> $themes
 * @var list<array<string, mixed>> $regimes
 */

$menuPresentation = [
    1 => [
        'order' => 1,
        'title' => 'Menu Noël Tradition',
        'description' => "Foie gras maison, volaille festive, légumes rôtis et dessert gourmand pour vos repas de fin d'année.",
        'image' => '/images/home/menu-noel-tradition.png',
        'image_alt' => 'Table de fête avec volaille et chandeliers',
        'badge' => 'NOËL',
        'people' => 8,
        'price' => 190,
        'regime_label' => 'Classique',
        'status' => 'Stock limité : 5 commandes restantes',
        'status_type' => 'limited',
        'available' => false,
        'party' => true,
        'allergens' => ['gluten', 'oeufs', 'lactose', 'fruits-a-coque'],
    ],
    6 => [
        'order' => 2,
        'title' => 'Menu Cocktail Bordelais',
        'description' => 'Bouchées salées, verrines, mini tartines et mignardises pour vos réceptions privées ou professionnelles.',
        'image' => '/images/home/menu-cocktail-bordelais.png',
        'image_alt' => 'Buffet de verrines et bouchées apéritives',
        'badge' => 'BUFFET',
        'people' => 10,
        'price' => 220,
        'regime_label' => 'Classique',
        'status' => 'Disponible cette semaine',
        'status_type' => 'available',
        'available' => true,
        'party' => false,
        'allergens' => ['gluten', 'lactose', 'oeufs', 'fruits-a-coque'],
    ],
    4 => [
        'order' => 3,
        'title' => 'Menu Végé-Gourmand',
        'description' => 'Tartes, légumes rôtis, quiches et desserts végétariens pour un repas complet et savoureux.',
        'image' => '/images/home/menu-vege-gourmand.png',
        'image_alt' => 'Plat végétarien généreux servi sur une table claire',
        'badge' => '100 % VÉGÉTAL',
        'people' => 6,
        'price' => 120,
        'regime_label' => 'Végétarien',
        'status' => 'Disponible',
        'status_type' => 'available',
        'available' => true,
        'party' => false,
        'allergens' => ['gluten', 'lactose', 'oeufs', 'fruits-a-coque'],
    ],
    3 => [
        'order' => 4,
        'title' => 'Menu Terre & Mer',
        'description' => 'Produits marins et saveurs traditionnelles pour une prestation complète et raffinée.',
        'image' => '/images/home/menu-terre-mer.png',
        'image_alt' => 'Assiette raffinée de poisson avec légumes colorés',
        'badge' => 'ÉVÉNEMENT',
        'people' => 6,
        'price' => 150,
        'regime_label' => 'Classique',
        'status' => '4 commandes restantes',
        'status_type' => 'limited',
        'available' => false,
        'party' => false,
        'allergens' => ['gluten', 'lactose', 'oeufs', 'fruits-a-coque', 'crustaces', 'poisson'],
    ],
    2 => [
        'order' => 5,
        'title' => 'Menu Saint-Valentin',
        'description' => 'Entrées, plats et desserts gourmands pour un dîner en amoureux, à partager à la maison.',
        'image' => '/images/home/menu-saint-valentin.png',
        'image_alt' => 'Dessert aux fraises et chocolats sur une table aux bougies',
        'badge' => 'SAINT-VALENTIN',
        'people' => 2,
        'price' => 80,
        'regime_label' => 'Saint-Valentin',
        'status' => 'Stock limité : 3 commandes restantes',
        'status_type' => 'limited',
        'available' => false,
        'party' => true,
        'allergens' => ['gluten', 'lactose', 'oeufs', 'crustaces'],
    ],
    5 => [
        'order' => 6,
        'title' => 'Menu Pâques en Famille',
        'description' => 'Plats printaniers, desserts colorés et mignardises pour un repas de fête convivial.',
        'image' => '/images/home/menu-paques-famille.png',
        'image_alt' => 'Buffet de Pâques familial avec gâteau et fleurs',
        'badge' => 'PÂQUES',
        'people' => 8,
        'price' => 170,
        'regime_label' => 'Pâques',
        'status' => 'Disponible cette semaine',
        'status_type' => 'available',
        'available' => true,
        'party' => true,
        'allergens' => ['gluten', 'lactose', 'oeufs', 'fruits-a-coque'],
    ],
];

$menuCards = [];

foreach ($menus as $menu) {
    $menuId = (int) $menu['id_menu'];
    $presentation = $menuPresentation[$menuId] ?? [
        'order' => 100 + $menuId,
        'title' => (string) $menu['titre'],
        'description' => (string) $menu['description'],
        'image' => '/images/home/menu-noel-tradition.png',
        'image_alt' => (string) $menu['titre'],
        'badge' => (string) $menu['theme'],
        'people' => (int) $menu['nombre_personnes_minimum'],
        'price' => (int) $menu['prix_minimum'],
        'regime_label' => (string) $menu['regime'],
        'status' => 'Disponible',
        'status_type' => 'available',
        'available' => true,
        'party' => false,
        'allergens' => [],
    ];

    $menuCards[] = array_merge($menu, $presentation);
}

usort(
    $menuCards,
    static fn (array $first, array $second): int => $first['order'] <=> $second['order']
);
?>
<section class="menu-list-hero" aria-labelledby="menu-list-title">
    <div class="container menu-list-hero-content">
        <h1 id="menu-list-title">
            <span>Menus traiteur</span>
            <span class="menu-list-title-accent">du moment</span>
        </h1>
        <p class="menu-list-lead">
            D&eacute;couvrez une s&eacute;lection en rotation selon les saisons, les f&ecirc;tes et les &eacute;v&eacute;nements.
            Besoin d'un menu d&eacute;j&agrave; appr&eacute;ci&eacute; ou d'une formule sur mesure ? Contactez notre &eacute;quipe.
        </p>
    </div>
</section>

<section class="menu-catalog-section">
    <div class="container menu-catalog-container">
        <form class="menu-filter-panel" method="get" action="/menus" data-menu-filters>
            <div class="menu-filter-pills" aria-label="Filtres rapides">
                <button class="menu-filter-pill is-active" type="button" data-quick-filter="all" aria-pressed="true">Tous</button>
                <button class="menu-filter-pill" type="button" data-quick-filter="available" aria-pressed="false">Disponible</button>
                <button class="menu-filter-pill" type="button" data-quick-filter="party" aria-pressed="false">Fêtes</button>
                <button class="menu-filter-pill" type="button" data-quick-filter="large" aria-pressed="false">Plus de 6 gourmands</button>
                <button class="menu-filter-pill" type="button" data-quick-filter="budget" aria-pressed="false">Moins de 150 €</button>
                <button
                    class="menu-filter-pill menu-filter-pill--primary"
                    type="button"
                    data-filter-overlay-open
                    aria-haspopup="dialog"
                    aria-expanded="false"
                    aria-controls="menu-filter-overlay"
                >
                    <i class="bi bi-sliders2" aria-hidden="true"></i>
                    Tous les filtres
                </button>
            </div>

            <p class="menu-filter-note">Les résultats se mettent à jour automatiquement.</p>
        </form>

        <?php require dirname(__DIR__) . '/partials/menu-filter-overlay.php'; ?>

        <?php if ($menuCards === []): ?>
            <div class="page-panel">
                <h2>Aucun menu disponible</h2>
                <p class="muted-text">
                    Les menus seront bientot ajoutes au catalogue.
                </p>
            </div>
        <?php else: ?>
            <div class="menu-grid">
                <?php foreach ($menuCards as $menu): ?>
                    <article
                        class="menu-card"
                        data-menu-card
                        data-theme-id="<?= (int) $menu['id_theme'] ?>"
                        data-regime-id="<?= (int) $menu['id_regime'] ?>"
                        data-price="<?= (float) $menu['price'] ?>"
                        data-people="<?= (int) $menu['people'] ?>"
                        data-available="<?= $menu['available'] ? '1' : '0' ?>"
                        data-party="<?= $menu['party'] ? '1' : '0' ?>"
                        data-status-type="<?= $this->e($menu['status_type']) ?>"
                        data-status-week="<?= str_contains((string) $menu['status'], 'semaine') ? '1' : '0' ?>"
                        data-allergens="<?= $this->e(implode(' ', $menu['allergens'] ?? [])) ?>"
                    >
                        <div class="menu-card-image">
                            <button
                                class="image-preview-button"
                                type="button"
                                data-image-preview
                                data-image-src="<?= $this->e($menu['image']) ?>"
                                data-image-alt="<?= $this->e($menu['image_alt']) ?>"
                                aria-label="Agrandir l'image : <?= $this->e($menu['title']) ?>"
                            >
                                <img src="<?= $this->e($menu['image']) ?>" alt="<?= $this->e($menu['image_alt']) ?>">
                            </button>
                            <span class="menu-card-badge"><?= $this->e($menu['badge']) ?></span>
                        </div>
                        <div class="menu-card-content">
                            <h2><?= $this->e($menu['title']) ?></h2>
                            <p class="menu-card-description">
                                <?= $this->e($menu['description']) ?>
                            </p>
                            <p class="menu-card-people">
                                <i class="bi bi-people" aria-hidden="true"></i>
                                À partir de <?= (int) $menu['people'] ?> personnes
                            </p>
                            <div class="menu-card-price-row">
                                <p class="menu-card-price"><?= number_format((float) $menu['price'], 0, ',', ' ') ?> €</p>
                                <span class="menu-card-regime"><?= $this->e($menu['regime_label']) ?></span>
                            </div>
                        </div>
                        <div class="menu-card-footer">
                            <p class="menu-card-status menu-card-status--<?= $this->e($menu['status_type']) ?>">
                                <span aria-hidden="true"></span>
                                <?= $this->e($menu['status']) ?>
                            </p>
                            <a class="menu-card-link" href="/menus/<?= (int) $menu['id_menu'] ?>">
                                Voir le menu
                            </a>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
            <div class="page-panel menu-empty-state" data-menu-empty-state hidden>
                <h2>Aucun menu ne correspond aux filtres</h2>
                <p class="muted-text">
                    Ajustez les criteres ou reinitialisez les filtres pour voir
                    toutes les offres disponibles.
                </p>
            </div>
        <?php endif; ?>

        <div class="menu-custom-cta">
            <a class="menu-custom-button" href="/contact">Demander un menu sur mesure</a>
            <a class="menu-custom-link" href="/contact">Besoin d'un menu sur mesure ? Contactez-nous</a>
        </div>
    </div>
</section>
