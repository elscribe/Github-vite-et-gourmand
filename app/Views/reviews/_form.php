<?php
/**
 * @var array<string, mixed> $order
 * @var array<string, mixed> $old
 * @var array<string, string> $errors
 * @var string $cancelHref
 */
$selectedNote = (int) ($old['note'] ?? 4);
$formatDate = static fn (mixed $value): string => $value === null || $value === '' ? '' : date('d/m/Y', strtotime((string) $value));
$deliveredAt = $formatDate($order['date_prestation'] ?? '');
?>
<form class="client-form-card client-review-form" action="/avis" method="post" novalidate>
    <input type="hidden" name="_csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
    <input type="hidden" name="id_commande" value="<?= (int) $order['id_commande'] ?>">

    <div class="client-reviewed-order">
        <p>Prestation dégustée</p>
        <strong>
            <?= $this->e($order['menu_titre']) ?>
            <?php if ($deliveredAt !== ''): ?>
                - Livré le <?= $this->e($deliveredAt) ?>
            <?php endif; ?>
        </strong>
    </div>

    <span class="client-status-badge client-review-delivered-badge">
        <i class="bi bi-check-circle" aria-hidden="true"></i>
        Livrée
    </span>

    <fieldset class="client-rating-field">
        <legend>Note de satisfaction</legend>
        <div class="client-rating-options" data-client-rating>
            <?php for ($note = 1; $note <= 5; $note++): ?>
                <input id="note-<?= $note ?>" name="note" type="radio" value="<?= $note ?>" <?= $selectedNote === $note ? 'checked' : '' ?>>
                <label for="note-<?= $note ?>" data-rating-value="<?= $note ?>" aria-label="<?= $note ?> sur 5">
                    <i class="bi bi-star" aria-hidden="true"></i>
                </label>
            <?php endfor; ?>
        </div>
        <?php if (!empty($errors['note'])): ?><p class="form-error"><?= $this->e($errors['note']) ?></p><?php endif; ?>
    </fieldset>

    <div class="form-field client-review-comment-field">
        <label for="commentaire">Votre commentaire</label>
        <textarea id="commentaire" name="commentaire" rows="6" placeholder="Qu'avez-vous pensé de l'harmonie des plats, de la cuisson ou de la ponctualité de la livraison ?" required><?= $this->e($old['commentaire'] ?? '') ?></textarea>
        <?php if (!empty($errors['commentaire'])): ?><p class="form-error"><?= $this->e($errors['commentaire']) ?></p><?php endif; ?>
    </div>

    <div class="client-review-validation">
        <p>
            <i class="bi bi-info-circle" aria-hidden="true"></i>
            <span>Information : Votre avis sera validé par l'équipe de modération de Vite &amp; Gourmand avant sa publication sur notre site.</span>
        </p>
        <small>Avis possible uniquement après une commande livrée.</small>
    </div>

    <div class="client-form-actions client-review-actions">
        <button class="client-button client-button-accent" type="submit">Envoyer mon avis</button>
        <a class="client-button client-button-outline-primary" href="<?= $this->e($cancelHref) ?>">Annuler</a>
    </div>
</form>
