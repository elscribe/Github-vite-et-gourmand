<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\BaseController;
use App\Core\Input;
use App\Core\Session;
use App\Core\Validator;
use App\Models\DishModel;
use App\Models\MenuModel;
use App\Models\OrderModel;
use App\Models\ReviewModel;
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
        $orderModel = new OrderModel();
        $reviewModel = new ReviewModel();
        $orders = $orderModel->findAll();
        $dailyStats = $orderModel->dashboardDailyStats();
        $pendingReviews = $reviewModel->countPending();

        $this->view('admin/dashboard', [
            'pageTitle' => 'Tableau de bord - Vite & Gourmand',
            'adminStats' => [
                'orders_to_process' => $dailyStats['active_followups'],
                'revenue_today' => $dailyStats['revenue_today'],
                'kitchen_delivery_followups' => count(array_filter(
                    $orders,
                    static fn (array $order): bool => in_array(
                        $order['statut_actuel'],
                        ['en_preparation', 'en_cours_de_livraison', 'livre', 'en_attente_retour_materiel'],
                        true
                    )
                )),
                'pending_reviews' => $pendingReviews,
            ],
            'ordersToProcess' => $orderModel->findDashboardOrders(),
            'reviewsToModerate' => $reviewModel->findPendingForDashboard(),
            'statusLabels' => $orderModel->statusLabels(),
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
            'pageTitle' => 'Comptes employés - Vite & Gourmand',
            'employees' => (new UserModel())->findEmployees(),
            'old' => ['pays' => 'France'],
            'errors' => [],
        ]);
    }

    public function storeEmployee(): void
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
        $userModel = new UserModel();

        if ($userModel->findByEmail($data['email']) !== null) {
            $errors['email'] = 'Cette adresse email existe deja.';
        }

        if ($data['password'] !== $data['password_confirmation']) {
            $errors['password_confirmation'] = 'Les mots de passe ne correspondent pas.';
        }

        if ($errors !== []) {
            $old = $data;
            unset($old['password'], $old['password_confirmation']);

            $this->view('admin/employees', [
                'pageTitle' => 'Comptes employés - Vite & Gourmand',
                'employees' => $userModel->findEmployees(),
                'old' => $old,
                'errors' => $errors,
            ]);

            return;
        }

        $userModel->createEmployee([
            'email' => $data['email'],
            'nom' => $data['nom'],
            'prenom' => $data['prenom'],
            'telephone' => $data['telephone'],
            'adresse_postale' => $data['adresse_postale'],
            'ville' => $data['ville'],
            'pays' => $data['pays'],
        ], $data['password']);

        (new MailService())->send(
            $data['email'],
            'Votre compte employe Vite & Gourmand',
            "Bonjour {$data['prenom']},\n\nUn compte employe Vite & Gourmand vient d'etre cree pour vous.\nPour recuperer votre mot de passe, contactez directement votre administrateur.\n\nPour des raisons de securite, aucun mot de passe n'est transmis par email.\n\nL'equipe Vite & Gourmand"
        );
        Session::flash('success', 'Compte employe cree. Communiquez le mot de passe hors email, puis invitez la personne a le changer si besoin.');

        $this->redirect('/admin/employes');
    }

    public function toggleEmployee(string $id): void
    {
        $active = Input::postString('active') === '1';
        $userModel = new UserModel();
        $employee = $userModel->findById((int) $id);
        $isEmployee = $employee !== null && $userModel->normalizeRole((string) $employee['role']) === 'employe';
        $updated = $isEmployee && $userModel->setEmployeeActive((int) $id, $active);

        if ($updated) {
            $employeeName = trim((string) $employee['prenom'] . ' ' . (string) $employee['nom']);
            $status = $active ? 'reactive' : 'desactive';
            Session::flash('success', "Compte de {$employeeName} {$status}.");
            $this->redirect('/admin/employes');
        }

        Session::flash('error', 'Compte employe introuvable.');
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
        $this->view('admin/menus', $this->menuWorkspaceData());
    }

    public function storeMenu(): void
    {
        $menuModel = new MenuModel();
        $data = $this->menuData();
        $dishIds = $this->selectedIds('dish_ids');
        $errors = $this->validateMenuData($data);

        if ($errors !== []) {
            $this->view('admin/menus', $this->menuWorkspaceData([
                'selectedDishIds' => [0 => $dishIds] + $menuModel->findDishIdsByMenu(),
                'old' => $data,
                'errors' => $errors,
            ]));

            return;
        }

        $menuId = $menuModel->create($data);
        $menuModel->syncDishes($menuId, $dishIds);
        Session::flash('success', 'Menu cree.');

        $this->redirect('/admin/menus?menu=' . $menuId);
    }

    public function updateMenu(string $id): void
    {
        $menuModel = new MenuModel();
        $data = $this->menuData();
        $errors = $this->validateMenuData($data);

        if ($errors !== []) {
            Session::flash('error', 'Menu non mis a jour : verifiez les champs obligatoires.');
            $this->redirect('/admin/menus');
        }

        $menuId = (int) $id;
        $menuModel->updateBasic($menuId, $data);
        $menuModel->syncDishes($menuId, $this->selectedIds('dish_ids'));
        Session::flash('success', 'Menu mis a jour.');

        $this->redirect('/admin/menus?menu=' . $menuId);
    }

    public function updateMenuSelection(): void
    {
        $selectedMenuId = (int) Input::postString('selected_menu_id');
        $selectedDishId = (int) Input::postString('selected_dish_id');

        (new MenuModel())->updateActiveSelection($this->selectedIds('public_menu_ids'));
        Session::flash('success', 'Selection des menus publics mise a jour.');

        $this->redirect($this->menuWorkspacePath($selectedMenuId, $selectedDishId));
    }

    public function dishes(): void
    {
        $this->redirect('/admin/menus');
    }

    public function storeDish(): void
    {
        $dishModel = new DishModel();
        $data = $this->dishData();
        $allergenIds = $this->selectedIds('allergen_ids');
        $errors = $this->validateDishData($data);
        $selectedMenuId = (int) Input::postString('selected_menu_id');

        if ($errors !== []) {
            $this->view('admin/menus', $this->menuWorkspaceData([
                'selectedAllergenIds' => [0 => $allergenIds] + $dishModel->findAllergenIdsByDish(),
                'dishOld' => $data,
                'dishErrors' => $errors,
            ]));

            return;
        }

        $dishId = $dishModel->create($data);
        $dishModel->syncAllergens($dishId, $allergenIds);
        Session::flash('success', 'Plat cree.');

        $this->redirect($this->menuWorkspacePath($selectedMenuId, $dishId));
    }

    public function updateDish(string $id): void
    {
        $dishModel = new DishModel();
        $data = $this->dishData();
        $errors = $this->validateDishData($data);
        $selectedMenuId = (int) Input::postString('selected_menu_id');
        $dishId = (int) $id;

        if ($errors !== []) {
            Session::flash('error', 'Plat non mis a jour : verifiez le titre et le type.');
            $this->redirect($this->menuWorkspacePath($selectedMenuId, $dishId));
        }

        $dishModel->update($dishId, $data);
        $dishModel->syncAllergens($dishId, $this->selectedIds('allergen_ids'));
        Session::flash('success', 'Plat mis a jour.');

        $this->redirect($this->menuWorkspacePath($selectedMenuId, $dishId));
    }

    public function attachDishToMenu(string $id): void
    {
        $menuId = (int) $id;
        $dishId = (int) Input::postString('id_plat');

        if ($menuId <= 0 || $dishId <= 0) {
            Session::flash('error', 'Menu ou plat invalide.');
            $this->redirect('/admin/menus');
        }

        $dishIdsByMenu = (new MenuModel())->findDishIdsByMenu();
        $dishIds = $dishIdsByMenu[$menuId] ?? [];
        $dishIds[] = $dishId;

        (new MenuModel())->syncDishes($menuId, $dishIds);
        Session::flash('success', 'Plat ajoute au menu.');

        $this->redirect($this->menuWorkspacePath($menuId, $dishId));
    }

    public function detachDishFromMenu(string $id, string $dishId): void
    {
        $menuId = (int) $id;
        $removedDishId = (int) $dishId;

        if ($menuId <= 0 || $removedDishId <= 0) {
            Session::flash('error', 'Menu ou plat invalide.');
            $this->redirect('/admin/menus');
        }

        $dishIdsByMenu = (new MenuModel())->findDishIdsByMenu();
        $dishIds = array_values(array_filter(
            $dishIdsByMenu[$menuId] ?? [],
            static fn (int $currentDishId): bool => $currentDishId !== $removedDishId
        ));

        (new MenuModel())->syncDishes($menuId, $dishIds);
        Session::flash('success', 'Plat retire du menu.');

        $this->redirect($this->menuWorkspacePath($menuId));
    }

    /**
     * @param array<string, mixed> $overrides
     *
     * @return array<string, mixed>
     */
    private function menuWorkspaceData(array $overrides = []): array
    {
        $menuModel = new MenuModel();
        $dishModel = new DishModel();
        $menus = $menuModel->findAllForAdmin();
        $dishes = $dishModel->findAll();
        $selectedDishIds = $menuModel->findDishIdsByMenu();
        $selectedAllergenIds = $dishModel->findAllergenIdsByDish();

        $selectedMenuId = (int) Input::getString('menu');
        if ($selectedMenuId <= 0) {
            $selectedMenuId = (int) Input::postString('selected_menu_id');
        }
        if ($selectedMenuId <= 0 && $menus !== []) {
            $selectedMenuId = (int) $menus[0]['id_menu'];
        }

        $selectedMenu = null;
        foreach ($menus as $menu) {
            if ((int) $menu['id_menu'] === $selectedMenuId) {
                $selectedMenu = $menu;
                break;
            }
        }

        if ($selectedMenu === null && $menus !== []) {
            $selectedMenu = $menus[0];
            $selectedMenuId = (int) $selectedMenu['id_menu'];
        }

        $selectedDishId = (int) Input::getString('dish');
        if ($selectedDishId <= 0) {
            $selectedDishId = (int) Input::postString('selected_dish_id');
        }
        if ($selectedDishId <= 0 && $dishes !== []) {
            $selectedDishId = (int) $dishes[0]['id_plat'];
        }

        $selectedDish = null;
        foreach ($dishes as $dish) {
            if ((int) $dish['id_plat'] === $selectedDishId) {
                $selectedDish = $dish;
                break;
            }
        }

        if ($selectedDish === null && $dishes !== []) {
            $selectedDish = $dishes[0];
            $selectedDishId = (int) $selectedDish['id_plat'];
        }

        return array_replace([
            'pageTitle' => 'Menus, plats & composition - Vite & Gourmand',
            'menus' => $menus,
            'themes' => $menuModel->findThemes(),
            'regimes' => $menuModel->findRegimes(),
            'dishes' => $dishes,
            'allergens' => $dishModel->findAllergens(),
            'selectedDishIds' => $selectedDishIds,
            'selectedAllergenIds' => $selectedAllergenIds,
            'selectedMenu' => $selectedMenu,
            'selectedMenuDishes' => $selectedMenuId > 0 ? $menuModel->findDishesByMenuId($selectedMenuId) : [],
            'selectedDish' => $selectedDish,
            'old' => ['actif' => 1],
            'errors' => [],
            'dishOld' => ['type_plat' => 'plat'],
            'dishErrors' => [],
        ], $overrides);
    }

    private function menuWorkspacePath(int $menuId = 0, int $dishId = 0): string
    {
        $query = [];

        if ($menuId > 0) {
            $query['menu'] = (string) $menuId;
        }

        if ($dishId > 0) {
            $query['dish'] = (string) $dishId;
        }

        return '/admin/menus' . ($query === [] ? '' : '?' . http_build_query($query));
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

    /**
     * @return list<int>
     */
    private function selectedIds(string $field): array
    {
        $ids = [];

        foreach (Input::postArray($field) as $value) {
            $id = (int) $value;

            if ($id > 0 && !in_array($id, $ids, true)) {
                $ids[] = $id;
            }
        }

        return $ids;
    }
}
