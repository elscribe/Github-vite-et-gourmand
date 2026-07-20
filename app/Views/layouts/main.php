<?php

/**
 * Layout HTML principal.
 *
 * Les vues sont inserees dans cette structure de page partagee afin de garder
 * une configuration Bootstrap 5 commune, un en-tete et un pied de page.
 */

$pageTitle = $pageTitle ?? 'Vite & Gourmand';
$successFlash = \App\Core\Session::pullFlash('success');
$errorFlash = \App\Core\Session::pullFlash('error');
$isAuthenticated = \App\Core\Session::isAuthenticated();
$currentRole = \App\Core\Session::role();
$currentPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';

$isHomeActive = $currentPath === '/';
$isMenusActive = str_starts_with($currentPath, '/menus');
$isContactActive = $currentPath === '/contact';
$isAuthActive = in_array($currentPath, [
    '/connexion',
    '/inscription',
    '/mot-de-passe/oublie',
    '/mot-de-passe/reinitialisation',
], true);
$isClientActive = str_starts_with($currentPath, '/mon-compte')
    || str_starts_with($currentPath, '/commandes')
    || str_starts_with($currentPath, '/avis');
$isBackOfficeRoute = str_starts_with($currentPath, '/admin') || str_starts_with($currentPath, '/employe');
$isAdminArea = str_starts_with($currentPath, '/admin') || ($currentRole === 'administrateur' && str_starts_with($currentPath, '/employe'));
$isEmployeeArea = str_starts_with($currentPath, '/employe') && !$isAdminArea;
$isBackOffice = $isBackOfficeRoute;
$isCustomerArea = $isAuthenticated && !$isBackOffice && !in_array($currentRole, ['administrateur', 'employe'], true);
$bodyClass = trim(($bodyClass ?? '') . ($isBackOffice ? ' backoffice-body' : ''));
$currentUser = null;
$backOfficeOrdersBasePath = $isAdminArea ? '/admin/commandes' : '/employe/commandes';
$backOfficeReviewsBasePath = $isAdminArea ? '/admin/avis' : '/employe/avis';

if ($isAuthenticated && \App\Core\Session::userId() !== null) {
    $currentUser = (new \App\Models\UserModel())->findById((int) \App\Core\Session::userId());
}

$currentUserName = $currentUser === null
    ? 'Vite & Gourmand'
    : trim((string) $currentUser['prenom'] . ' ' . (string) $currentUser['nom']);
$currentUserInitials = $currentUser === null
    ? 'VG'
    : strtoupper(substr((string) $currentUser['prenom'], 0, 1) . substr((string) $currentUser['nom'], 0, 1));
$backOfficePendingOrders = 0;
$backOfficePendingReviews = 0;

if ($isBackOffice && in_array($currentRole, ['administrateur', 'employe'], true)) {
    try {
        $backOfficeStats = (new \App\Models\OrderModel())->dashboardDailyStats();
        $backOfficePendingOrders = (int) $backOfficeStats['pending_orders'];
        $backOfficePendingReviews = (new \App\Models\ReviewModel())->countPending();
    } catch (\Throwable) {
        $backOfficePendingOrders = 0;
        $backOfficePendingReviews = 0;
    }
}

$backOfficeNotificationCount = $backOfficePendingOrders + $backOfficePendingReviews;

$backOfficeNav = $isAdminArea
    ? [
        ['href' => '/admin', 'label' => 'Tableau de bord', 'icon' => 'bi-grid-1x2', 'active' => $currentPath === '/admin'],
        ['href' => '/admin/commandes', 'label' => 'Commandes', 'icon' => 'bi-inbox', 'active' => str_starts_with($currentPath, '/admin/commandes')],
        ['href' => '/admin/menus', 'label' => 'Menus', 'icon' => 'bi-journal-bookmark', 'active' => str_starts_with($currentPath, '/admin/menus')],
        ['href' => '/admin/plats', 'label' => 'Plats / Recettes', 'icon' => 'bi-clipboard2', 'active' => str_starts_with($currentPath, '/admin/plats')],
        ['href' => '/admin/horaires', 'label' => 'Horaires', 'icon' => 'bi-clock', 'active' => str_starts_with($currentPath, '/admin/horaires')],
        ['href' => '/admin/avis', 'label' => 'Avis clients', 'icon' => 'bi-chat-left', 'active' => str_starts_with($currentPath, '/admin/avis')],
        ['href' => '/admin/employes', 'label' => 'Employés', 'icon' => 'bi-person', 'active' => str_starts_with($currentPath, '/admin/employes')],
        ['href' => '/admin/statistiques', 'label' => 'Statistiques', 'icon' => 'bi-bar-chart', 'active' => str_starts_with($currentPath, '/admin/statistiques')],
    ]
    : [
        ['href' => '/employe', 'label' => 'Tableau de bord', 'icon' => 'bi-grid-1x2', 'active' => $currentPath === '/employe'],
        ['href' => '/employe/commandes', 'label' => 'Commandes', 'icon' => 'bi-inbox', 'active' => str_starts_with($currentPath, '/employe/commandes')],
        ['href' => '/employe/avis', 'label' => 'Avis clients', 'icon' => 'bi-chat-left', 'active' => str_starts_with($currentPath, '/employe/avis')],
    ];

