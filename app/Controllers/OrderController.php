<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\BaseController;
use App\Core\Input;
use App\Core\Response;
use App\Core\Session;
use App\Models\MenuModel;
use App\Models\OrderModel;
use App\Models\UserModel;
use App\Services\MailService;

/**
 * Controleur du parcours commande client et de la gestion employe.
 */
final class OrderController extends BaseController
{
    public function index(): void
    {
        $userId = $this->authenticatedUserId();
        $orderModel = new OrderModel();

        $this->view('orders/index', [
            'pageTitle' => 'Mes commandes - Vite & Gourmand',
            'bodyClass' => 'page-client page-client-orders',
            'orders' => $orderModel->findForUser($userId),
            'statusLabels' => $orderModel->statusLabels(),
        ]);
    }

    public function create(?string $menuId = null): void
    {
        $menuModel = new MenuModel();
        $selectedMenuId = $menuId === null ? 0 : (int) $menuId;

        $this->view('orders/create', [
            'pageTitle' => 'Validation de votre commande - Vite & Gourmand',
            'bodyClass' => 'page-client page-client-order-form',
            'menus' => $menuModel->findActiveMenus(),
            'selectedMenuId' => $selectedMenuId,
            'old' => [],
            'errors' => [],
        ]);
    }

    public function store(): void
    {
        $userId = $this->authenticatedUserId();
        $orderModel = new OrderModel();
        $menuId = (int) Input::postString('id_menu');
        $menu = $orderModel->findMenuForOrder($menuId);
        $data = $this->orderFormData();
        $errors = $this->validateOrderData($data, $menu);

        if ($errors !== []) {
            $this->view('orders/create', [
                'pageTitle' => 'Validation de votre commande - Vite & Gourmand',
                'bodyClass' => 'page-client page-client-order-form',
                'menus' => (new MenuModel())->findActiveMenus(),
                'selectedMenuId' => $menuId,
                'old' => $data + ['id_menu' => $menuId],
                'errors' => $errors,
            ]);

            return;
        }

        $orderId = $orderModel->create($userId, $menuId, $data);
        $this->notifyOrderCreated($userId, $orderId);

        Session::flash('success', 'Votre commande a ete enregistree et attend validation.');
        $this->redirect('/commandes/' . $orderId);
    }

    public function show(string $id): void
    {
        $userId = $this->authenticatedUserId();
        $orderModel = new OrderModel();
        $order = $orderModel->findOneForUser((int) $id, $userId);

        if ($order === null) {
            Response::status(404);
            $this->view('errors/404', ['pageTitle' => 'Commande introuvable - Vite & Gourmand']);
            return;
        }

        $this->view('orders/show', [
            'pageTitle' => 'Commande #' . (int) $order['id_commande'] . ' - Vite & Gourmand',
            'bodyClass' => 'page-client page-client-order-detail',
            'order' => $order,
            'history' => $orderModel->findHistory((int) $order['id_commande']),
            'statusLabels' => $orderModel->statusLabels(),
        ]);
    }

    public function edit(string $id): void
    {
        $userId = $this->authenticatedUserId();
        $orderModel = new OrderModel();
        $order = $orderModel->findOneForUser((int) $id, $userId);

        if ($order === null) {
            Response::status(404);
            $this->view('errors/404', ['pageTitle' => 'Commande introuvable - Vite & Gourmand']);
            return;
        }

        if ($order['statut_actuel'] !== 'en_attente') {
            Session::flash('error', 'Cette commande ne peut plus etre modifiee car elle a deja ete traitee.');
            $this->redirect('/commandes/' . (int) $id);
        }

        $this->view('orders/edit', [
            'pageTitle' => 'Modifier la commande - Vite & Gourmand',
            'bodyClass' => 'page-client page-client-order-form',
            'order' => $order,
            'old' => $order,
            'errors' => [],
        ]);
    }

    public function update(string $id): void
    {
        $userId = $this->authenticatedUserId();
        $orderModel = new OrderModel();
        $order = $orderModel->findOneForUser((int) $id, $userId);
        $data = $this->orderFormData();
        $menu = $order === null ? null : $orderModel->findMenuForOrder((int) $order['id_menu']);
        $errors = $this->validateOrderData($data, $menu);

        if ($order === null) {
            Response::status(404);
            $this->view('errors/404', ['pageTitle' => 'Commande introuvable - Vite & Gourmand']);
            return;
        }

        if ($order['statut_actuel'] !== 'en_attente') {
            $errors['statut'] = 'Cette commande ne peut plus etre modifiee car elle a deja ete traitee.';
        }

        if ($errors !== []) {
            $this->view('orders/edit', [
                'pageTitle' => 'Modifier la commande - Vite & Gourmand',
                'bodyClass' => 'page-client page-client-order-form',
                'order' => $order,
                'old' => $data,
                'errors' => $errors,
            ]);

            return;
        }

        $orderModel->updatePendingForUser((int) $id, $userId, $data);
        Session::flash('success', 'Votre commande a ete modifiee.');

        $this->redirect('/commandes/' . (int) $id);
    }

