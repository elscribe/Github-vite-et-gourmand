<?php
/**
 * @var array<string, mixed>|null $user
 * @var array<string, string> $errors
 * @var bool $success
 */
$contactPreference = (string) ($user['canal_contact_prefere'] ?? 'email');
?>
<section class="client-page client-profile-edit-page">
    <div class="container client-container">
        <a class="client-back-link" href="/mon-compte">Retour à mon espace</a>

        <header class="client-hero client-profile-edit-hero">
            <p class="client-kicker">Mon espace personnel</p>
            <h1>Modifier mes informations</h1>
            <span>Mettez à jour vos coordonnées personnelles et préférences pour simplifier la planification de vos prochaines réceptions.</span>
            <em>Cette page est accessible uniquement lorsque l'utilisateur est connecté.</em>
        </header>

        <div class="client-profile-alerts" aria-live="polite">
            <?php if ($success): ?>
                <p class="client-profile-alert client-profile-alert-success">
                    <i class="bi bi-check-circle" aria-hidden="true"></i>
                    Vos informations ont été mises à jour.
                </p>
            <?php endif; ?>

            <?php if ($errors !== []): ?>
                <p class="client-profile-alert client-profile-alert-error">
                    <i class="bi bi-exclamation-triangle" aria-hidden="true"></i>
                    Une erreur est survenue. Veuillez vérifier les champs.
                </p>
            <?php endif; ?>
        </div>

        <div class="client-profile-edit-layout">
            <form class="client-form-card client-profile-edit-card" action="/mon-compte/modifier" method="post" novalidate>
                <input type="hidden" name="_csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">

                <h2>Données de contact &amp; Facturation</h2>

                <div class="client-form-grid">
                    <div class="form-field">
                        <label for="nom">Nom</label>
                        <input id="nom" name="nom" type="text" value="<?= $this->e($user['nom'] ?? '') ?>" required>
                        <?php if (!empty($errors['nom'])): ?><p class="form-error"><?= $this->e($errors['nom']) ?></p><?php endif; ?>
                    </div>

                    <div class="form-field">
                        <label for="prenom">Prénom</label>
                        <input id="prenom" name="prenom" type="text" value="<?= $this->e($user['prenom'] ?? '') ?>" required>
                        <?php if (!empty($errors['prenom'])): ?><p class="form-error"><?= $this->e($errors['prenom']) ?></p><?php endif; ?>
                    </div>

                    <div class="form-field">
                        <label for="email_display">Email</label>
                        <input id="email_display" type="email" value="<?= $this->e($user['email'] ?? '') ?>" disabled>
                    </div>

                    <div class="form-field">
                        <label for="telephone">Téléphone</label>
                        <input id="telephone" name="telephone" type="tel" value="<?= $this->e($user['telephone'] ?? '') ?>" required>
                        <?php if (!empty($errors['telephone'])): ?><p class="form-error"><?= $this->e($errors['telephone']) ?></p><?php endif; ?>
                    </div>

                    <div class="form-field client-field-wide">
                        <label for="adresse_postale">Adresse principale</label>
                        <input id="adresse_postale" name="adresse_postale" type="text" value="<?= $this->e($user['adresse_postale'] ?? '') ?>" required>
                        <?php if (!empty($errors['adresse_postale'])): ?><p class="form-error"><?= $this->e($errors['adresse_postale']) ?></p><?php endif; ?>
                    </div>

                    <div class="form-field">
                        <label for="ville">Ville</label>
                        <input id="ville" name="ville" type="text" value="<?= $this->e($user['ville'] ?? '') ?>" required>
                        <?php if (!empty($errors['ville'])): ?><p class="form-error"><?= $this->e($errors['ville']) ?></p><?php endif; ?>
                    </div>

                    <div class="form-field">
                        <label for="pays">Pays</label>
                        <input id="pays" name="pays" type="text" value="<?= $this->e($user['pays'] ?? 'France') ?>" required>
                        <?php if (!empty($errors['pays'])): ?><p class="form-error"><?= $this->e($errors['pays']) ?></p><?php endif; ?>
                    </div>
                </div>

                <fieldset class="client-contact-preference">
                    <legend>Canal de contact préféré pour le suivi de commande</legend>
                    <label>
                        <input type="radio" name="canal_contact_prefere" value="email" <?= $contactPreference === 'email' ? 'checked' : '' ?>>
                        <span>Email préféré</span>
                    </label>
                    <label>
                        <input type="radio" name="canal_contact_prefere" value="telephone" <?= $contactPreference === 'telephone' ? 'checked' : '' ?>>
                        <span>Appel si modification</span>
                    </label>
                    <?php if (!empty($errors['canal_contact_prefere'])): ?><p class="form-error"><?= $this->e($errors['canal_contact_prefere']) ?></p><?php endif; ?>
                </fieldset>

                <div class="client-form-actions">
                    <button class="client-button client-button-primary" type="submit">Enregistrer les modifications</button>
                    <a class="client-button client-button-secondary" href="/mon-compte">Annuler</a>
                </div>
            </form>

            <aside class="client-card client-profile-security-card">
                <h2>Sécurité &amp; Confidentialité</h2>
                <p>Vos informations personnelles sont uniquement utilisées pour l'organisation de vos réceptions avec Vite &amp; Gourmand. Nous ne revendons pas vos données à des tiers.</p>
                <hr>
                <p><em>Pour modifier vos identifiants de sécurité ou demander la suppression définitive de votre espace client, veuillez nous contacter directement.</em></p>
            </aside>
        </div>
    </div>
</section>
