<?php
/**
 * @var list<array<string, mixed>> $reviews
 */
$reviewManagementBasePath = $reviewManagementBasePath ?? '/employe/avis';
$statusLabels = [
    'en_attente' => '&Agrave; valider',
    'valide' => 'Valid&eacute;',
    'refuse' => 'Refus&eacute;',
];
?>
<section class="page-section">
    <div class="container">
        <div class="section-heading">
            <h1>Mod&eacute;ration des avis</h1>
            <p class="muted-text">Seuls les avis valid&eacute;s apparaissent sur la page d'accueil.</p>
        </div>

        <?php if ($reviews === []): ?>
            <div class="page-panel">
                <p>Aucun avis &agrave; mod&eacute;rer.</p>
            </div>
        <?php else: ?>
            <div class="employee-review-list">
                <?php foreach ($reviews as $review): ?>
                    <?php $status = (string) $review['statut']; ?>
                    <article class="employee-review-card">
                        <div class="employee-review-content">
                            <div class="employee-review-heading">
                                <p>
                                    <strong>#AV<?= str_pad((string) (int) $review['id_avis'], 2, '0', STR_PAD_LEFT) ?></strong>
                                    <?= $this->e($review['prenom']) ?> <?= $this->e($review['nom']) ?>
                                    <span>(<?= $this->e($review['email']) ?>)</span>
                                </p>
                                <span class="status-pill status-<?= $this->e(str_replace('_', '-', $status)) ?>">
                                    <?= $statusLabels[$status] ?? $this->e($status) ?>
                                </span>
                            </div>
                            <p class="employee-review-menu">Menu concern&eacute; : <strong><?= $this->e($review['menu_titre']) ?></strong></p>
                            <p class="employee-review-comment">"<?= $this->e($review['commentaire']) ?>"</p>
                            <p class="review-rating" aria-label="Note <?= (int) $review['note'] ?> sur 5">
                                <?= str_repeat('★', (int) $review['note']) ?><?= str_repeat('☆', max(0, 5 - (int) $review['note'])) ?>
                            </p>
                        </div>

                        <?php if ($status === 'en_attente'): ?>
                            <div class="employee-review-actions">
                                <form action="<?= htmlspecialchars($reviewManagementBasePath . '/' . (int) $review['id_avis'] . '/moderation', ENT_QUOTES, 'UTF-8') ?>" method="post">
                                    <input type="hidden" name="_csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
                                    <input type="hidden" name="statut" value="valide">
                                    <button class="review-approve-button" type="submit">
                                        <i class="bi bi-check-lg" aria-hidden="true"></i>
                                        Approuver l'avis
                                    </button>
                                </form>
                                <form action="<?= htmlspecialchars($reviewManagementBasePath . '/' . (int) $review['id_avis'] . '/moderation', ENT_QUOTES, 'UTF-8') ?>" method="post">
                                    <input type="hidden" name="_csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
                                    <input type="hidden" name="statut" value="refuse">
                                    <button class="review-reject-button" type="submit">
                                        <i class="bi bi-x-lg" aria-hidden="true"></i>
                                        Rejeter l'avis
                                    </button>
                                </form>
                            </div>
                        <?php endif; ?>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
