<?php
/**
 * @var string $token
 * @var array<string, string> $errors
 */
?>
<section class="password-page">
    <div class="container password-container">
        <div class="password-intro">
            <h1>Réinitialisation</h1>
            <p>Choisissez un nouveau mot de passe conforme aux règles de sécurité.</p>
            <span aria-hidden="true"></span>
        </div>

        <form class="auth-form password-card" action="/mot-de-passe/reinitialisation" method="post" novalidate>
            <input type="hidden" name="_csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
            <input type="hidden" name="token" value="<?= $this->e($token) ?>">

            <h2>Nouveau mot de passe</h2>
            <p>Renseignez votre nouveau mot de passe pour finaliser la réinitialisation.</p>

            <?php if (!empty($errors['token'])): ?>
                <p class="alert-message error-message"><?= $this->e($errors['token']) ?></p>
            <?php endif; ?>

            <div class="form-field">
                <label for="password">Nouveau mot de passe</label>
                <input
                    id="password"
                    name="password"
                    type="password"
                    autocomplete="new-password"
                    placeholder="8 caractères minimum"
                    required
                >
                <?php if (!empty($errors['password'])): ?><p class="form-error"><?= $this->e($errors['password']) ?></p><?php endif; ?>
            </div>

            <div class="form-field">
                <label for="password_confirmation">Confirmation</label>
                <input
                    id="password_confirmation"
                    name="password_confirmation"
                    type="password"
                    autocomplete="new-password"
                    placeholder="Répéter le mot de passe"
                    required
                >
                <?php if (!empty($errors['password_confirmation'])): ?><p class="form-error"><?= $this->e($errors['password_confirmation']) ?></p><?php endif; ?>
            </div>

            <button class="password-submit-button" type="submit">Réinitialiser</button>
            <a class="password-back-link" href="/connexion">Retour à la connexion</a>
        </form>
    </div>
</section>
