<?php
/**
 * @var array<string, mixed> $order
 * @var list<array<string, mixed>> $history
 * @var array<string, string> $statusLabels
 */
$status = (string) $order['statut_actuel'];
$formatDate = static fn (mixed $value): string => $value === null || $value === '' ? '' : date('d/m/Y', strtotime((string) $value));
$progressSteps = [
    'en_attente' => [
        'title' => 'Demande de commande reçue',
        'description' => 'Votre demande a bien été enregistrée et attend le traitement de l’équipe.',
    ],
    'acceptee' => [
        'title' => 'Commande validée par l’équipe',
        'description' => 'La prestation est acceptée. Les modifications autonomes sont désormais verrouillées.',
    ],
    'en_preparation' => [
        'title' => 'Préparation en cours',
        'description' => 'Notre équipe prépare votre menu avec les informations validées.',
    ],
    'en_cours_de_livraison' => [
        'title' => 'Prise en charge par le livreur',
        'description' => 'Votre commande est acheminée vers l’adresse indiquée.',
    ],
    'livre' => [
        'title' => 'Livrée & dégustée',
        'description' => 'La prestation est livrée. L’avis devient disponible après clôture.',
    ],
];
$progressKeys = array_keys($progressSteps);
$currentProgressIndex = array_search($status, $progressKeys, true);
$currentProgressIndex = $currentProgressIndex === false ? count($progressKeys) : $currentProgressIndex;
if ($status === 'terminee') {
    $currentProgressIndex = count($progressKeys) - 1;
}
?>
<section class="client-page client-order-detail-page">
    <div class="container client-container">
        <a class="client-back-link" href="/commandes">Retour aux commandes</a>

        <header class="client-hero client-hero-split">
            <div>
                <p class="client-kicker">Commande #<?= (int) $order['id_commande'] ?></p>
                <h1><?= $this->e($order['menu_titre']) ?></h1>
                <span>Suivez l’avancement de votre prestation et retrouvez les informations utiles à chaque étape.</span>
            </div>
            <span class="client-status-badge status-<?= $this->e($status) ?>"><?= $this->e($statusLabels[$status] ?? $status) ?></span>
        </header>

        <div class="client-order-detail-layout">
            <div class="client-order-detail-main">
                <article class="client-card">
                    <h2>Informations de livraison</h2>
                    <dl class="client-info-list client-detail-grid">
                        <div>
                            <dt>Date de l'événement</dt>
                            <dd><?= $this->e($formatDate($order['date_prestation'])) ?> à <?= $this->e(substr((string) $order['heure_livraison'], 0, 5)) ?></dd>
                        </div>
                        <div>
                            <dt>Adresse de livraison</dt>
                            <dd><?= $this->e($order['adresse_livraison']) ?>, <?= $this->e($order['code_postal_livraison'] ?? '') ?> <?= $this->e($order['ville_livraison']) ?></dd>
                        </div>
                        <div>
                            <dt>Convives</dt>
                            <dd><?= (int) $order['nombre_personnes'] ?> personnes</dd>
                        </div>
                    </dl>
                    <?php if (!empty($order['commentaire_client'])): ?>
                        <div class="client-order-comment">
                            <h3>Demande particulière</h3>
                            <p><?= nl2br($this->e($order['commentaire_client'])) ?></p>
                        </div>
                    <?php endif; ?>
                </article>

                <article class="client-card">
                    <h2>Suivi de votre commande</h2>
                    <div class="client-status-timeline" aria-label="Timeline de statut">
                        <?php foreach ($progressSteps as $key => $step): ?>
                            <?php
                            $index = array_search($key, $progressKeys, true);
                            $isDone = $index !== false && $index < $currentProgressIndex;
                            $isCurrent = ($key === $status) || ($status === 'terminee' && $key === 'livre');
                            ?>
                            <div class="client-status-step<?= $isDone ? ' is-done' : '' ?><?= $isCurrent ? ' is-current' : '' ?>">
                                <span aria-hidden="true">
                                    <?php if ($isDone): ?>
                                        <i class="bi bi-check-lg"></i>
                                    <?php else: ?>
                                        <i class="bi bi-circle-fill"></i>
                                    <?php endif; ?>
                                </span>
                                <div>
                                    <strong><?= $this->e($step['title']) ?></strong>
                                    <p><?= $this->e($step['description']) ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>

                        <?php if ($status === 'annulee'): ?>
                            <div class="client-status-step is-cancelled">
                                <span aria-hidden="true"><i class="bi bi-x-lg"></i></span>
                                <div>
                                    <strong>Annulée</strong>
                                    <p><?= $this->e($order['motif_annulation'] ?: 'La commande a été annulée.') ?></p>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </article>

                <article class="client-card">
                    <h2>Historique détaillé</h2>
                    <ol class="client-event-list">
                        <?php foreach ($history as $event): ?>
                            <li>
                                <strong><?= $this->e($statusLabels[$event['statut']] ?? $event['statut']) ?></strong>
                                <span><?= $this->e($event['created_at']) ?></span>
                                <?php if (!empty($event['commentaire'])): ?>
                                    <p><?= $this->e($event['commentaire']) ?></p>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; ?>
                    </ol>
                </article>
            </div>

            <aside class="client-order-sidebar">
                <article class="client-card client-price-card">
                    <h2>Récapitulatif</h2>
                    <div class="client-price-lines">
                        <div><span>Menu</span><strong><?= number_format((float) $order['prix_menu'], 2, ',', ' ') ?> €</strong></div>
                        <div><span>Remise</span><strong><?= number_format((float) $order['remise'], 2, ',', ' ') ?> €</strong></div>
                        <div><span>Livraison</span><strong><?= number_format((float) $order['prix_livraison'], 2, ',', ' ') ?> €</strong></div>
                        <div class="client-price-total"><span>Total</span><strong><?= number_format((float) $order['prix_total'], 2, ',', ' ') ?> €</strong></div>
                    </div>
                </article>

                <article class="client-card client-help-card">
                    <h2>Besoin d'aide ?</h2>
                    <p>Notre service client est à votre écoute pour adapter votre formule ou répondre à vos questions.</p>
                    <p>Téléphone : 05 57 00 00 00</p>
                    <p>Email : contact@viteetgourmand.fr</p>
                </article>

                <article class="client-card client-actions-card">
                    <h2>Actions</h2>
                    <a class="client-button client-button-secondary" href="/contact">Contacter l'équipe</a>

                    <?php if ($status === 'en_attente'): ?>
                        <a class="client-button client-button-primary" href="/commandes/<?= (int) $order['id_commande'] ?>/modifier">Modifier la commande</a>
                        <form class="client-cancel-form" action="/commandes/<?= (int) $order['id_commande'] ?>/annuler" method="post">
                            <input type="hidden" name="_csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
                            <label for="motif_annulation">Motif d'annulation</label>
                            <textarea id="motif_annulation" name="motif_annulation" rows="3">Annulation demandée par le client.</textarea>
                            <button class="client-button client-button-danger" type="submit">Annuler la commande</button>
                        </form>
                    <?php elseif ($status === 'terminee' && empty($order['avis_id'])): ?>
                        <a class="client-button client-button-primary" href="/avis/creation/<?= (int) $order['id_commande'] ?>">Déposer un avis</a>
                    <?php elseif (!empty($order['avis_id'])): ?>
                        <p class="client-muted">Avis : <?= $this->e($order['avis_statut']) ?></p>
                    <?php else: ?>
                        <div class="client-locked-note">
                            <i class="bi bi-info-circle" aria-hidden="true"></i>
                            <p>La modification et l'annulation ne sont possibles que tant que la commande n'a pas été acceptée.</p>
                        </div>
                    <?php endif; ?>
                </article>
            </aside>
        </div>
    </div>
</section>
