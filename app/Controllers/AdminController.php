<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\BaseController;
use App\Core\Input;
use App\Core\Session;
use App\Core\Validator;
use App\Models\DishModel;
use App\Models\MenuModel;
use App\Models\ScheduleModel;
use App\Models\StatisticsModel;
use App\Models\UserModel;
use App\Services\MailService;

/**
 * Controleur du tableau de bord administrateur.
 */
final class AdminController extends BaseController
{
    public function dashboard(): void
    {
        $statisticsModel = new StatisticsModel();
        $filters = $this->filters();
        $dashboard = $statisticsModel->dashboard($filters);

        $this->view('admin/dashboard', [
            'pageTitle' => 'Administration - Vite & Gourmand',
            'dashboard' => $dashboard,
            'menus' => $statisticsModel->menus(),
            'filters' => $filters,
        ]);
    }

    public function statistics(): void
    {
        $statisticsModel = new StatisticsModel();
        $filters = $this->filters();
        $dashboard = $statisticsModel->dashboard($filters);

        $this->view('admin/statistics', [
            'pageTitle' => 'Statistiques - Vite & Gourmand',
            'dashboard' => $dashboard,
            'menus' => $statisticsModel->menus(),
            'filters' => $filters,
        ]);
    }

    public function employees(): void
    {
        $this->view('admin/employees', [
            'pageTitle' => 'Comptes employes - Vite & Gourmand',
            'employees' => (new UserModel())->findEmployees(),
            'old' => ['pays' => 'France'],
            'errors' => [],
        ]);
    }

    public function storeEmployee(): void
    {
        $data = [
            'email' => Input::postString('email'),
            'nom' => Input::postString('nom'),
            'prenom' => Input::postString('prenom'),
            'telephone' => Input::postString('telephone'),
            'adresse_postale' => Input::postString('adresse_postale'),
            'ville' => Input::postString('ville'),
            'pays' => Input::postString('pays', 'France'),
        ];

        $validator = Validator::make($data, [
            'email' => ['required', 'email', 'max:120'],
            'nom' => ['required', 'max:80'],
            'prenom' => ['required', 'max:80'],
            'telephone' => ['required', 'max:30'],
            'adresse_postale' => ['required', 'max:255'],
            'ville' => ['required', 'max:80'],
            'pays' => ['required', 'max:80'],
        ]);

        $errors = $this->flattenErrors($validator->errors());
        $userModel = new UserModel();

        if ($userModel->findByEmail($data['email']) !== null) {
            $errors['email'] = 'Cette adresse email existe deja.';
        }

        if ($errors !== []) {
            $this->view('admin/employees', [
                'pageTitle' => 'Comptes employes - Vite & Gourmand',
                'employees' => $userModel->findEmployees(),
                'old' => $data,
                'errors' => $errors,
            ]);

            return;
        }

        $temporaryPassword = $this->generateTemporaryPassword();
        $userModel->createEmployee($data, $temporaryPassword);
        $resetUrl = rtrim(getenv('APP_URL') ?: 'http://127.0.0.1:8000', '/') . '/mot-de-passe/oublie';

        (new MailService())->send(
            $data['email'],
            'Votre compte employe Vite & Gourmand',
            "Bonjour {$data['prenom']},\n\nUn compte employe Vite & Gourmand vient d'etre cree pour vous.\nPour choisir votre mot de passe, utilisez la page de reinitialisation :\n{$resetUrl}\n\nPour des raisons de securite, aucun mot de passe n'est transmis par email.\n\nL'equipe Vite & Gourmand"
        );
        Session::flash('success', 'Compte employe cree. Une notification a ete envoyee par email.');

        $this->redirect('/admin/employes');
    }

    public function toggleEmployee(string $id): void
    {
        $active = Input::postString('active') === '1';
        $updated = (new UserModel())->setEmployeeActive((int) $id, $active);

        Session::flash($updated ? 'success' : 'error', $updated ? 'Compte employe mis a jour.' : 'Compte employe introuvable.');
        $this->redirect('/admin/employes');
    }

    public function schedules(): void
    {
        $this->view('admin/schedules', [
            'pageTitle' => 'Horaires - Vite & Gourmand',
            'schedules' => (new ScheduleModel())->findAll(),
        ]);
    }

    public function updateSchedules(): void
    {
        $rows = [];

        for ($day = 1; $day <= 7; $day++) {
            $closed = Input::postString('ferme_' . $day) === '1';
            $rows[] = [
                'jour_semaine' => $day,
                'ouverture_matin' => $closed ? null : $this->nullableTime(Input::postString('ouverture_matin_' . $day)),
                'fermeture_matin' => $closed ? null : $this->nullableTime(Input::postString('fermeture_matin_' . $day)),
                'ouverture_apres_midi' => $closed ? null : $this->nullableTime(Input::postString('ouverture_apres_midi_' . $day)),
                'fermeture_apres_midi' => $closed ? null : $this->nullableTime(Input::postString('fermeture_apres_midi_' . $day)),
                'ferme' => $closed ? 1 : 0,
            ];
        }

        (new ScheduleModel())->updateAll($rows);
        Session::flash('success', 'Horaires mis a jour.');

        $this->redirect('/admin/horaires');
    }

