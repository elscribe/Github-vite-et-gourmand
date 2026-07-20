<?php

/**
 * @var array<string, string> $errors
 * @var array<string, string> $old
 * @var bool $success
 */
?>
<section class="contact-page">
    <div class="container contact-container">
        <header class="contact-hero">
            <p>Visiteur / client</p>
            <h1>Contactez Vite &amp; Gourmand</h1>
            <span>Une question sur un menu, une commande ou une réception privée ?</span>
            <span>Notre équipe vous répond sous 24h ouvrées.</span>
        </header>

        <div class="contact-layout">
            <aside class="contact-info-card" aria-label="Coordonnées">
                <h2>Coordonnées</h2>
                <dl>
                    <div>
                        <dt>Adresse</dt>
                        <dd>12 rue des Vignes, 33000 Bordeaux</dd>
                    </div>
                    <div>
                        <dt>Téléphone</dt>
                        <dd>05 57 00 00 00</dd>
                    </div>
                    <div>
                        <dt>Email</dt>
                        <dd>contact@viteetgourmand.fr</dd>
                    </div>
                    <div>
                        <dt>Horaires</dt>
                        <dd>Lundi - Vendredi : 8h - 18h</dd>
                        <dd>Samedi : 9h - 16h</dd>
                    </div>
                </dl>
            </aside>

            <form class="contact-form contact-form-card" method="post" action="/contact" novalidate>
                <input type="hidden" name="_csrf_token" value="<?= $this->e($csrfToken) ?>">

                <h2>
                    <span class="contact-title-desktop">Envoyer un message</span>
                    <span class="contact-title-mobile">Message</span>
                </h2>

                <?php if ($success): ?>
                    <div class="alert-message success-message" role="status">
                        Votre message a bien été envoyé.
                    </div>
                <?php endif; ?>

                <div class="contact-form-grid">
                    <div class="form-field">
                        <label for="nom">Nom *</label>
                        <input
                            id="nom"
                            name="nom"
                            type="text"
                            maxlength="120"
                            placeholder="Marie Dupont"
                            value="<?= $this->e($old['nom'] ?? '') ?>"
                            aria-describedby="<?= isset($errors['nom']) ? 'nom-error' : '' ?>"
                            required
                        >
                        <?php if (isset($errors['nom'])): ?>
                            <p class="form-error" id="nom-error"><?= $this->e($errors['nom']) ?></p>
                        <?php endif; ?>
                    </div>

                    <div class="form-field">
                        <label for="email">Email *</label>
                        <input
                            id="email"
                            name="email"
                            type="email"
                            maxlength="120"
                            placeholder="marie@email.fr"
                            value="<?= $this->e($old['email'] ?? '') ?>"
                            aria-describedby="<?= isset($errors['email']) ? 'email-error' : '' ?>"
                            required
                        >
                        <?php if (isset($errors['email'])): ?>
                            <p class="form-error" id="email-error"><?= $this->e($errors['email']) ?></p>
                        <?php endif; ?>
                    </div>

                    <div class="form-field contact-phone-field">
                        <label for="telephone">Téléphone</label>
                        <input
                            id="telephone"
                            name="telephone"
                            type="tel"
                            maxlength="30"
                            placeholder="06 12 34 56 78"
                            value="<?= $this->e($old['telephone'] ?? '') ?>"
                        >
                    </div>

                    <div class="form-field">
                        <label for="titre">Sujet *</label>
                        <input
                            id="titre"
                            name="titre"
                            type="text"
                            maxlength="160"
                            placeholder="Question sur un menu"
                            value="<?= $this->e($old['titre'] ?? '') ?>"
                            aria-describedby="<?= isset($errors['titre']) ? 'titre-error' : '' ?>"
                            required
                        >
                        <?php if (isset($errors['titre'])): ?>
                            <p class="form-error" id="titre-error"><?= $this->e($errors['titre']) ?></p>
                        <?php endif; ?>
                    </div>

                    <div class="form-field contact-message-field">
                        <label for="description">Message *</label>
                        <textarea
                            id="description"
                            name="description"
                            rows="6"
                            placeholder="Bonjour, je souhaite obtenir des informations pour une réception..."
                            aria-describedby="<?= isset($errors['description']) ? 'description-error' : '' ?>"
                            required
                        ><?= $this->e($old['description'] ?? '') ?></textarea>
                        <?php if (isset($errors['description'])): ?>
                            <p class="form-error" id="description-error"><?= $this->e($errors['description']) ?></p>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="contact-form-actions">
                    <button class="contact-submit-button" type="submit">
                        <span class="contact-title-desktop">Envoyer le message</span>
                        <span class="contact-title-mobile">Envoyer</span>
                    </button>
                    <p>Les champs marqués d’un astérisque sont obligatoires.</p>
                </div>
            </form>
        </div>
    </div>
</section>
