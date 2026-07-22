<?php

/**
 * Vue publique de la page d'accueil.
 */

$text = static function (string $value): string {
    return htmlspecialchars(
        html_entity_decode($value, ENT_QUOTES | ENT_HTML5, 'UTF-8'),
        ENT_QUOTES,
        'UTF-8'
    );
};

$featuredMenus = [
    [
        'title' => 'Menu No&euml;l Tradition',
        'description' => 'Foie gras maison, volaille festive, l&eacute;gumes r&ocirc;tis et dessert gourmand pour vos repas de fin d&apos;ann&eacute;e.',
        'image' => '/images/home/menu-noel-tradition.png',
        'tag' => 'No&euml;l',
        'people' => '&Agrave; partir de 8 personnes',
        'peopleCount' => 8,
        'price' => '190 &euro;',
        'priceValue' => 190,
        'regime' => 'Classique',
        'status' => 'Stock limit&eacute; : 5 commandes restantes',
        'statusTone' => 'limited',
        'isAvailable' => false,
        'isParty' => true,
        'themeId' => 1,
        'regimeId' => 1,
        'statusType' => 'limited',
        'statusWeek' => false,
        'allergens' => ['gluten', 'oeufs'],
        'url' => '/menus/1',
    ],
    [
        'title' => 'Menu Cocktail Bordelais',
        'description' => 'Bouch&eacute;es sal&eacute;es, verrines, mini tartines et mignardises pour vos r&eacute;ceptions priv&eacute;es ou professionnelles.',
        'image' => '/images/home/menu-cocktail-bordelais.png',
        'tag' => 'Buffet',
        'people' => '&Agrave; partir de 10 personnes',
        'peopleCount' => 10,
        'price' => '220 &euro;',
        'priceValue' => 220,
        'regime' => 'Classique',
        'status' => 'Disponible cette semaine',
        'statusTone' => 'available',
        'isAvailable' => true,
        'isParty' => false,
        'themeId' => 6,
        'regimeId' => 1,
        'statusType' => 'available',
        'statusWeek' => true,
        'allergens' => ['gluten', 'lactose'],
        'url' => '/menus/6',
    ],
    [
        'title' => 'Menu V&eacute;g&eacute;-Gourmand',
        'description' => 'Tartes, l&eacute;gumes r&ocirc;tis, quiches et desserts v&eacute;g&eacute;tariens pour un repas complet et savoureux.',
        'image' => '/images/home/menu-vege-gourmand.png',
        'tag' => '100 % vegetal',
        'people' => '&Agrave; partir de 6 personnes',
        'peopleCount' => 6,
        'price' => '120 &euro;',
        'priceValue' => 120,
        'regime' => 'V&eacute;g&eacute;tarien',
        'status' => 'Disponible',
        'statusTone' => 'available',
        'isAvailable' => true,
        'isParty' => false,
        'themeId' => 4,
        'regimeId' => 2,
        'statusType' => 'available',
        'statusWeek' => false,
        'allergens' => ['gluten', 'oeufs'],
        'url' => '/menus/4',
    ],
    [
        'title' => 'Menu Terre &amp; Mer',
        'description' => 'Produits marins et saveurs traditionnelles pour une prestation compl&egrave;te et raffin&eacute;e.',
        'image' => '/images/home/menu-terre-mer.png',
        'tag' => '&Eacute;v&eacute;nement',
        'people' => '&Agrave; partir de 6 personnes',
        'peopleCount' => 6,
        'price' => '150 &euro;',
        'priceValue' => 150,
        'regime' => 'Classique',
        'status' => '4 commandes restantes',
        'statusTone' => 'limited',
        'isAvailable' => false,
        'isParty' => false,
        'themeId' => 3,
        'regimeId' => 1,
        'statusType' => 'limited',
        'statusWeek' => false,
        'allergens' => ['poisson', 'crustaces'],
        'url' => '/menus/3',
    ],
    [
        'title' => 'Menu Saint-Valentin',
        'description' => 'Entr&eacute;es, plats et desserts gourmands pour un d&icirc;ner en amoureux, &agrave; partager &agrave; la maison.',
        'image' => '/images/home/menu-saint-valentin.png',
        'tag' => 'Saint-Valentin',
        'people' => '&Agrave; partir de 2 personnes',
        'peopleCount' => 2,
        'price' => '80 &euro;',
        'priceValue' => 80,
        'regime' => 'Saint-Valentin',
        'status' => 'Stock limit&eacute; : 3 commandes restantes',
        'statusTone' => 'limited',
        'isAvailable' => false,
        'isParty' => true,
        'themeId' => 2,
        'regimeId' => 1,
        'statusType' => 'limited',
        'statusWeek' => false,
        'allergens' => ['gluten', 'lactose', 'oeufs'],
        'url' => '/menus/2',
    ],
    [
        'title' => 'Menu P&acirc;ques en Famille',
        'description' => 'Plats printaniers, desserts color&eacute;s et mignardises pour un repas de f&ecirc;te convivial.',
        'image' => '/images/home/menu-paques-famille.png',
        'tag' => 'Paques',
        'people' => '&Agrave; partir de 8 personnes',
        'peopleCount' => 8,
        'price' => '170 &euro;',
        'priceValue' => 170,
        'regime' => 'P&acirc;ques',
        'status' => 'Disponible cette semaine',
        'statusTone' => 'available',
        'isAvailable' => true,
        'isParty' => true,
        'themeId' => 5,
        'regimeId' => 1,
        'statusType' => 'available',
        'statusWeek' => true,
        'allergens' => ['gluten', 'oeufs'],
        'url' => '/menus/5',
    ],
];

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

