<?php

declare(strict_types=1);

use App\Controllers\AccountController;
use App\Controllers\AdminController;
use App\Controllers\AuthController;
use App\Controllers\ContactController;
use App\Controllers\ErrorController;
use App\Controllers\HomeController;
use App\Controllers\MenuController;
use App\Controllers\OrderController;
use App\Controllers\PlaceholderController;
use App\Controllers\ReviewController;
use App\Core\Router;
use App\Middlewares\AuthMiddleware;
use App\Middlewares\CsrfMiddleware;
use App\Middlewares\RoleMiddleware;

return static function (Router $router): void {
    $router->setNotFoundHandler([ErrorController::class, 'notFound']);

    $auth = [new AuthMiddleware()];
    $csrf = [new CsrfMiddleware()];
    $authCsrf = [new AuthMiddleware(), new CsrfMiddleware()];
    $employeeAccess = [new AuthMiddleware(), new RoleMiddleware(['employe', 'administrateur'])];
    $employeeAccessCsrf = [new AuthMiddleware(), new RoleMiddleware(['employe', 'administrateur']), new CsrfMiddleware()];
    $adminAccess = [new AuthMiddleware(), new RoleMiddleware(['administrateur'])];
    $adminAccessCsrf = [new AuthMiddleware(), new RoleMiddleware(['administrateur']), new CsrfMiddleware()];

    $router->get('/', [HomeController::class, 'index']);
    $router->get('/accueil', [HomeController::class, 'redirectToHome']);

    $router->get('/menus', [MenuController::class, 'index']);
    $router->get('/menus/{id}', [MenuController::class, 'show']);

    $router->get('/connexion', [AuthController::class, 'login']);
    $router->post('/connexion', [AuthController::class, 'storeLogin'], $csrf);
    $router->get('/deconnexion', [AuthController::class, 'logout'], $auth);

    $router->get('/inscription', [AuthController::class, 'register']);
    $router->post('/inscription', [AuthController::class, 'storeRegister'], $csrf);

    $router->get('/mot-de-passe/oublie', [AuthController::class, 'forgotPassword']);
    $router->post('/mot-de-passe/oublie', [AuthController::class, 'sendResetLink'], $csrf);
    $router->get('/mot-de-passe/reinitialisation', [AuthController::class, 'resetPassword']);
    $router->post('/mot-de-passe/reinitialisation', [AuthController::class, 'updatePassword'], $csrf);

    $router->get('/commandes', [OrderController::class, 'index'], $auth);
    $router->get('/commandes/creation', [OrderController::class, 'create'], $auth);
    $router->get('/commandes/creation/{menuId}', [OrderController::class, 'create'], $auth);
    $router->post('/commandes', [OrderController::class, 'store'], $authCsrf);
    $router->get('/commandes/{id}', [OrderController::class, 'show'], $auth);
    $router->get('/commandes/{id}/modifier', [OrderController::class, 'edit'], $auth);
    $router->post('/commandes/{id}/modifier', [OrderController::class, 'update'], $authCsrf);
    $router->post('/commandes/{id}/annuler', [OrderController::class, 'cancel'], $authCsrf);
    $router->get('/avis', [ReviewController::class, 'index'], $auth);
    $router->get('/avis/creation/{orderId}', [ReviewController::class, 'create'], $auth);
    $router->post('/avis', [ReviewController::class, 'store'], $authCsrf);

    $router->get('/mon-compte', [AccountController::class, 'show'], $auth);
    $router->get('/mon-compte/profil', [AccountController::class, 'profile'], $auth);
    $router->get('/mon-compte/modifier', [AccountController::class, 'edit'], $auth);
    $router->post('/mon-compte/modifier', [AccountController::class, 'update'], $authCsrf);

    $router->get('/employe', [OrderController::class, 'employeeDashboard'], $employeeAccess);
    $router->get('/employe/commandes', [OrderController::class, 'employeeIndex'], $employeeAccess);
    $router->post('/employe/commandes/{id}/modifier', [OrderController::class, 'employeeUpdateOrder'], $employeeAccessCsrf);
    $router->post('/employe/commandes/{id}/statut', [OrderController::class, 'employeeStatus'], $employeeAccessCsrf);
    $router->post('/employe/commandes/{id}/annuler', [OrderController::class, 'employeeCancel'], $employeeAccessCsrf);
    $router->get('/employe/avis', [ReviewController::class, 'employeeIndex'], $employeeAccess);
    $router->post('/employe/avis/{id}/moderation', [ReviewController::class, 'moderate'], $employeeAccessCsrf);

    $router->get('/admin', [AdminController::class, 'dashboard'], $adminAccess);
    $router->get('/admin/commandes', [OrderController::class, 'employeeIndex'], $adminAccess);
    $router->post('/admin/commandes/{id}/modifier', [OrderController::class, 'employeeUpdateOrder'], $adminAccessCsrf);
    $router->post('/admin/commandes/{id}/statut', [OrderController::class, 'employeeStatus'], $adminAccessCsrf);
    $router->post('/admin/commandes/{id}/annuler', [OrderController::class, 'employeeCancel'], $adminAccessCsrf);
    $router->get('/admin/avis', [ReviewController::class, 'employeeIndex'], $adminAccess);
    $router->post('/admin/avis/{id}/moderation', [ReviewController::class, 'moderate'], $adminAccessCsrf);
    $router->get('/admin/statistiques', [AdminController::class, 'statistics'], $adminAccess);
    $router->get('/admin/employes', [AdminController::class, 'employees'], $adminAccess);
    $router->post('/admin/employes', [AdminController::class, 'storeEmployee'], $adminAccessCsrf);
    $router->post('/admin/employes/{id}/activation', [AdminController::class, 'toggleEmployee'], $adminAccessCsrf);
    $router->get('/admin/horaires', [AdminController::class, 'schedules'], $adminAccess);
    $router->post('/admin/horaires', [AdminController::class, 'updateSchedules'], $adminAccessCsrf);
    $router->get('/admin/menus', [AdminController::class, 'menus'], $adminAccess);
    $router->post('/admin/menus', [AdminController::class, 'storeMenu'], $adminAccessCsrf);
    $router->post('/admin/menus/selection', [AdminController::class, 'updateMenuSelection'], $adminAccessCsrf);
    $router->post('/admin/menus/{id}', [AdminController::class, 'updateMenu'], $adminAccessCsrf);
    $router->post('/admin/menus/{id}/plats', [AdminController::class, 'attachDishToMenu'], $adminAccessCsrf);
    $router->post('/admin/menus/{id}/plats/{dishId}/retirer', [AdminController::class, 'detachDishFromMenu'], $adminAccessCsrf);
    $router->get('/admin/plats', [AdminController::class, 'dishes'], $adminAccess);
    $router->post('/admin/plats', [AdminController::class, 'storeDish'], $adminAccessCsrf);
    $router->post('/admin/plats/{id}', [AdminController::class, 'updateDish'], $adminAccessCsrf);

    $router->get('/contact', [ContactController::class, 'create']);
    $router->post('/contact', [ContactController::class, 'store'], $csrf);

    $router->get('/mentions-legales', [PlaceholderController::class, 'legalNotice']);
    $router->get('/cgv', [PlaceholderController::class, 'terms']);
    $router->get('/confidentialite', [PlaceholderController::class, 'privacy']);
};
