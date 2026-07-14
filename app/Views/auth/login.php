<section class="auth-page">
    <div class="container auth-container">
        <div class="auth-intro">
            <h1>Connexion</h1>
            <p>Connectez-vous pour suivre vos commandes, laisser un avis ou accéder à votre espace de gestion.</p>
        </div>

        <form class="auth-form auth-card" action="/connexion" method="post" novalidate>
            <input type="hidden" name="_csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">

            <h2>Se connecter</h2>
            <p class="auth-card-lead">Choisissez votre espace puis renseignez vos identifiants.</p>

            <div class="auth-role-switch" aria-label="Type d'espace">
                <button class="auth-role-button is-active" type="button" aria-pressed="true">Client</button>
                <button class="auth-role-button" type="button" aria-pressed="false">Employé</button>
                <button class="auth-role-button" type="button" aria-pressed="false">Admin</button>
            </div>

            <?php if (!empty($errors['credentials'])): ?>
                <p class="alert-message error-message"><?= htmlspecialchars($errors['credentials'], ENT_QUOTES, 'UTF-8') ?></p>
            <?php endif; ?>

            <div class="form-field">
                <label for="email">Email</label>
                <input
                    id="email"
                    name="email"
                    type="email"
                    autocomplete="email"
                    placeholder="maria.dupont@email.com"
                    value="<?= htmlspecialchars($old['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                    required
                >
            </div>

            <div class="form-field">
                <label for="password">Mot de passe</label>
                <input
                    id="password"
                    name="password"
                    type="password"
                    autocomplete="current-password"
                    placeholder="••••••••••••"
                    required
                >
            </div>

            <button class="auth-submit-button" type="submit">Se connecter</button>

            <div class="auth-links">
                <a href="/mot-de-passe/oublie">Mot de passe oublié ?</a>
                <a href="/inscription">Créer un compte client</a>
            </div>
        </form>

        <aside class="auth-role-card" aria-label="Accès selon votre rôle">
            <h2>Accès selon votre rôle</h2>
            <p>Client : suivi de commande et informations personnelles.</p>
            <p>Employé : commandes à traiter, avis et contact client.</p>
            <p>Administrateur : menus, statistiques et gestion interne.</p>
            <em>Les accès internes sont réservés au personnel autorisé.</em>
        </aside>
    </div>
</section>
