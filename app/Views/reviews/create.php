<?php
/**
 * @var array<string, mixed> $order
 * @var array<string, mixed> $old
 * @var array<string, string> $errors
 */
$cancelHref = '/commandes/' . (int) $order['id_commande'];
?>
<section class="client-page client-review-page">
    <div class="container client-container client-review-container">
        <a class="client-back-link" href="<?= $this->e($cancelHref) ?>">Retour à la commande</a>

        <header class="client-hero client-review-hero">
            <p class="client-kicker">Votre expérience compte</p>
            <h1>Laisser un avis gourmand</h1>
        </header>

        <?php if (!empty($errors['order'])): ?>
            <p class="alert-message error-message"><?= $this->e($errors['order']) ?></p>
        <?php endif; ?>

        <?php require __DIR__ . '/_form.php'; ?>
    </div>
</section>
