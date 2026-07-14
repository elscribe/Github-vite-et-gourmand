<?php
/**
 * @var array<string, mixed> $order
 * @var array<string, mixed> $old
 * @var array<string, string> $errors
 */
?>
<section class="page-section">
    <div class="container">
        <a class="back-link" href="/commandes/<?= (int) $order['id_commande'] ?>">Retour a la commande</a>

        <div class="section-heading">
            <p class="section-kicker">Avis client</p>
            <h1>Deposer un avis</h1>
            <p class="muted-text">Votre avis sera visible publiquement apres validation par l'equipe.</p>
        </div>

        <?php if (!empty($errors['order'])): ?>
            <p class="alert-message error-message"><?= $this->e($errors['order']) ?></p>
        <?php endif; ?>

        <form class="auth-form" action="/avis" method="post" novalidate>
            <input type="hidden" name="_csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
            <input type="hidden" name="id_commande" value="<?= (int) $order['id_commande'] ?>">

            <div class="page-panel order-current-menu">
                <strong><?= $this->e($order['menu_titre']) ?></strong>
                <span>Commande #<?= (int) $order['id_commande'] ?></span>
            </div>

            <div class="form-field">
                <label for="note">Note</label>
                <select id="note" name="note" required>
                    <?php for ($note = 5; $note >= 1; $note--): ?>
                        <option value="<?= $note ?>" <?= (int) ($old['note'] ?? 5) === $note ? 'selected' : '' ?>><?= $note ?>/5</option>
                    <?php endfor; ?>
                </select>
                <?php if (!empty($errors['note'])): ?><p class="form-error"><?= $this->e($errors['note']) ?></p><?php endif; ?>
            </div>

            <div class="form-field">
                <label for="commentaire">Commentaire</label>
                <textarea id="commentaire" name="commentaire" rows="6" required><?= $this->e($old['commentaire'] ?? '') ?></textarea>
                <?php if (!empty($errors['commentaire'])): ?><p class="form-error"><?= $this->e($errors['commentaire']) ?></p><?php endif; ?>
            </div>

            <button class="primary-link" type="submit">Envoyer l'avis</button>
        </form>
    </div>
</section>
