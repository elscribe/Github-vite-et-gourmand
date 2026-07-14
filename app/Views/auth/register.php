<section class="register-page">
    <div class="container register-container">
        <header class="register-hero">
            <p>Visiteur -> client fidélisé</p>
            <h1>Créer mon espace gourmand</h1>
            <span>Un espace personnel permet de suivre les commandes, modifier ses informations et laisser un avis après prestation.</span>
        </header>

        <div class="register-layout">
        <form class="auth-form register-card" action="/inscription" method="post" novalidate>
            <input type="hidden" name="_csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
            <input type="hidden" name="ville" value="<?= htmlspecialchars($old['ville'] ?? 'Bordeaux', ENT_QUOTES, 'UTF-8') ?>">
            <input type="hidden" name="pays" value="<?= htmlspecialchars($old['pays'] ?? 'France', ENT_QUOTES, 'UTF-8') ?>">

            <h2>Informations personnelles</h2>

            <div class="register-form-grid">
                <div class="form-field">
                    <label for="nom">Nom *</label>
                    <input
                        id="nom"
                        name="nom"
                        type="text"
                        autocomplete="family-name"
                        placeholder="Dupont"
                        value="<?= htmlspecialchars($old['nom'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                        required
                    >
                    <?php if (!empty($errors['nom'])): ?>
                        <p class="form-error"><?= htmlspecialchars($errors['nom'], ENT_QUOTES, 'UTF-8') ?></p>
                    <?php endif; ?>
                </div>

                <div class="form-field">
                    <label for="prenom">Prénom *</label>
                    <input
                        id="prenom"
                        name="prenom"
                        type="text"
                        autocomplete="given-name"
                        placeholder="Marie"
                        value="<?= htmlspecialchars($old['prenom'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                        required
                    >
                    <?php if (!empty($errors['prenom'])): ?>
                        <p class="form-error"><?= htmlspecialchars($errors['prenom'], ENT_QUOTES, 'UTF-8') ?></p>
                    <?php endif; ?>
                </div>

                <div class="form-field">
                    <label for="email">Email *</label>
                    <input
                        id="email"
                        name="email"
                        type="email"
                        autocomplete="email"
                        placeholder="marie.dupont@email.com"
                        value="<?= htmlspecialchars($old['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                        required
                    >
                    <?php if (!empty($errors['email'])): ?>
                        <p class="form-error"><?= htmlspecialchars($errors['email'], ENT_QUOTES, 'UTF-8') ?></p>
                    <?php endif; ?>
                </div>

                <div class="form-field">
                    <label for="telephone">Téléphone *</label>
                    <input
                        id="telephone"
                        name="telephone"
                        type="tel"
                        autocomplete="tel"
                        placeholder="06 12 34 56 78"
                        value="<?= htmlspecialchars($old['telephone'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                        required
                    >
                    <?php if (!empty($errors['telephone'])): ?>
                        <p class="form-error"><?= htmlspecialchars($errors['telephone'], ENT_QUOTES, 'UTF-8') ?></p>
                    <?php endif; ?>
                </div>

                <div class="form-field register-full-field">
                    <label for="adresse_postale">Adresse *</label>
                    <input
                        id="adresse_postale"
                        name="adresse_postale"
                        type="text"
                        autocomplete="street-address"
                        placeholder="12 rue des Vignes, 33000 Bordeaux"
                        value="<?= htmlspecialchars($old['adresse_postale'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                        required
                    >
                    <?php if (!empty($errors['adresse_postale'])): ?>
                        <p class="form-error"><?= htmlspecialchars($errors['adresse_postale'], ENT_QUOTES, 'UTF-8') ?></p>
                    <?php endif; ?>
                </div>

                <div class="form-field">
                    <label for="password">Mot de passe *</label>
                    <input
                        id="password"
                        name="password"
                        type="password"
                        autocomplete="new-password"
                        placeholder="8 caractères minimum"
                        required
                    >
                    <?php if (!empty($errors['password'])): ?>
                        <p class="form-error"><?= htmlspecialchars($errors['password'], ENT_QUOTES, 'UTF-8') ?></p>
                    <?php endif; ?>
                </div>

                <div class="form-field">
                    <label for="password_confirmation">Confirmation *</label>
                    <input
                        id="password_confirmation"
                        name="password_confirmation"
                        type="password"
                        autocomplete="new-password"
                        placeholder="Répéter le mot de passe"
                        required
                    >
                    <?php if (!empty($errors['password_confirmation'])): ?>
                        <p class="form-error"><?= htmlspecialchars($errors['password_confirmation'], ENT_QUOTES, 'UTF-8') ?></p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="register-actions">
                <button class="register-submit-button" type="submit">Créer mon compte</button>
                <a class="register-login-link" href="/connexion">Déjà un compte</a>
            </div>
        </form>

            <aside class="register-benefit-card" aria-label="Pourquoi créer un compte">
                <h2>Pourquoi créer un compte ?</h2>
                <ul>
                    <li>Suivre l’avancement d’une commande</li>
                    <li>Retrouver son historique</li>
                    <li>Contacter facilement l’équipe</li>
                    <li>Laisser un avis validé après livraison</li>
                </ul>
                <p>Vos informations restent utilisées uniquement pour la gestion de vos prestations Vite &amp; Gourmand.</p>
            </aside>
        </div>
    </div>
</section>
