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
            'user' => $userModel->findById($userId),
            'orders' => array_slice($orderModel->findForUser($userId), 0, 5),
            'errors' => [],
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
        ];

        $validator = Validator::make($data, [
            'nom' => ['required', 'max:80'],
            'prenom' => ['required', 'max:80'],
            'telephone' => ['required', 'max:30'],
            'adresse_postale' => ['required', 'max:255'],
            'ville' => ['required', 'max:80'],
            'pays' => ['required', 'max:80'],
        ]);

        if ($validator->fails()) {
            $orderModel = new OrderModel();

            $this->view('account/show', [
                'pageTitle' => 'Mon compte - Vite & Gourmand',
                'user' => $data + ['email' => ''],
                'orders' => array_slice($orderModel->findForUser($userId), 0, 5),
                'errors' => $this->flattenErrors($validator->errors()),
            ]);

            return;
        }

        (new UserModel())->updateProfile($userId, $data);
        Session::flash('success', 'Vos informations ont ete mises a jour.');

        $this->redirect('/mon-compte');
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
