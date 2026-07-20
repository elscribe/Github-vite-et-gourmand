<?php
/**
 * @var array<string, mixed>|null $user
 */
$fullAddress = trim((string) ($user['adresse_postale'] ?? '') . ', ' . (string) ($user['ville'] ?? ''), ', ');
$contactPreference = (string) ($user['canal_contact_prefere'] ?? 'email');
?>
<section class="client-page client-profile-page">
    <div class="container client-container">
        <a class="client-back-link" href="/mon-compte">Retour à mon espace</a>

        <header class="client-hero client-profile-hero">
            <p class="client-kicker">Mon espace personnel</p>
            <h1>Mon profil</h1>
            <span>Retrouvez vos coordonnées et vos préférences de contact pour faciliter le suivi de vos commandes.</span>
        </header>

        <div class="client-profile-grid">
            <article class="client-card client-profile-card">
                <div class="client-card-heading">
                    <h2>Informations client</h2>
                    <a class="client-button client-button-secondary" href="/mon-compte/modifier">Modifier</a>
                </div>

                <dl class="client-info-list client-info-grid">
                    <div>
                        <dt>Nom :</dt>
                        <dd><?= $this->e($user['nom'] ?? '') ?></dd>
                    </div>
                    <div>
                        <dt>Prénom :</dt>
                        <dd><?= $this->e($user['prenom'] ?? '') ?></dd>
                    </div>
                    <div>
                        <dt>Email :</dt>
                        <dd><?= $this->e($user['email'] ?? '') ?></dd>
                    </div>
                    <div>
                        <dt>Téléphone :</dt>
                        <dd><?= $this->e($user['telephone'] ?? '') ?></dd>
                    </div>
                    <div class="client-info-wide">
                        <dt>Adresse :</dt>
                        <dd><?= $this->e($fullAddress) ?></dd>
                    </div>
                </dl>
            </article>

            <aside class="client-card client-profile-preferences-card">
                <h2>Préférences</h2>
                <div class="client-preference-tags">
                    <span class="<?= $contactPreference === 'email' ? 'is-active' : '' ?>">Email préféré</span>
                    <span class="<?= $contactPreference === 'telephone' ? 'is-active' : '' ?>">Appel si modification</span>
                </div>
                <p>Ces informations restent réservées à l'organisation et au suivi de vos prestations.</p>
            </aside>
        </div>
    </div>
</section>
