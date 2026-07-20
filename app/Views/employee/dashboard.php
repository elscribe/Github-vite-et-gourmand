<?php
/**
 * @var array{orders_to_process: int, revenue_today: float, kitchen_delivery_followups: int, pending_reviews: int} $employeeStats
 * @var list<array<string, mixed>> $ordersToProcess
 * @var list<array<string, mixed>> $reviewsToModerate
 * @var array<string, string> $statusLabels
 */
$backofficeStatusLabels = $statusLabels;
$backofficeStatusLabels['en_attente'] = 'Reçue';
?>
<section class="page-section">
    <div class="container">
        <div class="section-heading">
            <h1>Tableau de bord</h1>
            <p class="muted-text">Les priorit&eacute;s op&eacute;rationnelles pour suivre les commandes et les avis clients.</p>
        </div>

        <div class="stats-grid admin-daily-stats">
            <div class="stat-card">
                <span>Commandes &agrave; traiter</span>
                <strong><?= (int) $employeeStats['orders_to_process'] ?></strong>
            </div>
            <div class="stat-card">
                <span>CA pr&eacute;vu aujourd'hui</span>
                <strong><?= number_format((float) $employeeStats['revenue_today'], 2, ',', ' ') ?> EUR</strong>
            </div>
            <div class="stat-card">
                <span>Pr&eacute;pa / livraison</span>
                <strong><?= (int) $employeeStats['kitchen_delivery_followups'] ?></strong>
            </div>
            <div class="stat-card">
                <span>Avis &agrave; valider</span>
                <strong><?= (int) $employeeStats['pending_reviews'] ?></strong>
            </div>
        </div>

        <div class="admin-dashboard-grid">
            <section class="backoffice-card admin-dashboard-card">
                <div class="admin-dashboard-card-heading">
                    <h2>Derni&egrave;res commandes &agrave; traiter</h2>
                    <a href="/employe/commandes">Voir toutes</a>
                </div>

                <?php if ($ordersToProcess === []): ?>
                    <p class="muted-text">Aucune commande active &agrave; traiter.</p>
                <?php else: ?>
                    <div class="admin-dashboard-list">
                        <?php foreach ($ordersToProcess as $order): ?>
                            <?php
                            $status = (string) $order['statut_actuel'];
                            $orderId = (int) $order['id_commande'];
                            $orderUrl = '/employe/commandes?' . http_build_query([
                                'status' => $status,
                                'id_commande' => (string) $orderId,
                            ]) . '#commande-' . $orderId;
                            $dateLabel = date('d/m/Y', strtotime((string) $order['date_prestation']));
                            $timeLabel = substr((string) $order['heure_livraison'], 0, 5);
                            ?>
                            <article class="admin-dashboard-row">
                                <div>
                                    <strong>#<?= $orderId ?> - <?= $this->e($order['client_prenom']) ?> <?= $this->e($order['client_nom']) ?></strong>
                                    <span><?= $this->e($order['menu_titre']) ?></span>
                                    <small><?= $this->e($dateLabel) ?> &agrave; <?= $this->e($timeLabel) ?> - <?= $this->e($order['ville_livraison']) ?></small>
                                </div>
                                <div class="admin-dashboard-row-actions">
                                    <span class="status-pill status-<?= $this->e(str_replace('_', '-', $status)) ?>">
                                        <?= $this->e($backofficeStatusLabels[$status] ?? $status) ?>
                                    </span>
                                    <a href="<?= htmlspecialchars($orderUrl, ENT_QUOTES, 'UTF-8') ?>">Ouvrir</a>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </section>

            <section class="backoffice-card admin-dashboard-card">
                <div class="admin-dashboard-card-heading">
                    <h2>Avis clients &agrave; valider</h2>
                    <a href="/employe/avis">Voir les avis</a>
                </div>

                <?php if ($reviewsToModerate === []): ?>
                    <p class="muted-text">Aucun avis en attente de validation.</p>
                <?php else: ?>
                    <div class="admin-dashboard-list">
                        <?php foreach ($reviewsToModerate as $review): ?>
                            <article class="admin-dashboard-row">
                                <div>
                                    <strong>#AV<?= str_pad((string) (int) $review['id_avis'], 2, '0', STR_PAD_LEFT) ?> - <?= $this->e($review['prenom']) ?> <?= $this->e($review['nom']) ?></strong>
                                    <span><?= $this->e($review['menu_titre']) ?></span>
                                    <small><?= str_repeat('★', (int) $review['note']) ?><?= str_repeat('☆', max(0, 5 - (int) $review['note'])) ?> - <?= $this->e(date('d/m/Y', strtotime((string) $review['created_at']))) ?></small>
                                </div>
                                <span class="status-pill status-en-attente">&Agrave; valider</span>
                            </article>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </section>
        </div>
    </div>
</section>