    public function menus(): void
    {
        $menuModel = new MenuModel();

        $this->view('admin/menus', [
            'pageTitle' => 'Gestion menus - Vite & Gourmand',
            'menus' => $menuModel->findAllForAdmin(),
            'themes' => $menuModel->findThemes(),
            'regimes' => $menuModel->findRegimes(),
            'old' => ['actif' => 1],
            'errors' => [],
        ]);
    }

    public function storeMenu(): void
    {
        $menuModel = new MenuModel();
        $data = $this->menuData();
        $errors = $this->validateMenuData($data);

        if ($errors !== []) {
            $this->view('admin/menus', [
                'pageTitle' => 'Gestion menus - Vite & Gourmand',
                'menus' => $menuModel->findAllForAdmin(),
                'themes' => $menuModel->findThemes(),
                'regimes' => $menuModel->findRegimes(),
                'old' => $data,
                'errors' => $errors,
            ]);

            return;
        }

        $menuModel->create($data);
        Session::flash('success', 'Menu cree.');

        $this->redirect('/admin/menus');
    }

    public function updateMenu(string $id): void
    {
        (new MenuModel())->updateBasic((int) $id, $this->menuData());
        Session::flash('success', 'Menu mis a jour.');

        $this->redirect('/admin/menus');
    }

    public function dishes(): void
    {
        $this->view('admin/dishes', [
            'pageTitle' => 'Gestion plats - Vite & Gourmand',
            'dishes' => (new DishModel())->findAll(),
            'old' => ['type_plat' => 'plat'],
            'errors' => [],
        ]);
    }

    public function storeDish(): void
    {
        $dishModel = new DishModel();
        $data = $this->dishData();
        $errors = $this->validateDishData($data);

        if ($errors !== []) {
            $this->view('admin/dishes', [
                'pageTitle' => 'Gestion plats - Vite & Gourmand',
                'dishes' => $dishModel->findAll(),
                'old' => $data,
                'errors' => $errors,
            ]);

            return;
        }

        $dishModel->create($data);
        Session::flash('success', 'Plat cree.');

        $this->redirect('/admin/plats');
    }

    public function updateDish(string $id): void
    {
        (new DishModel())->update((int) $id, $this->dishData());
        Session::flash('success', 'Plat mis a jour.');

        $this->redirect('/admin/plats');
    }

    /**
     * @return array{menu: int, start: string, end: string}
     */
    private function filters(): array
    {
        return [
            'menu' => (int) Input::getString('menu'),
            'start' => Input::getString('start'),
            'end' => Input::getString('end'),
        ];
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

    private function nullableTime(string $value): ?string
    {
        return $value === '' ? null : $value;
    }

    private function generateTemporaryPassword(): string
    {
        return 'Vg-' . bin2hex(random_bytes(6)) . '!';
    }

    /**
     * @return array{id_regime: int, id_theme: int, titre: string, description: string, conditions: string, nombre_personnes_minimum: int, prix_minimum: float, stock_disponible: int, actif: int}
     */
    private function menuData(): array
    {
        return [
            'id_regime' => (int) Input::postString('id_regime'),
            'id_theme' => (int) Input::postString('id_theme'),
            'titre' => Input::postString('titre'),
            'description' => Input::postString('description'),
            'conditions' => Input::postString('conditions'),
            'nombre_personnes_minimum' => max(1, (int) Input::postString('nombre_personnes_minimum')),
            'prix_minimum' => max(0, (float) str_replace(',', '.', Input::postString('prix_minimum'))),
            'stock_disponible' => max(0, (int) Input::postString('stock_disponible')),
            'actif' => Input::postString('actif') === '1' ? 1 : 0,
        ];
    }

    /**
     * @param array<string, mixed> $data
     *
     * @return array<string, string>
     */
    private function validateMenuData(array $data): array
    {
        $errors = [];

        foreach (['titre', 'description', 'conditions'] as $field) {
            if ((string) $data[$field] === '') {
                $errors[$field] = 'Champ obligatoire.';
            }
        }

        if ($data['id_regime'] <= 0 || $data['id_theme'] <= 0) {
            $errors['taxonomy'] = 'Theme et regime sont obligatoires.';
        }

        return $errors;
    }

    /**
     * @return array{titre_plat: string, type_plat: string, description: string}
     */
    private function dishData(): array
    {
        return [
            'titre_plat' => Input::postString('titre_plat'),
            'type_plat' => Input::postString('type_plat'),
            'description' => Input::postString('description'),
        ];
    }

    /**
     * @param array<string, string> $data
     *
     * @return array<string, string>
     */
    private function validateDishData(array $data): array
    {
        $errors = [];

        if ($data['titre_plat'] === '') {
            $errors['titre_plat'] = 'Le titre est obligatoire.';
        }

        if (!in_array($data['type_plat'], ['entree', 'plat', 'dessert'], true)) {
            $errors['type_plat'] = 'Le type de plat est invalide.';
        }

        return $errors;
    }
}
