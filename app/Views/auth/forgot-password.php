<?php
/**
 * @var array<string, string> $errors
 * @var string|null $resetLink
 */
?>
<section class="password-page">
    <div class="container password-container">
        <div class="password-intro">
            <h1>Mot de passe oublié</h1>
            <p>Demandez un lien de réinitialisation pour accéder à votre compte.</p>
            <span aria-hidden="true"></span>
        </div>

        <form class="auth-form password-card" action="/mot-de-passe/oublie" method="post" novalidate>
            <input type="hidden" name="_csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">

            <h2>Réinitialiser le mot de passe</h2>
            <p>Saisissez votre adresse email. Nous vous enverrons un lien pour réinitialiser votre mot de passe.</p>

            <div class="form-field">
                <label for="email">Email</label>
                <input
                    id="email"
                    name="email"
                    type="email"
                    autocomplete="email"
                    placeholder="jordan@email.com"
                    required
                >
                <?php if (!empty($errors['email'])): ?><p class="form-error"><?= $this->e($errors['email']) ?></p><?php endif; ?>
            </div>

            <p class="password-help-text">Si un compte existe avec cet email, un lien de réinitialisation a été envoyé.</p>

            <?php if (!empty($errors['email'])): ?>
                <p class="password-error-text">Veuillez saisir une adresse email valide.</p>
            <?php endif; ?>

            <?php if ($resetLink !== null): ?>
                <p class="password-demo-link">
                    Lien de démonstration :
                    <a href="<?= $this->e($resetLink) ?>"><?= $this->e($resetLink) ?></a>
                </p>
            <?php endif; ?>

            <button class="password-submit-button" type="submit">Envoyer le lien</button>
            <a class="password-back-link" href="/connexion">Retour à la connexion</a>
        </form>

        <aside class="password-help-card" aria-label="Aide mot de passe">
            <h2>Besoin d'aide ?</h2>
            <p>Si vous ne recevez pas l'email, vérifiez vos spams ou contactez notre support client à Bordeaux.</p>
        </aside>
    </div>
</section>