$testimonials = [
    [
        'name' => 'Sophie R.',
        'event' => 'Repas de No&euml;l',
        'date' => 'D&eacute;cembre 2025',
        'avatar' => '/images/home/avatar-sophie.png',
        'rating' => 5,
        'quote' => '&laquo; Nous avions command&eacute; pour un repas de No&euml;l en famille. Les quantit&eacute;s &eacute;taient g&eacute;n&eacute;reuses, les plats bien pr&eacute;sent&eacute;s et la livraison parfaitement &agrave; l&apos;heure. On a vraiment senti le fait maison. &raquo;',
    ],
    [
        'name' => 'Marc L.',
        'event' => 'Cocktail entreprise',
        'date' => 'Mars 2026',
        'avatar' => '/images/home/avatar-marc.png',
        'rating' => 5,
        'quote' => '&laquo; Tr&egrave;s bon contact d&egrave;s le d&eacute;part. L&apos;&eacute;quipe nous a aid&eacute;s &agrave; adapter le menu au nombre d&apos;invit&eacute;s et aux contraintes de la salle. Tout &eacute;tait pr&ecirc;t dans les temps. &raquo;',
    ],
    [
        'name' => 'H&eacute;l&egrave;ne B.',
        'event' => 'Anniversaire familial',
        'date' => 'Avril 2026',
        'avatar' => '/images/home/avatar-helene.png',
        'rating' => 4,
        'quote' => '&laquo; Les conditions du menu &eacute;taient claires avant la commande, notamment pour le d&eacute;lai de pr&eacute;paration. Les invit&eacute;s ont beaucoup appr&eacute;ci&eacute; les entr&eacute;es et les desserts. &raquo;',
    ],
];
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
                            data-image-alt="<?= $text($menu['title']) ?>"
                            aria-label="Agrandir l'image : <?= $text($menu['title']) ?>"
                        >
                            <img src="<?= $this->e($menu['image']) ?>" alt="<?= $text($menu['title']) ?>">
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

        <div class="home-testimonial-grid">
            <?php foreach ($testimonials as $testimonial): ?>
                <article class="home-testimonial-card">
                    <div class="home-testimonial-person">
                        <img src="<?= $this->e($testimonial['avatar']) ?>" alt="<?= $text($testimonial['name']) ?>">
                        <div>
                            <strong><?= $text($testimonial['name']) ?></strong>
                            <span><?= $text($testimonial['event']) ?></span>
                            <small><?= $text($testimonial['date']) ?></small>
                        </div>
                    </div>

                    <div class="home-testimonial-rating" aria-label="Note <?= (int) $testimonial['rating'] ?> sur 5">
                        <?php for ($star = 1; $star <= 5; $star++): ?>
                            <img
                                src="<?= $star <= (int) $testimonial['rating'] ? '/images/home/icon-star.svg' : '/images/home/icon-star-muted.svg' ?>"
                                alt=""
                            >
                        <?php endfor; ?>
                    </div>

                    <blockquote><?= $text($testimonial['quote']) ?></blockquote>

                    <p class="home-verified-badge">
                        <img src="/images/home/icon-check.svg" alt="">
                        Avis valid&eacute;
                    </p>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
