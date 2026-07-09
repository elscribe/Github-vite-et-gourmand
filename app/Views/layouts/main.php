<?php

/**
 * Layout HTML principal.
 *
 * Les vues sont inserees dans cette structure de page partagee afin de garder
 * une configuration Bootstrap 5 commune, un en-tete et un pied de page.
 */

$pageTitle = $pageTitle ?? 'Vite & Gourmand';
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/css/style.css" rel="stylesheet">
</head>
<body class="bg-light">
    <header class="border-bottom bg-white">
        <nav class="navbar navbar-expand-lg container">
            <a class="navbar-brand fw-semibold" href="/">Vite & Gourmand</a>
        </nav>
    </header>

    <main>
        <?php require $viewPath; ?>
    </main>

    <footer class="border-top bg-white py-3">
        <div class="container small text-muted">
            Socle Sprint 0 PHP MVC - aucun parcours metier implemente.
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/app.js"></script>
</body>
</html>