    public function cancel(string $id): void
    {
        $userId = $this->authenticatedUserId();
        $motif = Input::postString('motif_annulation');

        if ($motif === '') {
            $motif = 'Annulation demandee par le client.';
        }

        $cancelled = (new OrderModel())->cancelPendingForUser((int) $id, $userId, $motif);
        Session::flash(
            $cancelled ? 'success' : 'error',
            $cancelled
                ? 'La commande a ete annulee.'
                : 'Cette commande ne peut plus etre annulee depuis votre espace client.'
        );

        $this->redirect('/commandes/' . (int) $id);
    }

    public function employeeDashboard(): void
    {
        $orderModel = new OrderModel();
        $orders = $orderModel->findAll();

        $this->view('employee/dashboard', [
            'pageTitle' => 'Espace employe - Vite & Gourmand',
            'orders' => $orders,
            'statusLabels' => $orderModel->statusLabels(),
        ]);
    }

    public function employeeIndex(): void
    {
        $orderModel = new OrderModel();
        $filters = [
            'status' => Input::getString('status'),
            'customer' => Input::getString('customer'),
        ];

        $this->view('employee/orders', [
            'pageTitle' => 'Commandes employe - Vite & Gourmand',
            'orders' => $orderModel->findAll($filters),
            'statusLabels' => $orderModel->statusLabels(),
            'filters' => $filters,
        ]);
    }

    public function employeeStatus(string $id): void
    {
        $userId = $this->authenticatedUserId();
        $status = Input::postString('statut');
        $comment = Input::postString('commentaire');

        if ($comment === '') {
            $comment = 'Statut mis a jour par un employe.';
        }

        $orderModel = new OrderModel();
        $updated = $orderModel->changeStatusByEmployee((int) $id, $userId, $status, $comment);

        if ($updated) {
            $this->notifyCustomerAfterEmployeeStatus($orderModel, (int) $id, $status);
        }

        Session::flash($updated ? 'success' : 'error', $updated ? 'Statut mis a jour.' : 'Le statut demande est invalide.');

        $this->redirect('/employe/commandes');
    }

    public function employeeCancel(string $id): void
    {
        $userId = $this->authenticatedUserId();
        $modeContact = Input::postString('mode_contact_modification');
        $motif = Input::postString('motif_annulation');
        $orderModel = new OrderModel();
        $cancelled = $orderModel->cancelByEmployee((int) $id, $userId, $modeContact, $motif);

        if ($cancelled && $modeContact === 'email') {
            $this->notifyCustomerAboutCancellation($orderModel, (int) $id, $motif);
        }

        Session::flash(
            $cancelled ? 'success' : 'error',
            $cancelled
                ? 'Commande annulee avec motif et mode de contact.'
                : 'Mode de contact ou motif invalide.'
        );

        $this->redirect('/employe/commandes');
    }

    /**
     * @return array{date_prestation: string, heure_livraison: string, adresse_livraison: string, code_postal_livraison: string, ville_livraison: string, distance_km: float, commentaire_client: string, nombre_personnes: int}
     */
    private function orderFormData(): array
    {
        $city = Input::postString('ville_livraison');
        $distance = max(0, (float) str_replace(',', '.', Input::postString('distance_km')));

        if (strtolower(trim($city)) === 'bordeaux') {
            $distance = 0.0;
        }

        return [
            'date_prestation' => Input::postString('date_prestation'),
            'heure_livraison' => Input::postString('heure_livraison'),
            'adresse_livraison' => Input::postString('adresse_livraison'),
            'code_postal_livraison' => Input::postString('code_postal_livraison'),
            'ville_livraison' => $city,
            'distance_km' => $distance,
            'commentaire_client' => Input::postString('commentaire_client'),
            'nombre_personnes' => (int) Input::postString('nombre_personnes'),
        ];
    }