$assetVersion = static function (string $assetPath): string {
    $fullPath = dirname(__DIR__, 3) . '/public' . $assetPath;

    return is_file($fullPath) ? (string) filemtime($fullPath) : '1';
};
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Playfair+Display:wght@600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="/assets/css/style.css?v=<?= $assetVersion('/assets/css/style.css') ?>" rel="stylesheet">
</head>
<body id="top" class="<?= htmlspecialchars($bodyClass, ENT_QUOTES, 'UTF-8') ?>">
    <?php if ($isBackOffice): ?>
        <main class="backoffice-shell">
            <aside class="backoffice-sidebar" aria-label="Navigation interne">
                <a class="backoffice-brand" href="<?= $isAdminArea ? '/admin' : '/employe' ?>" aria-label="Vite & Gourmand">
                    <span class="backoffice-brand-mark" aria-hidden="true">VG</span>
                </a>

                <nav class="backoffice-nav">
                    <?php foreach ($backOfficeNav as $item): ?>
                        <a href="<?= htmlspecialchars($item['href'], ENT_QUOTES, 'UTF-8') ?>" class="<?= $item['active'] ? 'is-active' : '' ?>">
                            <i class="bi <?= htmlspecialchars($item['icon'], ENT_QUOTES, 'UTF-8') ?>" aria-hidden="true"></i>
                            <span><?= htmlspecialchars($item['label'], ENT_QUOTES, 'UTF-8') ?></span>
                        </a>
                    <?php endforeach; ?>
                </nav>

                <a class="backoffice-logout" href="/deconnexion">
                    <i class="bi bi-box-arrow-right" aria-hidden="true"></i>
                    <span>Deconnexion</span>
                </a>
            </aside>

            <section class="backoffice-main">
                <header class="backoffice-topbar">
                    <button
                        class="backoffice-mobile-menu-toggle"
                        type="button"
                        aria-label="Ouvrir la navigation interne"
                        aria-controls="backoffice-mobile-nav"
                        aria-expanded="false"
                        data-backoffice-mobile-menu-toggle
                    >
                        <i class="bi bi-list" aria-hidden="true"></i>
                    </button>
                    <a class="backoffice-mobile-logo" href="<?= $isAdminArea ? '/admin' : '/employe' ?>" aria-label="Vite & Gourmand">
                        <img src="/images/brand/logo-mobile-vg.png" alt="Vite & Gourmand">
                    </a>
                    <div class="backoffice-user">
                        <div class="backoffice-notification-wrapper" data-backoffice-notifications>
                            <button
                                class="backoffice-notification"
                                type="button"
                                aria-label="<?= $backOfficeNotificationCount > 0 ? htmlspecialchars('Ouvrir les notifications : ' . (string) $backOfficeNotificationCount . ' action(s) a gerer', ENT_QUOTES, 'UTF-8') : 'Ouvrir les notifications' ?>"
                                aria-expanded="false"
                                aria-controls="backoffice-notification-panel"
                                data-backoffice-notification-toggle
                            >
                                <i class="bi bi-bell" aria-hidden="true"></i>
                                <?php if ($backOfficeNotificationCount > 0): ?>
                                    <span><?= $backOfficeNotificationCount > 99 ? '99+' : (int) $backOfficeNotificationCount ?></span>
                                <?php endif; ?>
                            </button>
                            <div
                                id="backoffice-notification-panel"
                                class="backoffice-notification-panel"
                                data-backoffice-notification-panel
                                hidden
                            >
                                <div class="backoffice-notification-panel-heading">
                                    <strong>Notifications</strong>
                                    <span><?= (int) $backOfficeNotificationCount ?> en attente</span>
                                </div>

                                <?php if ($backOfficeNotificationCount === 0): ?>
                                    <p class="backoffice-notification-empty">Aucune action en attente.</p>
                                <?php else: ?>
                                    <div class="backoffice-notification-list">
                                        <?php if ($backOfficePendingOrders > 0): ?>
                                            <a href="<?= htmlspecialchars($backOfficeOrdersBasePath . '?status=en_attente', ENT_QUOTES, 'UTF-8') ?>">
                                                <i class="bi bi-inbox" aria-hidden="true"></i>
                                                <span>
                                                    <strong><?= (int) $backOfficePendingOrders ?> commande<?= $backOfficePendingOrders > 1 ? 's' : '' ?> &agrave; valider</strong>
                                                    <small>Ouvrir les commandes en attente.</small>
                                                </span>
                                            </a>
                                        <?php endif; ?>

                                        <?php if ($backOfficePendingReviews > 0): ?>
                                            <a href="<?= htmlspecialchars($backOfficeReviewsBasePath, ENT_QUOTES, 'UTF-8') ?>">
                                                <i class="bi bi-chat-left" aria-hidden="true"></i>
                                                <span>
                                                    <strong><?= (int) $backOfficePendingReviews ?> avis client<?= $backOfficePendingReviews > 1 ? 's' : '' ?> &agrave; valider</strong>
                                                    <small>Ouvrir les avis en attente.</small>
                                                </span>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="backoffice-user-info">
                            <strong><?= htmlspecialchars($currentUserName, ENT_QUOTES, 'UTF-8') ?></strong>
                            <span><?= $isAdminArea ? 'Administration' : '&Eacute;quipe op&eacute;rationnelle' ?></span>
                        </div>
                        <span class="backoffice-avatar"><?= htmlspecialchars($currentUserInitials, ENT_QUOTES, 'UTF-8') ?></span>
                    </div>
                </header>

                <aside
                    id="backoffice-mobile-nav"
                    class="backoffice-mobile-nav"
                    aria-label="Navigation interne mobile"
                    data-backoffice-mobile-nav
                    hidden
                >
                    <button
                        class="backoffice-mobile-nav-scrim"
                        type="button"
                        aria-label="Fermer la navigation interne"
                        data-backoffice-mobile-nav-close
                    ></button>
                    <div class="backoffice-mobile-nav-panel" role="dialog" aria-modal="true" aria-label="Navigation interne">
                        <a class="backoffice-brand" href="<?= $isAdminArea ? '/admin' : '/employe' ?>" aria-label="Vite & Gourmand">
                            <span class="backoffice-brand-mark" aria-hidden="true">VG</span>
                        </a>

                        <nav class="backoffice-nav" aria-label="Navigation interne">
                            <?php foreach ($backOfficeNav as $item): ?>
                                <a href="<?= htmlspecialchars($item['href'], ENT_QUOTES, 'UTF-8') ?>" class="<?= $item['active'] ? 'is-active' : '' ?>">
                                    <i class="bi <?= htmlspecialchars($item['icon'], ENT_QUOTES, 'UTF-8') ?>" aria-hidden="true"></i>
                                    <span><?= htmlspecialchars($item['label'], ENT_QUOTES, 'UTF-8') ?></span>
                                </a>
                            <?php endforeach; ?>
                        </nav>

                        <a class="backoffice-logout" href="/deconnexion">
                            <i class="bi bi-box-arrow-right" aria-hidden="true"></i>
                            <span>Deconnexion</span>
                        </a>
                    </div>
                </aside>

                <div class="backoffice-content">
                    <?php if ($successFlash !== null || $errorFlash !== null): ?>
                        <div class="flash-container backoffice-flash">
                            <?php if ($successFlash !== null): ?>
                                <p class="alert-message success-message"><?= htmlspecialchars($successFlash, ENT_QUOTES, 'UTF-8') ?></p>
                            <?php endif; ?>
                            <?php if ($errorFlash !== null): ?>
                                <p class="alert-message error-message"><?= htmlspecialchars($errorFlash, ENT_QUOTES, 'UTF-8') ?></p>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    <?php require $viewPath; ?>
                </div>
            </section>
        </main>
    <?php else: ?>
        <header class="site-header">
            <nav class="navbar navbar-expand-md site-nav" aria-label="Navigation principale">
                <div class="container">
                    <a class="navbar-brand site-logo" href="/" aria-label="Vite & Gourmand - Accueil">
                        <img class="site-logo-primary" src="/images/brand/logo-primary.png" alt="Vite & Gourmand">
                        <img class="site-logo-compact" src="/images/brand/logo-mobile-vg.png" alt="Vite & Gourmand">
                    </a>
                    <button
                        class="mobile-menu-button"
                        type="button"
                        data-mobile-menu-open
                        aria-controls="mobile-public-menu"
                        aria-expanded="false"
                        aria-label="Ouvrir le menu"
                    >
                        <i class="bi bi-list" aria-hidden="true"></i>
                    </button>
                    <div class="navbar-nav site-nav-links">
                        <a class="nav-link<?= $isHomeActive ? ' is-active' : '' ?>" href="/">Accueil</a>
                        <a class="nav-link<?= $isMenusActive ? ' is-active' : '' ?>" href="/menus">Nos Menus</a>
                        <a class="nav-link<?= $isContactActive ? ' is-active' : '' ?>" href="/contact">Contact</a>
                        <?php if ($isAuthenticated): ?>
                            <?php if ($currentRole === 'administrateur'): ?>
                                <a class="nav-link" href="/admin">Admin</a>
                            <?php elseif ($currentRole === 'employe'): ?>
                                <a class="nav-link" href="/employe">Employé</a>
                            <?php else: ?>
                                <a class="nav-link<?= $isClientActive ? ' is-active' : '' ?>" href="/mon-compte">Mon espace</a>
                            <?php endif; ?>
                            <a class="nav-link" href="/deconnexion">Deconnexion</a>
                        <?php else: ?>
                            <a class="nav-link<?= $isAuthActive ? ' is-active' : '' ?>" href="/connexion">Mon espace</a>
                        <?php endif; ?>
                    </div>
                </div>
            </nav>
        </header>

        <aside class="mobile-public-menu" id="mobile-public-menu" data-mobile-menu hidden aria-label="Menu mobile public">
            <div class="mobile-public-menu-panel" role="dialog" aria-modal="true" aria-labelledby="mobile-public-menu-title">
                <header class="mobile-public-menu-header">
                    <p id="mobile-public-menu-title">Vite &amp; Gourmand</p>
                    <button class="mobile-menu-close" type="button" data-mobile-menu-close aria-label="Fermer le menu">
                        <i class="bi bi-x-lg" aria-hidden="true"></i>
                    </button>
                </header>

                <nav class="mobile-public-menu-links" aria-label="Navigation mobile">
                    <?php if ($isCustomerArea): ?>
                        <div class="mobile-public-menu-user">
                            <span><?= htmlspecialchars($currentUserInitials, ENT_QUOTES, 'UTF-8') ?></span>
                            <strong><?= htmlspecialchars($currentUserName, ENT_QUOTES, 'UTF-8') ?></strong>
                            <small>Client connecté</small>
                            <a class="mobile-public-profile-button" href="/mon-compte/profil">Gérer mon profil</a>
                        </div>
                        <a href="/"<?= $isHomeActive ? ' aria-current="page"' : '' ?>>Accueil</a>
                        <a href="/menus"<?= $isMenusActive ? ' aria-current="page"' : '' ?>>Menus</a>
                        <a href="/mon-compte"<?= $currentPath === '/mon-compte' ? ' aria-current="page"' : '' ?>>Mon espace gourmand</a>
                        <a href="/commandes"<?= str_starts_with($currentPath, '/commandes') ? ' aria-current="page"' : '' ?>>Mes commandes</a>
                        <a href="/contact"<?= $isContactActive ? ' aria-current="page"' : '' ?>>Contact</a>
                        <a href="/deconnexion">Déconnexion</a>
                    <?php elseif ($isAuthenticated): ?>
                        <a href="/"<?= $isHomeActive ? ' aria-current="page"' : '' ?>>Accueil</a>
                        <a href="/menus"<?= $isMenusActive ? ' aria-current="page"' : '' ?>>Nos menus</a>
                        <a href="/contact"<?= $isContactActive ? ' aria-current="page"' : '' ?>>Contact</a>
                        <?php if ($currentRole === 'administrateur'): ?>
                            <a href="/admin">Admin</a>
                        <?php elseif ($currentRole === 'employe'): ?>
                            <a href="/employe">Employé</a>
                        <?php else: ?>
                            <a href="/mon-compte">Mon espace</a>
                        <?php endif; ?>
                        <a href="/deconnexion">Deconnexion</a>
                    <?php else: ?>
                        <a href="/"<?= $isHomeActive ? ' aria-current="page"' : '' ?>>Accueil</a>
                        <a href="/menus"<?= $isMenusActive ? ' aria-current="page"' : '' ?>>Nos menus</a>
                        <a href="/contact"<?= $isContactActive ? ' aria-current="page"' : '' ?>>Contact</a>
                        <a href="/connexion"<?= $isAuthActive ? ' aria-current="page"' : '' ?>>Connexion</a>
                    <?php endif; ?>
                </nav>

                <?php if (!$isAuthenticated): ?>
                    <a class="mobile-public-menu-primary" href="/inscription">Cr&eacute;er un compte</a>
                <?php endif; ?>

                <footer class="mobile-public-menu-footer">
                    <div>
                        <a href="/mentions-legales">Mentions l&eacute;gales</a>
                        <a href="/cgv">CGV</a>
                        <a href="/confidentialite">Confidentialit&eacute;</a>
                    </div>
                    <p>Lun - Ven : 8h - 18h &middot; Sam : 9h - 16h &middot; Dim : ferm&eacute;</p>
                </footer>
            </div>
        </aside>

        <main class="site-main">
            <?php if ($successFlash !== null || $errorFlash !== null): ?>
                <div class="container flash-container">
                    <?php if ($successFlash !== null): ?>
                        <p class="alert-message success-message"><?= htmlspecialchars($successFlash, ENT_QUOTES, 'UTF-8') ?></p>
                    <?php endif; ?>
                    <?php if ($errorFlash !== null): ?>
                        <p class="alert-message error-message"><?= htmlspecialchars($errorFlash, ENT_QUOTES, 'UTF-8') ?></p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            <?php require $viewPath; ?>
        </main>

        <a class="back-to-top-link" href="#top" aria-label="Retour en haut">&uarr;</a>

        <footer class="site-footer">
            <div class="container site-footer-grid">
                <div class="site-footer-brand">
                    <img src="/images/brand/logo-compact.png" alt="Vite & Gourmand">
                    <p>12 rue des Vignes, 33000 Bordeaux</p>
                    <p>Sublimer vos r&eacute;ceptions avec passion et authenticit&eacute; depuis 2001.</p>
                </div>
                <div class="site-footer-column">
                    <p class="footer-title">Horaires</p>
                    <p>Lundi &ndash; Vendredi : 8h &ndash; 18h</p>
                    <p>Samedi : 9h &ndash; 16h</p>
                    <p>Dimanche : Ferm&eacute;</p>
                </div>
                <div class="site-footer-column">
                    <p class="footer-title">Contact</p>
                    <p>05 57 00 00 00</p>
                    <p>contact@viteetgourmand.fr</p>
                </div>
                <div class="site-footer-column">
                    <p class="footer-title">Informations</p>
                    <a href="/mentions-legales">Mentions l&eacute;gales</a>
                    <a href="/cgv">CGV</a>
                    <a href="/confidentialite">Confidentialit&eacute;</a>
                </div>
            </div>
        </footer>
    <?php endif; ?>

    <div class="image-lightbox" data-image-lightbox hidden>
        <button class="image-lightbox-backdrop" type="button" data-image-lightbox-close aria-label="Fermer l'aperçu"></button>
        <figure class="image-lightbox-dialog" role="dialog" aria-modal="true" aria-label="Aperçu de l'image">
            <button class="image-lightbox-close" type="button" data-image-lightbox-close aria-label="Fermer l'aperçu">
                &times;
            </button>
            <img src="" alt="" data-image-lightbox-image>
            <figcaption data-image-lightbox-caption></figcaption>
        </figure>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/app.js?v=<?= $assetVersion('/assets/js/app.js') ?>"></script>
</body>
</html>
