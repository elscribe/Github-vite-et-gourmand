<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\BaseController;
use App\Core\Input;
use App\Core\Response;
use App\Core\Session;
use App\Models\MenuModel;
use App\Models\OrderModel;

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
            'orders' => $orderModel->findForUser($userId),
            'statusLabels' => $orderModel->statusLabels(),
        ]);
    }

    public function create(?string $menuId = null): void
    {
        $menuModel = new MenuModel();
        $selectedMenuId = $menuId === null ? 0 : (int) $menuId;

        $this->view('orders/create', [
            'pageTitle' => 'Commander un menu - Vite & Gourmand',
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
                'pageTitle' => 'Commander un menu - Vite & Gourmand',
                'menus' => (new MenuModel())->findActiveMenus(),
                'selectedMenuId' => $menuId,
                'old' => $data + ['id_menu' => $menuId],
                'errors' => $errors,
            ]);

            return;
        }

        $orderId = $orderModel->create($userId, $menuId, $data);

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

        $this->view('orders/edit', [
            'pageTitle' => 'Modifier la commande - Vite & Gourmand',
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

        $updated = (new OrderModel())->changeStatusByEmployee((int) $id, $userId, $status, $comment);
        Session::flash($updated ? 'success' : 'error', $updated ? 'Statut mis a jour.' : 'Le statut demande est invalide.');

        $this->redirect('/employe/commandes');
    }

    public function employeeCancel(string $id): void
    {
        $userId = $this->authenticatedUserId();
        $modeContact = Input::postString('mode_contact_modification');
        $motif = Input::postString('motif_annulation');
        $cancelled = (new OrderModel())->cancelByEmployee((int) $id, $userId, $modeContact, $motif);

        Session::flash(
            $cancelled ? 'success' : 'error',
            $cancelled
                ? 'Commande annulee avec motif et mode de contact.'
                : 'Mode de contact ou motif invalide.'
        );

        $this->redirect('/employe/commandes');
    }

    /**
     * @return array{date_prestation: string, heure_livraison: string, adresse_livraison: string, ville_livraison: string, distance_km: float, nombre_personnes: int}
     */
    private function orderFormData(): array
    {
        return [
            'date_prestation' => Input::postString('date_prestation'),
            'heure_livraison' => Input::postString('heure_livraison'),
            'adresse_livraison' => Input::postString('adresse_livraison'),
            'ville_livraison' => Input::postString('ville_livraison'),
            'distance_km' => max(0, (float) str_replace(',', '.', Input::postString('distance_km'))),
            'nombre_personnes' => (int) Input::postString('nombre_personnes'),
        ];
    }

    /**
     * @param array{date_prestation: string, heure_livraison: string, adresse_livraison: string, ville_livraison: string, distance_km: float, nombre_personnes: int} $data
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

        if ($data['ville_livraison'] === '') {
            $errors['ville_livraison'] = 'La ville de livraison est obligatoire.';
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
}
