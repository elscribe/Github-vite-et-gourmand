<section class="auth-page">
    <div class="container auth-container">
        <div class="auth-intro">
            <h1>Connexion</h1>
            <p>Connectez-vous pour suivre vos commandes, laisser un avis ou accéder à votre espace de gestion.</p>
        </div>

        <form class="auth-form auth-card" action="/connexion" method="post" novalidate>
            <input type="hidden" name="_csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">

            <h2>Se connecter</h2>
            <p class="auth-card-lead">Renseignez vos identifiants pour vous connecter.</p>

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
            <h2>Un seul accès sécurisé</h2>
            <p>Connectez-vous avec votre adresse email. Votre espace s'ouvre automatiquement selon votre profil.</p>
        </aside>
    </div>
</section>
