<?php
/**
 * @var array<string, mixed>|null $user
 * @var array<string, mixed>|null $currentOrder
 * @var array<string, mixed>|null $reviewableOrder
 * @var list<array<string, mixed>> $orders
 * @var array<string, string> $statusLabels
 */
$statusSteps = [
    'en_attente' => 'Réceptionnée',
    'acceptee' => 'Acceptée',
    'en_preparation' => 'En préparation',
    'en_cours_de_livraison' => 'Livraison',
    'livre' => 'Livrée',
];
$stepKeys = array_keys($statusSteps);
$activeStatus = (string) ($currentOrder['statut_actuel'] ?? 'en_attente');
$activeIndex = array_search($activeStatus, $stepKeys, true);
$activeIndex = $activeIndex === false ? 0 : $activeIndex;
$fullAddress = trim((string) ($user['adresse_postale'] ?? '') . ', ' . (string) ($user['ville'] ?? ''), ', ');
$formatDate = static fn (mixed $value): string => $value === null || $value === '' ? '' : date('d/m/Y', strtotime((string) $value));
$formatOrderNumber = static function (array $order): string {
    $rawDate = (string) ($order['date_prestation'] ?? $order['date_commande'] ?? 'now');
    $timestamp = strtotime($rawDate) ?: time();

    return '#CMD-' . date('Y', $timestamp) . '-' . str_pad((string) (int) $order['id_commande'], 4, '0', STR_PAD_LEFT);
};
$displayedOrders = array_slice($orders, 0, 3);
$firstName = trim((string) ($user['prenom'] ?? ''));
$lastName = trim((string) ($user['nom'] ?? ''));
$initials = strtoupper(substr($firstName, 0, 1) . substr($lastName, 0, 1));
$initials = $initials === '' ? 'VG' : $initials;
$ordersCount = count($orders);
?>
<section class="client-page client-account-page">
    <div class="container client-container">
        <header class="client-hero">
            <h1>Mon espace gourmand</h1>
            <span>Bonjour <?= $this->e($user['prenom'] ?? 'client') ?> ! Bienvenue dans votre espace personnel. Retrouvez ici vos commandes et vos informations.</span>
        </header>

        <div class="client-account-layout">
            <aside class="client-account-sidebar" aria-label="Navigation de l'espace client">
                <div class="client-account-sidebar-card">
                    <div class="client-account-user-card">
                        <span class="client-account-avatar" aria-hidden="true"><?= $this->e($initials) ?></span>
                        <div>
                            <strong><?= $this->e(trim($firstName . ' ' . $lastName)) ?></strong>
                            <small><?= $this->e($user['email'] ?? '') ?></small>
                        </div>
                    </div>

                    <nav class="client-account-nav" data-client-account-nav>
                        <a class="is-active" href="#mes-commandes" aria-current="page" data-account-nav-link>
                            <i class="bi bi-bag-check" aria-hidden="true"></i>
                            <span>Mes commandes</span>
                            <em><?= $ordersCount ?></em>
                        </a>
                        <a href="/mon-compte/profil">
                            <i class="bi bi-person" aria-hidden="true"></i>
                            <span>Mes informations</span>
                        </a>
                        <a href="/avis">
                            <i class="bi bi-star" aria-hidden="true"></i>
                            <span>Mes avis</span>
                            <?php if ($reviewableOrder !== null): ?>
                                <em>1</em>
                            <?php endif; ?>
                        </a>
                        <a class="client-account-nav-logout" href="/deconnexion">
                            <i class="bi bi-box-arrow-right" aria-hidden="true"></i>
                            <span>Déconnexion</span>
                        </a>
                    </nav>
                </div>
            </aside>

            <div class="client-account-content">
                <section id="mes-commandes" class="client-account-section">
                    <article class="client-card client-current-order client-account-current">
                        <div class="client-card-heading">
                            <h2>
                                Commande en cours
                                <?php if ($currentOrder !== null): ?>
                                    <span>— <?= $this->e($formatOrderNumber($currentOrder)) ?></span>
                                <?php endif; ?>
                            </h2>
                            <?php if ($currentOrder !== null): ?>
                                <span class="client-status-badge status-<?= $this->e($activeStatus) ?>"><?= $this->e($statusLabels[$activeStatus] ?? $activeStatus) ?></span>
                            <?php endif; ?>
                        </div>

                        <?php if ($currentOrder === null): ?>
                            <div class="client-empty-state">
                                <p>Aucune commande active pour le moment.</p>
                                <a class="client-button client-button-primary" href="/menus">Découvrir les menus</a>
                            </div>
                        <?php else: ?>
                            <dl class="client-current-summary">
                                <div>
                                    <dt>Menu</dt>
                                    <dd><?= $this->e($currentOrder['menu_titre']) ?> — <?= (int) $currentOrder['nombre_personnes'] ?> personnes</dd>
                                </div>
                                <div>
                                    <dt>Date de l'événement</dt>
                                    <dd><?= $this->e($formatDate($currentOrder['date_prestation'])) ?></dd>
                                </div>
                                <div>
                                    <dt>Heure de livraison</dt>
                                    <dd><?= $this->e(substr((string) $currentOrder['heure_livraison'], 0, 5)) ?></dd>
                                </div>
                                <div class="client-current-address">
                                    <dt>Adresse</dt>
                                    <dd><?= $this->e($currentOrder['adresse_livraison']) ?>, <?= $this->e($currentOrder['code_postal_livraison'] ?? '') ?> <?= $this->e($currentOrder['ville_livraison']) ?></dd>
                                </div>
                            </dl>

                            <div class="client-progress client-account-progress" aria-label="Avancement de la commande">
                                <?php foreach ($statusSteps as $key => $label): ?>
                                    <?php
                                    $index = array_search($key, $stepKeys, true);
                                    $isDone = $index !== false && $index < $activeIndex;
                                    $isCurrent = $key === $activeStatus;
                                    ?>
                                    <div class="client-progress-step<?= $isDone ? ' is-done' : '' ?><?= $isCurrent ? ' is-current' : '' ?>">
                                        <span></span>
                                        <small><?= $this->e($label) ?></small>
                                    </div>
                                <?php endforeach; ?>
                            </div>

                            <div class="client-card-actions client-account-actions">
                                <a class="client-button client-button-primary" href="/commandes/<?= (int) $currentOrder['id_commande'] ?>">Voir le détail</a>
                                <a class="client-button client-button-secondary" href="/contact">Contacter l'équipe</a>
                                <?php if ($activeStatus === 'en_attente'): ?>
                                    <a class="client-button client-button-secondary" href="/commandes/<?= (int) $currentOrder['id_commande'] ?>/modifier">Modifier</a>
                                    <a class="client-button client-button-secondary" href="/commandes/<?= (int) $currentOrder['id_commande'] ?>">Annuler</a>
                                <?php else: ?>
                                    <span class="client-button client-button-disabled">Modifier</span>
                                    <span class="client-button client-button-disabled">Annuler</span>
                                <?php endif; ?>
                            </div>

                            <p class="client-current-note"><i class="bi bi-info-circle" aria-hidden="true"></i> La modification et l'annulation ne sont possibles que tant que la commande n'a pas été acceptée par l'équipe.</p>
                        <?php endif; ?>
                    </article>
                </section>

                <section class="client-account-section">
                    <article class="client-card client-account-history-card">
                        <div class="client-card-heading client-account-history-heading">
                            <h2>Historique des commandes</h2>
                            <a class="client-button client-button-secondary" href="/commandes">Voir toutes mes commandes</a>
                        </div>

                        <?php if ($displayedOrders === []): ?>
                            <p class="client-muted">Aucune commande n'est encore rattachée à votre compte.</p>
                        <?php else: ?>
                            <div class="client-history-table" role="table" aria-label="Historique des commandes">
                                <div class="client-history-table-head" role="row">
                                    <span role="columnheader">N° commande</span>
                                    <span role="columnheader">Menu</span>
                                    <span role="columnheader">Date</span>
                                    <span role="columnheader">Statut</span>
                                    <span role="columnheader">Action</span>
                                </div>
                                <?php foreach ($displayedOrders as $order): ?>
                                    <?php $status = (string) $order['statut_actuel']; ?>
                                    <a class="client-history-table-row" href="/commandes/<?= (int) $order['id_commande'] ?>" role="row">
                                        <strong role="cell"><?= $this->e($formatOrderNumber($order)) ?></strong>
                                        <span role="cell"><?= $this->e($order['menu_titre']) ?> — <?= (int) $order['nombre_personnes'] ?> pers.</span>
                                        <span role="cell"><?= $this->e($formatDate($order['date_prestation'])) ?></span>
                                        <span role="cell"><em class="client-status-badge status-<?= $this->e($status) ?>"><?= $this->e($statusLabels[$status] ?? $status) ?></em></span>
                                        <span role="cell" class="client-history-detail">Détail</span>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </article>
                </section>

                <section class="client-account-section" id="mes-informations">
                    <article class="client-card client-account-info-card">
                        <h2>Mes informations</h2>
                        <dl class="client-account-info-list">
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
                            <div>
                                <dt>Adresse :</dt>
                                <dd><?= $this->e($fullAddress) ?></dd>
                            </div>
                        </dl>
                        <a class="client-button client-button-secondary" href="/mon-compte/profil">Voir / modifier mes informations</a>
                    </article>
                </section>

                <section class="client-account-section client-review-prompt" id="mes-avis">
                    <article class="client-card">
                        <div class="client-card-heading">
                            <h2><i class="bi bi-star" aria-hidden="true"></i> Donnez votre avis</h2>
                        </div>
                        <?php if ($reviewableOrder === null): ?>
                            <p class="client-muted">Aucune commande terminée sans avis pour le moment. Le bouton apparaîtra après une prestation livrée et clôturée.</p>
                        <?php else: ?>
                            <p>Vous avez des commandes livrées sans avis. Partagez votre expérience après votre prestation.</p>
                            <div class="client-review-mini client-review-action-row">
                                <div>
                                    <strong><?= $this->e($reviewableOrder['menu_titre']) ?></strong>
                                    <span>Livré le <?= $this->e($formatDate($reviewableOrder['date_prestation'])) ?></span>
                                </div>
                                <span class="client-stars" aria-hidden="true">
                                    <i class="bi bi-star"></i>
                                    <i class="bi bi-star"></i>
                                    <i class="bi bi-star"></i>
                                    <i class="bi bi-star"></i>
                                    <i class="bi bi-star"></i>
                                </span>
                                <a class="client-button client-button-secondary" href="/avis/creation/<?= (int) $reviewableOrder['id_commande'] ?>">Laisser un avis</a>
                            </div>
                        <?php endif; ?>
                    </article>
                </section>

            </div>
        </div>
    </div>
</section>
