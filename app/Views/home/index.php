<?php

use App\Services\MenuPresentation;

/**
 * Vue publique de la page d'accueil.
 *
 * @var list<array<string, mixed>> $menus
 * @var list<array<string, mixed>> $validatedReviews
 */

$text = static function (string $value): string {
    return htmlspecialchars(
        html_entity_decode($value, ENT_QUOTES | ENT_HTML5, 'UTF-8'),
        ENT_QUOTES,
        'UTF-8'
    );
};

$featuredMenus = array_map(
    static function (array $menu): array {
        $card = MenuPresentation::forListing($menu);

        return [
            'title' => $card['title'],
            'description' => $card['description'],
            'image' => $card['image'],
            'image_alt' => $card['image_alt'],
            'tag' => $card['badge'],
            'people' => 'À partir de ' . (int) $card['people'] . ' personnes',
            'peopleCount' => (int) $card['people'],
            'price' => number_format((float) $card['price'], 0, ',', ' ') . ' €',
            'priceValue' => (float) $card['price'],
            'regime' => $card['regime_label'],
            'status' => $card['status'],
            'statusTone' => $card['status_type'],
            'isAvailable' => (bool) $card['available'],
            'isParty' => (bool) $card['party'],
            'themeId' => (int) $menu['id_theme'],
            'regimeId' => (int) $menu['id_regime'],
            'statusType' => $card['status_type'],
            'statusWeek' => str_contains((string) $card['status'], 'semaine'),
            'allergens' => $card['allergens'],
            'url' => $card['url'],
            'order' => $card['order'],
        ];
    },
    $menus
);

usort(
    $featuredMenus,
    static fn (array $first, array $second): int => $first['order'] <=> $second['order']
);

$commitments = [
    [
        'title' => '25 ans d&apos;expertise',
        'description' => 'Une exigence constante de qualit&eacute; et de r&eacute;gularit&eacute;.',
        'icon' => '/images/home/icon-expertise.svg',
    ],
    [
        'title' => 'Fra&icirc;cheur garantie',
        'description' => 'Saisonnalit&eacute;, cr&eacute;ativit&eacute; et modernit&eacute;.',
        'icon' => '/images/home/icon-freshness.svg',
    ],
    [
        'title' => 'Cuisine inclusive',
        'description' => 'Du classique au vegan, avec suivi allerg&egrave;nes.',
        'icon' => '/images/home/icon-inclusive.svg',
    ],
    [
        'title' => 'Ponctualit&eacute;',
        'description' => 'Organisation rigoureuse, livraison et service.',
        'icon' => '/images/home/icon-delivery.svg',
    ],
];

$validatedReviews ??= [];

$reviewAvatars = [
    '/images/home/avatar-sophie.png',
    '/images/home/avatar-marc.png',
    '/images/home/avatar-helene.png',
];

$reviewEventLabels = [
    'Menu Noël Tradition' => 'Repas de Noël',
    'Menu Cocktail Bordelais' => 'Cocktail entreprise',
    'Menu Pâques en Famille' => 'Anniversaire familial',
];

$monthLabels = [
    '01' => 'Janvier',
    '02' => 'Février',
    '03' => 'Mars',
    '04' => 'Avril',
    '05' => 'Mai',
    '06' => 'Juin',
    '07' => 'Juillet',
    '08' => 'Août',
    '09' => 'Septembre',
    '10' => 'Octobre',
    '11' => 'Novembre',
    '12' => 'Décembre',
];

$reviewName = static function (array $review): string {
    $firstName = trim((string) ($review['prenom'] ?? 'Client'));
    $lastName = trim((string) ($review['nom'] ?? ''));
    $lastInitial = '';

    if ($lastName !== '') {
        $initial = function_exists('mb_substr') ? mb_substr($lastName, 0, 1, 'UTF-8') : substr($lastName, 0, 1);
        $lastInitial = $initial . '.';
    }

    return trim($firstName . ' ' . $lastInitial);
};

$reviewDate = static function (array $review) use ($monthLabels): string {
    $dateValue = (string) ($review['event_date'] ?? $review['created_at'] ?? '');

    if ($dateValue === '') {
        return '';
    }

    try {
        $date = new DateTimeImmutable($dateValue);
    } catch (Throwable) {
        return '';
    }

    return ($monthLabels[$date->format('m')] ?? $date->format('m')) . ' ' . $date->format('Y');
};
?>
<section class="hero-section home-hero" id="top">
    <div class="container hero-content">
        <h1>
            <span class="hero-title-intro">Bienvenue chez</span>
            <span class="hero-title-brand">Vite &amp; Gourmand</span>
        </h1>
        <p class="hero-lead">
            Du simple repas de famille aux grandes c&eacute;l&eacute;brations,
            notre &eacute;quipe bordelaise met tout son professionnalisme
            au service de vos papilles.
        </p>
        <div class="hero-actions" aria-label="Action principale">
            <a class="primary-link" href="/menus">D&Eacute;COUVRIR NOS MENUS</a>
        </div>
    </div>
