<?php
/**
 * @var list<array<string, mixed>> $reviews
 */
?>
<section class="page-section">
    <div class="container">
        <div class="section-heading">
            <p class="section-kicker">Espace employe</p>
            <h1>Moderation des avis</h1>
            <p class="muted-text">Seuls les avis valides apparaissent sur la page d'accueil.</p>
        </div>

        <?php if ($reviews === []): ?>
            <div class="page-panel">
                <p>Aucun avis a moderer.</p>
            </div>
        <?php else: ?>
            <div class="employee-order-list">
                <?php foreach ($reviews as $review): ?>
                    <article class="employee-order-card review-card">
                        <div>
                            <p class="section-kicker">Avis #<?= (int) $review['id_avis'] ?> - <?= $this->e($review['statut']) ?></p>
                            <h2><?= $this->e($review['menu_titre']) ?></h2>
                            <p><?= $this->e($review['prenom']) ?> <?= $this->e($review['nom']) ?> - <?= $this->e($review['email']) ?></p>
                            <p class="review-rating"><?= str_repeat('*', (int) $review['note']) ?> (<?= (int) $review['note'] ?>/5)</p>
                            <p><?= $this->e($review['commentaire']) ?></p>
                        </div>

                        <form action="/employe/avis/<?= (int) $review['id_avis'] ?>/moderation" method="post" class="employee-action-form">
                            <input type="hidden" name="_csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
                            <label for="statut-<?= (int) $review['id_avis'] ?>">Decision</label>
                            <select id="statut-<?= (int) $review['id_avis'] ?>" name="statut">
                                <option value="valide">Valider</option>
                                <option value="refuse">Refuser</option>
                            </select>
                            <button class="primary-link" type="submit">Enregistrer</button>
                        </form>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
