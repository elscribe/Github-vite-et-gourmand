<?php
/**
 * @var list<array<string, mixed>> $orders
 * @var array<string, string> $statusLabels
 * @var array{status: string, customer?: string, id_commande: string, nom: string, prenom: string, email: string, telephone: string, adresse: string, ville: string} $filters
 */
$searchFilterKeys = ['id_commande', 'nom', 'prenom', 'email', 'telephone', 'adresse', 'ville'];
$backofficeStatusLabels = $statusLabels;
$backofficeStatusLabels['en_attente'] = 'Reçue';
$orderManagementBasePath = $orderManagementBasePath ?? '/employe/commandes';
$statusTabs = ['' => 'Toutes'] + $backofficeStatusLabels;
$focusedOrderId = ctype_digit($filters['id_commande']) ? (int) $filters['id_commande'] : 0;

$statusTabUrl = static function (string $status) use ($filters, $searchFilterKeys, $orderManagementBasePath): string {
    $query = [];

    if ($status !== '') {
        $query['status'] = $status;
    }

    foreach ($searchFilterKeys as $key) {
        $value = trim((string) ($filters[$key] ?? ''));

        if ($value !== '') {
            $query[$key] = $value;
        }
    }

    return $query === [] ? $orderManagementBasePath : $orderManagementBasePath . '?' . http_build_query($query);
};
?>
<section class="page-section">
    <div class="container">
        <div class="section-heading">
            <h1>Gestion des commandes</h1>
            <p class="muted-text">Filtrez les commandes, mettez &agrave; jour les statuts et annulez apr&egrave;s contact client.</p>
        </div>

        <div class="employee-order-filter-bar">
            <div class="employee-status-tabs" aria-label="Filtrer par statut">
                <?php foreach ($statusTabs as $status => $label): ?>
                    <?php $statusClass = $status === '' ? 'all' : str_replace('_', '-', $status); ?>
                    <a
                        href="<?= htmlspecialchars($statusTabUrl($status), ENT_QUOTES, 'UTF-8') ?>"
                        class="employee-status-tab employee-status-tab-<?= $this->e($statusClass) ?><?= $filters['status'] === $status ? ' is-active' : '' ?>"
                    >
                        <?= $this->e($label) ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>

        <form class="menu-filters employee-order-search-form" action="<?= htmlspecialchars($orderManagementBasePath, ENT_QUOTES, 'UTF-8') ?>" method="get">
            <input type="hidden" name="status" value="<?= $this->e($filters['status']) ?>">
            <div>
                <label for="id_commande">ID commande</label>
                <input id="id_commande" name="id_commande" type="search" value="<?= $this->e($filters['id_commande']) ?>" placeholder="Ex : 12">
            </div>
            <div>
                <label for="nom">Nom</label>
                <input id="nom" name="nom" type="search" value="<?= $this->e($filters['nom']) ?>" placeholder="Ex : Martin">
            </div>
            <div>
                <label for="prenom">Pr&eacute;nom</label>
                <input id="prenom" name="prenom" type="search" value="<?= $this->e($filters['prenom']) ?>" placeholder="Ex : Claire">
            </div>
            <div>
                <label for="email">Email</label>
                <input id="email" name="email" type="email" value="<?= $this->e($filters['email']) ?>" placeholder="client@email.fr">
            </div>
            <div>
                <label for="telephone">T&eacute;l&eacute;phone</label>
                <input id="telephone" name="telephone" type="tel" value="<?= $this->e($filters['telephone']) ?>" placeholder="Ex : 0603">
            </div>
            <div>
                <label for="adresse">Adresse</label>
                <input id="adresse" name="adresse" type="search" value="<?= $this->e($filters['adresse']) ?>" placeholder="Ex : rue">
            </div>
            <div>
                <label for="ville">Ville</label>
                <input id="ville" name="ville" type="search" value="<?= $this->e($filters['ville']) ?>" placeholder="Ex : Bordeaux">
            </div>
            <div class="menu-filters-actions">
                <button class="primary-link" type="submit">Rechercher</button>
                <a class="secondary-link" href="<?= htmlspecialchars($orderManagementBasePath, ENT_QUOTES, 'UTF-8') ?>">R&eacute;initialiser</a>
            </div>
        </form>

        <?php if ($orders === []): ?>
            <div class="page-panel">
                <p>Aucune commande ne correspond aux filtres.</p>
            </div>
        <?php else: ?>
            <div class="employee-order-list">
                <?php foreach ($orders as $order): ?>
                    <?php
                    $status = (string) $order['statut_actuel'];
                    $isClosed = in_array($status, ['terminee', 'annulee'], true);
                    ?>
                    <article id="commande-<?= (int) $order['id_commande'] ?>" class="employee-order-card<?= $isClosed ? ' employee-order-card-closed' : '' ?>">
                        <div class="employee-order-summary">
                            <div>
                                <strong>#<?= (int) $order['id_commande'] ?></strong>
                                <span><?= $this->e($order['client_prenom']) ?> <?= $this->e($order['client_nom']) ?></span>
                                <small><?= $this->e($order['client_email']) ?> - <?= $this->e($order['client_telephone']) ?></small>
                            </div>
                            <div>
                                <span><?= $this->e($order['menu_titre']) ?></span>
                                <small><?= (int) $order['nombre_personnes'] ?> personnes - <?= number_format((float) $order['prix_total'], 2, ',', ' ') ?> EUR</small>
                            </div>
                            <div>
                                <span><?= $this->e($order['date_prestation']) ?></span>
                                <small><?= $this->e(substr((string) $order['heure_livraison'], 0, 5)) ?> - <?= $this->e($order['ville_livraison']) ?></small>
                            </div>
                            <span class="status-pill status-<?= $this->e(str_replace('_', '-', $status)) ?>">
                                <?= $this->e($backofficeStatusLabels[$status] ?? $status) ?>
                            </span>
                        </div>

                        <?php if (!$isClosed): ?>
                            <details class="employee-manage-details"<?= $focusedOrderId === (int) $order['id_commande'] ? ' open' : '' ?>>
                                <summary>Gerer</summary>
                                <div class="employee-order-actions">
                                    <p class="employee-contact-warning">
                                        <i class="bi bi-info-circle" aria-hidden="true"></i>
                                        <span>Modification ou annulation uniquement apres contact client par email ou GSM.</span>
                                    </p>

                                    <form action="<?= htmlspecialchars($orderManagementBasePath . '/' . (int) $order['id_commande'] . '/statut', ENT_QUOTES, 'UTF-8') ?>" method="post" class="employee-action-form">
                                        <input type="hidden" name="_csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
                                        <h3>Modifier le statut</h3>
                                        <label for="statut-<?= (int) $order['id_commande'] ?>">Nouveau statut</label>
                                        <select id="statut-<?= (int) $order['id_commande'] ?>" name="statut">
                                            <?php foreach ($backofficeStatusLabels as $value => $label): ?>
                                                <?php if ($value === 'annulee') {
                                                    continue;
                                                } ?>
                                                <option value="<?= $this->e($value) ?>" <?= $status === $value ? 'selected' : '' ?>><?= $this->e($label) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <label for="commentaire-<?= (int) $order['id_commande'] ?>">Commentaire</label>
                                        <input id="commentaire-<?= (int) $order['id_commande'] ?>" name="commentaire" type="text" placeholder="Precision interne">
                                        <button class="primary-link" type="submit">Enregistrer le statut</button>
                                    </form>

                                    <form action="<?= htmlspecialchars($orderManagementBasePath . '/' . (int) $order['id_commande'] . '/annuler', ENT_QUOTES, 'UTF-8') ?>" method="post" class="employee-action-form">
                                        <input type="hidden" name="_csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
                                        <h3>Annuler une commande</h3>
                                        <label for="mode-<?= (int) $order['id_commande'] ?>">Contact client</label>
                                        <select id="mode-<?= (int) $order['id_commande'] ?>" name="mode_contact_modification">
                                            <option value="email">Email</option>
                                            <option value="gsm">GSM</option>
                                        </select>
                                        <label for="motif-<?= (int) $order['id_commande'] ?>">Motif</label>
                                        <input id="motif-<?= (int) $order['id_commande'] ?>" name="motif_annulation" type="text" placeholder="Motif obligatoire">
                                        <button class="secondary-button" type="submit">Annuler la commande</button>
                                    </form>

                                    <form action="<?= htmlspecialchars($orderManagementBasePath . '/' . (int) $order['id_commande'] . '/modifier', ENT_QUOTES, 'UTF-8') ?>" method="post" class="employee-action-form employee-order-edit-form">
                                        <input type="hidden" name="_csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
                                        <h3>Modifier apres contact client</h3>
                                        <div class="employee-order-edit-grid">
                                            <div>
                                                <label for="date-prestation-<?= (int) $order['id_commande'] ?>">Date</label>
                                                <input id="date-prestation-<?= (int) $order['id_commande'] ?>" name="date_prestation" type="date" value="<?= $this->e($order['date_prestation']) ?>" required>
                                            </div>
                                            <div>
                                                <label for="heure-livraison-<?= (int) $order['id_commande'] ?>">Heure</label>
                                                <input id="heure-livraison-<?= (int) $order['id_commande'] ?>" name="heure_livraison" type="time" value="<?= $this->e(substr((string) $order['heure_livraison'], 0, 5)) ?>" required>
                                            </div>
                                            <div>
                                                <label for="nombre-personnes-<?= (int) $order['id_commande'] ?>">Personnes</label>
                                                <input id="nombre-personnes-<?= (int) $order['id_commande'] ?>" name="nombre_personnes" type="number" min="1" value="<?= (int) $order['nombre_personnes'] ?>" required>
                                            </div>
                                            <div>
                                                <label for="distance-km-<?= (int) $order['id_commande'] ?>">Distance km</label>
                                                <input id="distance-km-<?= (int) $order['id_commande'] ?>" name="distance_km" type="number" min="0" step="0.1" value="<?= $this->e((string) $order['distance_km']) ?>" required>
                                            </div>
                                            <div>
                                                <label for="adresse-livraison-<?= (int) $order['id_commande'] ?>">Adresse</label>
                                                <input id="adresse-livraison-<?= (int) $order['id_commande'] ?>" name="adresse_livraison" type="text" value="<?= $this->e($order['adresse_livraison']) ?>" required>
                                            </div>
                                            <div>
                                                <label for="ville-livraison-<?= (int) $order['id_commande'] ?>">Ville</label>
                                                <input id="ville-livraison-<?= (int) $order['id_commande'] ?>" name="ville_livraison" type="text" value="<?= $this->e($order['ville_livraison']) ?>" required>
                                            </div>
                                            <div>
                                                <label for="mode-modification-<?= (int) $order['id_commande'] ?>">Contact client</label>
                                                <select id="mode-modification-<?= (int) $order['id_commande'] ?>" name="mode_contact_modification" required>
                                                    <option value="email" <?= $order['mode_contact_modification'] === 'email' ? 'selected' : '' ?>>Email</option>
                                                    <option value="gsm" <?= $order['mode_contact_modification'] === 'gsm' ? 'selected' : '' ?>>GSM</option>
                                                </select>
                                            </div>
                                            <div>
                                                <label for="motif-modification-<?= (int) $order['id_commande'] ?>">Motif</label>
                                                <textarea id="motif-modification-<?= (int) $order['id_commande'] ?>" name="motif_modification" rows="2" placeholder="Motif obligatoire" required></textarea>
                                            </div>
                                        </div>
                                        <button class="primary-link" type="submit">Modifier apres contact</button>
                                    </form>
                                </div>
                            </details>
                        <?php endif; ?>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