    /**
     * @param array{date_prestation: string, heure_livraison: string, adresse_livraison: string, code_postal_livraison: string, ville_livraison: string, distance_km: float, commentaire_client: string, nombre_personnes: int} $data
     * @param array<string, mixed>|null $menu
     *
     * @return array<string, string>
     */
    private function validateOrderData(array $data, ?array $menu): array
    {
        $errors = [];

        if ($menu === null) {
            $errors['id_menu'] = 'Le menu selectionne est invalide.';
            return $errors;
        }

        if ($data['date_prestation'] === '') {
            $errors['date_prestation'] = 'La date de prestation est obligatoire.';
        }

        if ($data['heure_livraison'] === '' || preg_match('/^\d{2}:\d{2}$/', $data['heure_livraison']) !== 1) {
            $errors['heure_livraison'] = 'L heure de livraison est obligatoire.';
        }

        if ($data['adresse_livraison'] === '') {
            $errors['adresse_livraison'] = 'L adresse de livraison est obligatoire.';
        }

        if ($data['code_postal_livraison'] === '' || preg_match('/^\d{5}$/', $data['code_postal_livraison']) !== 1) {
            $errors['code_postal_livraison'] = 'Le code postal doit contenir 5 chiffres.';
        }

        if ($data['ville_livraison'] === '') {
            $errors['ville_livraison'] = 'La ville de livraison est obligatoire.';
        }

        if (strtolower(trim($data['ville_livraison'])) !== 'bordeaux' && $data['distance_km'] <= 0) {
            $errors['distance_km'] = 'Indiquez une distance approximative depuis Bordeaux pour estimer la livraison.';
        }

        if (strlen($data['commentaire_client']) > 1000) {
            $errors['commentaire_client'] = 'Le commentaire ne doit pas depasser 1000 caracteres.';
        }

        if ($data['nombre_personnes'] < (int) $menu['nombre_personnes_minimum']) {
            $errors['nombre_personnes'] = 'Le nombre de personnes doit respecter le minimum du menu.';
        }

        return $errors;
    }

    private function authenticatedUserId(): int
    {
        $userId = Session::userId();

        if ($userId === null) {
            $this->redirect('/connexion');
        }

        return $userId;
    }

    private function notifyOrderCreated(int $userId, int $orderId): void
    {
        $user = (new UserModel())->findById($userId);
        $order = (new OrderModel())->findOneForEmployee($orderId);

        if ($user === null || $order === null) {
            return;
        }

        (new MailService())->send(
            (string) $user['email'],
            'Confirmation de votre commande Vite & Gourmand',
            "Bonjour {$user['prenom']},\n\nVotre commande #{$orderId} pour le menu {$order['menu_titre']} a bien ete enregistree.\nElle est actuellement en attente de validation par notre equipe.\n\nVous pouvez la suivre depuis votre espace :\n" . $this->absoluteUrl('/commandes/' . $orderId) . "\n\nL'equipe Vite & Gourmand"
        );
    }

    private function notifyCustomerAfterEmployeeStatus(OrderModel $orderModel, int $orderId, string $status): void
    {
        $order = $orderModel->findOneForEmployee($orderId);

        if ($order === null) {
            return;
        }

        if ($status === 'terminee') {
            (new MailService())->send(
                (string) $order['client_email'],
                'Votre avis nous interesse',
                "Bonjour {$order['client_prenom']},\n\nVotre commande #{$orderId} est terminee. Vous pouvez maintenant partager votre avis sur le menu {$order['menu_titre']} depuis votre espace client :\n" . $this->absoluteUrl('/avis/creation/' . $orderId) . "\n\nMerci pour votre confiance,\nL'equipe Vite & Gourmand"
            );
        }

        if ($status === 'en_attente_retour_materiel') {
            (new MailService())->send(
                (string) $order['client_email'],
                'Retour du materiel de votre prestation',
                "Bonjour {$order['client_prenom']},\n\nLe materiel prete pour votre commande #{$orderId} est en attente de retour.\nMerci de le restituer sous 10 jours ouvres. Passe ce delai, des frais de 600 EUR peuvent etre appliques selon les conditions de prestation.\n\nL'equipe Vite & Gourmand"
            );
        }
    }

    private function notifyCustomerAboutCancellation(OrderModel $orderModel, int $orderId, string $reason): void
    {
        $order = $orderModel->findOneForEmployee($orderId);

        if ($order === null) {
            return;
        }

        (new MailService())->send(
            (string) $order['client_email'],
            'Annulation de votre commande Vite & Gourmand',
            "Bonjour {$order['client_prenom']},\n\nVotre commande #{$orderId} pour le menu {$order['menu_titre']} a ete annulee apres contact avec notre equipe.\nMotif : {$reason}\n\nL'equipe Vite & Gourmand"
        );
    }

    private function absoluteUrl(string $path): string
    {
        return rtrim(getenv('APP_URL') ?: 'http://127.0.0.1:8000', '/') . $path;
    }
}
