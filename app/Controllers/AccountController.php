<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\BaseController;
use App\Core\Input;
use App\Core\Session;
use App\Core\Validator;
use App\Models\OrderModel;
use App\Models\UserModel;

/**
 * Controleur de l'espace personnel client.
 */
final class AccountController extends BaseController
{
    public function show(): void
    {
        $userId = Session::userId();

        if ($userId === null) {
            $this->redirect('/connexion');
        }

        $userModel = new UserModel();
        $orderModel = new OrderModel();

        $this->view('account/show', [
            'pageTitle' => 'Mon compte - Vite & Gourmand',
            'bodyClass' => 'page-client page-client-account',
            'user' => $userModel->findById($userId),
            'currentOrder' => $orderModel->findCurrentForUser($userId),
            'reviewableOrder' => $orderModel->findReviewableForUser($userId),
            'orders' => array_slice($orderModel->findForUser($userId), 0, 5),
            'statusLabels' => $orderModel->statusLabels(),
            'errors' => [],
        ]);
    }

    public function edit(): void
    {
        $userId = Session::userId();

        if ($userId === null) {
            $this->redirect('/connexion');
        }

        $this->view('account/edit', [
            'pageTitle' => 'Modifier mes informations - Vite & Gourmand',
            'bodyClass' => 'page-client page-client-profile-edit',
            'user' => (new UserModel())->findById($userId),
            'errors' => [],
            'success' => Input::getString('success') === '1',
        ]);
    }

    public function update(): void
    {
        $userId = Session::userId();

        if ($userId === null) {
            $this->redirect('/connexion');
        }

        $data = [
            'nom' => Input::postString('nom'),
            'prenom' => Input::postString('prenom'),
            'telephone' => Input::postString('telephone'),
            'adresse_postale' => Input::postString('adresse_postale'),
            'ville' => Input::postString('ville'),
            'pays' => Input::postString('pays', 'France'),
            'canal_contact_prefere' => Input::postString('canal_contact_prefere', 'email'),
        ];

        $validator = Validator::make($data, [
            'nom' => ['required', 'max:80'],
            'prenom' => ['required', 'max:80'],
            'telephone' => ['required', 'max:30'],
            'adresse_postale' => ['required', 'max:255'],
            'ville' => ['required', 'max:80'],
            'pays' => ['required', 'max:80'],
            'canal_contact_prefere' => ['required', 'in:email,telephone'],
        ]);

        if ($validator->fails()) {
            $user = (new UserModel())->findById($userId) ?? [];

            $this->view('account/edit', [
                'pageTitle' => 'Modifier mes informations - Vite & Gourmand',
                'bodyClass' => 'page-client page-client-profile-edit',
                'user' => $data + ['email' => $user['email'] ?? ''],
                'errors' => $this->flattenErrors($validator->errors()),
                'success' => false,
            ]);

            return;
        }

        (new UserModel())->updateProfile($userId, $data);

        $this->redirect('/mon-compte/modifier?success=1');
    }

    /**
     * @param array<string, list<string>> $errors
     *
     * @return array<string, string>
     */
    private function flattenErrors(array $errors): array
    {
        $flattened = [];

        foreach ($errors as $field => $messages) {
            $flattened[$field] = $messages[0] ?? 'Le champ est invalide.';
        }

        return $flattened;
    }
}