</section>

<section class="home-about-section" aria-labelledby="home-about-title">
    <div class="container home-section-container">
        <header class="home-section-header">
            <h2 id="home-about-title">Qui sommes-nous ?</h2>
            <span aria-hidden="true"></span>
        </header>

        <div class="home-about-grid">
            <article class="home-about-row">
                <div class="home-about-copy">
                    <h3>Notre Histoire</h3>
                    <p>
                        Install&eacute;e au c&oelig;ur de Bordeaux depuis 25 ans,
                        Vite &amp; Gourmand est avant tout une aventure humaine
                        n&eacute;e de la passion d&apos;un duo fondateur : Julie et Jos&eacute;.
                    </p>
                    <p>
                        Sans cesse &agrave; la recherche de nouvelles saveurs, nous mettons
                        notre savoir-faire artisanal au service de tous vos &eacute;v&eacute;nements,
                        qu&apos;il s&apos;agisse d&apos;un repas de f&ecirc;te traditionnel comme No&euml;l
                        et P&acirc;ques ou d&apos;une r&eacute;ception sur mesure.
                    </p>
                </div>
                <img src="/images/home/about-preparation.png" alt="Julie et Jose preparent un buffet gourmand">
            </article>

            <article class="home-about-row">
                <div class="home-about-copy">
                    <h3>Notre &Eacute;quipe</h3>
                    <p>
                        Aujourd&apos;hui entour&eacute;s d&apos;une &eacute;quipe d&eacute;vou&eacute;e en cuisine
                        et en logistique, nous travaillons main dans la main pour donner
                        vie &agrave; vos projets :
                    </p>
                    <ul>
                        <li>Julie insuffle sa cr&eacute;ativit&eacute; au quotidien en imaginant, avec nos cuisiniers, des cartes et des menus en constante &eacute;volution pour surprendre vos papilles.</li>
                        <li>Jos&eacute; veille avec rigueur &agrave; l&apos;excellence de notre organisation et coordonne nos &eacute;quipes pour garantir le respect des traditions culinaires qui font notre renomm&eacute;e.</li>
                    </ul>
                    <p>
                        Ensemble, nous unissons notre professionnalisme et notre amour de
                        la cuisine pour vous garantir des prestations de qualit&eacute;, alliant
                        la rapidit&eacute; d&apos;un service irr&eacute;prochable &agrave; la g&eacute;n&eacute;rosit&eacute;
                        d&apos;une table gourmande.
                    </p>
                </div>
                <img src="/images/home/about-team.png" alt="Equipe Vite et Gourmand en cuisine">
            </article>
        </div>
    </div>
</section>

<section class="home-engagements-section" aria-labelledby="home-engagements-title">
    <div class="container home-section-container">
        <header class="home-section-header">
            <h2 id="home-engagements-title">NOS ENGAGEMENTS</h2>
            <span aria-hidden="true"></span>
        </header>

        <div class="home-commitment-grid">
            <?php foreach ($commitments as $commitment): ?>
                <article class="home-commitment-card">
                    <span class="home-commitment-icon" aria-hidden="true">
                        <img src="<?= $this->e($commitment['icon']) ?>" alt="">
                    </span>
                    <h3><?= $text($commitment['title']) ?></h3>
                    <p><?= $text($commitment['description']) ?></p>
                </article>
            <?php endforeach; ?>
        </div>

    </div>
</section>

