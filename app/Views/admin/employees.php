<?php
/**
 * @var list<array<string, mixed>> $employees
 * @var array<string, mixed> $old
 * @var array<string, string> $errors
 */
?>
<section class="page-section">
    <div class="container">
        <a class="back-link" href="/admin">Retour admin</a>

        <div class="section-heading">
            <p class="section-kicker">Administration</p>
            <h1>Comptes employes</h1>
            <p class="muted-text">Un administrateur peut creer ou desactiver un compte employe. Le mot de passe n'est jamais affiche dans l'interface.</p>
        </div>

        <div class="account-layout">
            <form class="auth-form" action="/admin/employes" method="post" novalidate>
                <input type="hidden" name="_csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
                <h2>Nouvel employe</h2>

                <div class="form-field">
                    <label for="email">Email</label>
                    <input id="email" name="email" type="email" value="<?= $this->e($old['email'] ?? '') ?>" required>
                    <?php if (!empty($errors['email'])): ?><p class="form-error"><?= $this->e($errors['email']) ?></p><?php endif; ?>
                </div>

                <div class="form-grid">
                    <div class="form-field">
                        <label for="prenom">Prenom</label>
                        <input id="prenom" name="prenom" type="text" value="<?= $this->e($old['prenom'] ?? '') ?>" required>
                        <?php if (!empty($errors['prenom'])): ?><p class="form-error"><?= $this->e($errors['prenom']) ?></p><?php endif; ?>
                    </div>
                    <div class="form-field">
                        <label for="nom">Nom</label>
                        <input id="nom" name="nom" type="text" value="<?= $this->e($old['nom'] ?? '') ?>" required>
                        <?php if (!empty($errors['nom'])): ?><p class="form-error"><?= $this->e($errors['nom']) ?></p><?php endif; ?>
                    </div>
                </div>

                <div class="form-field">
                    <label for="telephone">Telephone</label>
                    <input id="telephone" name="telephone" type="tel" value="<?= $this->e($old['telephone'] ?? '') ?>" required>
                </div>

                <div class="form-field">
                    <label for="adresse_postale">Adresse</label>
                    <input id="adresse_postale" name="adresse_postale" type="text" value="<?= $this->e($old['adresse_postale'] ?? '') ?>" required>
                </div>

                <div class="form-grid">
                    <div class="form-field">
                        <label for="ville">Ville</label>
                        <input id="ville" name="ville" type="text" value="<?= $this->e($old['ville'] ?? '') ?>" required>
                    </div>
                    <div class="form-field">
                        <label for="pays">Pays</label>
                        <input id="pays" name="pays" type="text" value="<?= $this->e($old['pays'] ?? 'France') ?>" required>
                    </div>
                </div>

                <button class="primary-link" type="submit">Creer et notifier</button>
            </form>

            <aside class="page-panel account-orders">
                <h2>Employes existants</h2>
                <div class="compact-list">
                    <?php foreach ($employees as $employee): ?>
                        <div class="employee-row">
                            <strong><?= $this->e($employee['prenom']) ?> <?= $this->e($employee['nom']) ?></strong>
                            <span><?= $this->e($employee['email']) ?> - <?= ((int) $employee['actif'] === 1) ? 'Actif' : 'Desactive' ?></span>
                            <form action="/admin/employes/<?= (int) $employee['id_utilisateur'] ?>/activation" method="post">
                                <input type="hidden" name="_csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
                                <input type="hidden" name="active" value="<?= ((int) $employee['actif'] === 1) ? '0' : '1' ?>">
                                <button class="secondary-button" type="submit"><?= ((int) $employee['actif'] === 1) ? 'Desactiver' : 'Reactiver' ?></button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                </div>
            </aside>
        </div>
    </div>
</section>
