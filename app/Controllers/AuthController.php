<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\BaseController;
use App\Core\Input;
use App\Core\Session;
use App\Core\Validator;
use App\Models\PasswordResetModel;
use App\Models\UserModel;

/**
 * Controleur des parcours d'inscription, connexion et deconnexion.
 */
final class AuthController extends BaseController
{
    public function register(): void
    {
        $this->view('auth/register', [
            'pageTitle' => 'Inscription - Vite & Gourmand',
            'errors' => [],
            'old' => [],
        ]);
    }

    public function storeRegister(): void
    {
        $data = [
            'email' => Input::postString('email'),
            'password' => Input::postString('password'),
            'password_confirmation' => Input::postString('password_confirmation'),
            'nom' => Input::postString('nom'),
            'prenom' => Input::postString('prenom'),
            'telephone' => Input::postString('telephone'),
            'adresse_postale' => Input::postString('adresse_postale'),
            'ville' => Input::postString('ville'),
            'pays' => Input::postString('pays', 'France'),
        ];

        $validator = Validator::make($data, [
            'email' => ['required', 'email', 'max:120'],
            'password' => ['required', 'password'],
            'nom' => ['required', 'max:80'],
            'prenom' => ['required', 'max:80'],
            'telephone' => ['required', 'max:30'],
            'adresse_postale' => ['required', 'max:255'],
            'ville' => ['required', 'max:80'],
            'pays' => ['required', 'max:80'],
        ]);

        $errors = $this->flattenErrors($validator->errors());

        if ($data['password'] !== $data['password_confirmation']) {
            $errors['password_confirmation'] = 'Les mots de passe ne correspondent pas.';
        }

        $userModel = new UserModel();

        if ($userModel->findByEmail($data['email']) !== null) {
            $errors['email'] = 'Un compte existe deja avec cette adresse email.';
        }

        if ($errors !== []) {
            unset($data['password'], $data['password_confirmation']);

            $this->view('auth/register', [
                'pageTitle' => 'Inscription - Vite & Gourmand',
                'errors' => $errors,
                'old' => $data,
            ]);

            return;
        }

        $userId = $userModel->createCustomer([
            'email' => $data['email'],
            'password' => $data['password'],
            'nom' => $data['nom'],
            'prenom' => $data['prenom'],
            'telephone' => $data['telephone'],
            'adresse_postale' => $data['adresse_postale'],
            'ville' => $data['ville'],
            'pays' => $data['pays'],
        ]);

        Session::login($userId, 'utilisateur');
        Session::flash('success', 'Votre compte a bien ete cree.');

        $this->redirect('/mon-compte');
    }

    public function login(): void
    {
        $this->view('auth/login', [
            'pageTitle' => 'Connexion - Vite & Gourmand',
            'errors' => [],
            'old' => [],
        ]);
    }

    public function storeLogin(): void
    {
        $email = Input::postString('email');
        $password = Input::postString('password');
        $errors = [];

        if ($email === '' || $password === '') {
            $errors['credentials'] = 'Email et mot de passe sont obligatoires.';
        }

        $userModel = new UserModel();
        $user = $errors === [] ? $userModel->findByEmail($email) : null;

        if (
            $errors === []
            && (
                $user === null
                || (int) $user['actif'] !== 1
                || ! password_verify($password, (string) $user['password_hash'])
            )
        ) {
            $errors['credentials'] = 'Identifiants invalides.';
        }

        if ($errors !== [] || $user === null) {
            $this->view('auth/login', [
                'pageTitle' => 'Connexion - Vite & Gourmand',
                'errors' => $errors,
                'old' => ['email' => $email],
            ]);

            return;
        }

        Session::login((int) $user['id_utilisateur'], $userModel->normalizeRole((string) $user['role']));
        Session::flash('success', 'Vous etes connecte.');

        $this->redirect('/mon-compte');
    }

    public function logout(): void
    {
        Session::logout();
        Session::flash('success', 'Vous etes deconnecte.');

        $this->redirect('/');
    }

    public function forgotPassword(): void
    {
        $this->view('auth/forgot-password', [
            'pageTitle' => 'Mot de passe oublie - Vite & Gourmand',
            'errors' => [],
            'resetLink' => null,
        ]);
    }

    public function sendResetLink(): void
    {
        $email = Input::postString('email');
        $errors = [];
        $resetLink = null;

        if ($email === '' || filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            $errors['email'] = 'Adresse email invalide.';
        }

        $userModel = new UserModel();
        $user = $errors === [] ? $userModel->findByEmail($email) : null;

        if ($user !== null && (int) $user['actif'] === 1) {
            $token = (new PasswordResetModel())->createForUser((int) $user['id_utilisateur']);
            $resetLink = '/mot-de-passe/reinitialisation?token=' . urlencode($token);
        }

        $this->view('auth/forgot-password', [
            'pageTitle' => 'Mot de passe oublie - Vite & Gourmand',
            'errors' => $errors,
            'resetLink' => $resetLink,
        ]);
    }

    public function resetPassword(): void
    {
        $token = Input::getString('token');

        $this->view('auth/reset-password', [
            'pageTitle' => 'Reinitialisation - Vite & Gourmand',
            'token' => $token,
            'errors' => [],
        ]);
    }

    public function updatePassword(): void
    {
        $token = Input::postString('token');
        $password = Input::postString('password');
        $passwordConfirmation = Input::postString('password_confirmation');
        $errors = [];
        $resetModel = new PasswordResetModel();
        $reset = $resetModel->findValidByToken($token);

        $validator = Validator::make(['password' => $password], [
            'password' => ['required', 'password'],
        ]);
        $errors = $this->flattenErrors($validator->errors());

        if ($password !== $passwordConfirmation) {
            $errors['password_confirmation'] = 'Les mots de passe ne correspondent pas.';
        }

        if ($reset === null) {
            $errors['token'] = 'Le lien de reinitialisation est invalide ou expire.';
        }

        if ($errors !== []) {
            $this->view('auth/reset-password', [
                'pageTitle' => 'Reinitialisation - Vite & Gourmand',
                'token' => $token,
                'errors' => $errors,
            ]);

            return;
        }

        (new UserModel())->updatePassword((int) $reset['id_utilisateur'], $password);
        $resetModel->markUsed((int) $reset['id_reset']);
        Session::flash('success', 'Votre mot de passe a ete reinitialise.');

        $this->redirect('/connexion');
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