<section class="home-featured-menus-section" aria-labelledby="home-featured-menus-title">
    <div class="container home-section-container">
        <header class="home-section-header home-menu-header">
            <p>S&Eacute;LECTION DU MOMENT</p>
            <h2 id="home-featured-menus-title">Nos menus &agrave; d&eacute;couvrir</h2>
            <strong>
                Une s&eacute;lection de menus faits maison pour vos repas de famille,
                f&ecirc;tes traditionnelles et r&eacute;ceptions priv&eacute;es.
            </strong>
            <span aria-hidden="true"></span>
        </header>

        <div class="home-menu-filter-row" aria-label="Filtres visuels des menus" data-menu-filters>
            <button class="is-active" type="button" data-quick-filter="all" aria-pressed="true">Tous</button>
            <button type="button" data-quick-filter="available" aria-pressed="false">Disponible</button>
            <button type="button" data-quick-filter="party" aria-pressed="false">F&ecirc;tes</button>
            <button type="button" data-quick-filter="large" aria-pressed="false">Plus de 6 gourmands</button>
            <button type="button" data-quick-filter="budget" aria-pressed="false">Moins de 150 &euro;</button>
            <button
                class="home-menu-filter-all"
                type="button"
                data-filter-overlay-open
                aria-haspopup="dialog"
                aria-expanded="false"
                aria-controls="menu-filter-overlay"
            >
                <i class="bi bi-sliders2-vertical" aria-hidden="true"></i>
                Tous les filtres
            </button>
        </div>
        <p class="home-menu-filter-note">Les r&eacute;sultats se mettent &agrave; jour automatiquement.</p>

        <?php require dirname(__DIR__) . '/partials/menu-filter-overlay.php'; ?>

        <div class="home-featured-menu-grid">
            <?php foreach ($featuredMenus as $menu): ?>
                <article
                    class="home-featured-menu-card"
                    data-menu-card
                    data-home-menu-card
                    data-theme-id="<?= (int) $menu['themeId'] ?>"
                    data-regime-id="<?= (int) $menu['regimeId'] ?>"
                    data-available="<?= $menu['isAvailable'] ? '1' : '0' ?>"
                    data-party="<?= $menu['isParty'] ? '1' : '0' ?>"
                    data-people="<?= (int) $menu['peopleCount'] ?>"
                    data-price="<?= (int) $menu['priceValue'] ?>"
                    data-status-type="<?= $this->e($menu['statusType']) ?>"
                    data-status-week="<?= $menu['statusWeek'] ? '1' : '0' ?>"
                    data-allergens="<?= $this->e(implode(' ', $menu['allergens'])) ?>"
                >
                    <div class="home-featured-menu-image">
                        <button
                            class="image-preview-button"
                            type="button"
                            data-image-preview
                            data-image-src="<?= $this->e($menu['image']) ?>"
                            data-image-alt="<?= $text($menu['image_alt']) ?>"
                            aria-label="Agrandir l'image : <?= $text($menu['title']) ?>"
                        >
                            <img src="<?= $this->e($menu['image']) ?>" alt="<?= $text($menu['image_alt']) ?>">
                        </button>
                        <span><?= $text($menu['tag']) ?></span>
                    </div>

                    <div class="home-featured-menu-body">
                        <h3><?= $text($menu['title']) ?></h3>
                        <p><?= $text($menu['description']) ?></p>

                        <div class="home-menu-people">
                            <i aria-hidden="true"></i>
                            <span><?= $text($menu['people']) ?></span>
                        </div>

                        <div class="home-menu-price-row">
                            <strong><?= $text($menu['price']) ?></strong>
                            <em><?= $text($menu['regime']) ?></em>
                        </div>
                    </div>

                    <div class="home-featured-menu-footer">
                        <p class="home-menu-status is-<?= $this->e($menu['statusTone']) ?>">
                            <?= $text($menu['status']) ?>
                        </p>
                        <a href="<?= $this->e($menu['url']) ?>">Voir le menu</a>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>

        <div class="home-menu-bottom-actions">
            <a class="home-menu-all-link" href="/menus">VOIR TOUS LES MENUS</a>
            <a class="home-menu-contact-link" href="/contact">Besoin d&apos;un menu sur mesure ? Contactez-nous</a>
        </div>
    </div>
</section>

<section class="home-testimonials-section" aria-labelledby="home-testimonials-title">
    <div class="container home-section-container">
        <header class="home-section-header home-testimonials-header">
            <h2 id="home-testimonials-title">Ce que disent nos clients</h2>
            <p>Des retours authentiques, publi&eacute;s apr&egrave;s validation par notre &eacute;quipe.</p>
            <span aria-hidden="true"></span>
        </header>

        <?php if ($validatedReviews === []): ?>
            <div class="home-testimonial-grid">
                <article class="home-testimonial-card">
                    <p class="home-testimonial-empty">
                        Aucun avis client valid&eacute; n&apos;est publi&eacute; pour le moment.
                    </p>
                </article>
            </div>
        <?php else: ?>
            <div class="home-testimonial-grid">
            <?php foreach ($validatedReviews as $index => $testimonial): ?>
                <?php
                $menuTitle = (string) ($testimonial['menu_titre'] ?? '');
                $eventLabel = $reviewEventLabels[$menuTitle] ?? ($menuTitle !== '' ? $menuTitle : 'Prestation traiteur');
                $rating = (int) ($testimonial['note'] ?? 0);
                $rating = max(1, min(5, $rating));
                ?>
                <article class="home-testimonial-card">
                    <div class="home-testimonial-person">
                        <img src="<?= $this->e($reviewAvatars[$index % count($reviewAvatars)]) ?>" alt="<?= $text($reviewName($testimonial)) ?>">
                        <div>
                            <strong><?= $text($reviewName($testimonial)) ?></strong>
                            <span><?= $text($eventLabel) ?></span>
                            <small><?= $text($reviewDate($testimonial)) ?></small>
                        </div>
                    </div>

                    <div class="home-testimonial-rating" aria-label="Note <?= $rating ?> sur 5">
                        <?php for ($star = 1; $star <= 5; $star++): ?>
                            <img
                                src="<?= $star <= $rating ? '/images/home/icon-star.svg' : '/images/home/icon-star-muted.svg' ?>"
                                alt=""
                            >
                        <?php endfor; ?>
                    </div>

                    <blockquote>&laquo; <?= $text((string) ($testimonial['commentaire'] ?? '')) ?> &raquo;</blockquote>

                    <p class="home-verified-badge">
                        <img src="/images/home/icon-check.svg" alt="">
                        Avis valid&eacute;
                    </p>
                </article>
            <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
