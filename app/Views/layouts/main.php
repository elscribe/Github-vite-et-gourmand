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
$bodyClass = $bodyClass ?? '';
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
    <link href="/assets/css/style.css" rel="stylesheet">
</head>
<body id="top" class="<?= htmlspecialchars($bodyClass, ENT_QUOTES, 'UTF-8') ?>">
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
                        <a class="nav-link" href="/mon-compte">Mon compte</a>
                        <?php if ($currentRole === 'employe' || $currentRole === 'administrateur'): ?>
                            <a class="nav-link" href="/employe">Employe</a>
                        <?php endif; ?>
                        <?php if ($currentRole === 'administrateur'): ?>
                            <a class="nav-link" href="/admin">Admin</a>
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
                <a href="/"<?= $isHomeActive ? ' aria-current="page"' : '' ?>>Accueil</a>
                <a href="/menus"<?= $isMenusActive ? ' aria-current="page"' : '' ?>>Nos menus</a>
                <a href="/contact"<?= $isContactActive ? ' aria-current="page"' : '' ?>>Contact</a>
                <?php if ($isAuthenticated): ?>
                    <a href="/mon-compte">Mon compte</a>
                    <a href="/deconnexion">Deconnexion</a>
                <?php else: ?>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/app.js"></script>
</body>
</html>
