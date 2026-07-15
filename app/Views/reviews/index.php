<?php
/**
 * @var array<string, mixed>|null $reviewableOrder
 * @var list<array<string, mixed>> $reviews
 * @var array<string, mixed> $old
 * @var array<string, string> $errors
 */
$formatDate = static fn (mixed $value): string => $value === null || $value === '' ? '' : date('d/m/Y', strtotime((string) $value));
$reviewStatusLabels = [
    'en_attente' => 'En attente',
    'valide' => 'Validé',
    'refuse' => 'Refusé',
];
?>
<section class="client-page client-review-page">
    <div class="container client-container client-review-container">
        <a class="client-back-link" href="/mon-compte">Retour à mon espace</a>

        <header class="client-hero client-review-hero">
            <p class="client-kicker">Votre expérience compte</p>
            <h1>Laisser un avis gourmand</h1>
        </header>

        <?php if ($reviewableOrder !== null): ?>
            <?php
            $order = $reviewableOrder;
            $cancelHref = '/mon-compte';
            require __DIR__ . '/_form.php';
            ?>
        <?php else: ?>
            <article class="client-card client-review-empty-card">
                <span class="client-review-empty-icon" aria-hidden="true"><i class="bi bi-star"></i></span>
                <p class="client-kicker">Aucune prestation à noter</p>
                <h2>Votre prochain avis apparaîtra ici</h2>
                <p>Le formulaire sera disponible après une commande livrée, clôturée par l'équipe et pas encore évaluée.</p>
                <a class="client-button client-button-primary" href="/commandes">Voir mes commandes</a>
            </article>
        <?php endif; ?>

        <?php if ($reviews !== []): ?>
            <section class="client-review-history" aria-labelledby="review-history-title">
                <div class="client-card-heading">
                    <h2 id="review-history-title">Avis envoyés</h2>
                    <span><?= count($reviews) ?> avis</span>
                </div>

                <div class="client-review-history-list">
                    <?php foreach ($reviews as $review): ?>
                        <?php $status = (string) $review['statut']; ?>
                        <article class="client-card client-review-history-card">
                            <div class="client-card-heading">
                                <div>
                                    <h3><?= $this->e($review['menu_titre']) ?></h3>
                                    <p>Prestation du <?= $this->e($formatDate($review['date_prestation'])) ?></p>
                                </div>
                                <span class="client-status-badge status-<?= $this->e($status) ?>"><?= $this->e($reviewStatusLabels[$status] ?? $status) ?></span>
                            </div>
                            <div class="client-review-history-stars" aria-label="Note <?= (int) $review['note'] ?> sur 5">
                                <?php for ($note = 1; $note <= 5; $note++): ?>
                                    <i class="bi <?= $note <= (int) $review['note'] ? 'bi-star-fill' : 'bi-star' ?>" aria-hidden="true"></i>
                                <?php endfor; ?>
                            </div>
                            <p><?= $this->e($review['commentaire']) ?></p>
                        </article>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endif; ?>
    </div>
</section>
