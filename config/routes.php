<?php

declare(strict_types=1);

use App\Controllers\ErrorController;
use App\Controllers\HomeController;
use App\Controllers\PlaceholderController;
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
    $adminAccess = [new AuthMiddleware(), new RoleMiddleware(['administrateur'])];

    $router->get('/', [HomeController::class, 'index']);

    $router->get('/menus', [PlaceholderController::class, 'menus']);
    $router->get('/menus/{id}', [PlaceholderController::class, 'menuDetail']);

    $router->get('/connexion', [PlaceholderController::class, 'login']);
    $router->post('/connexion', [PlaceholderController::class, 'formSubmit'], $csrf);
    $router->get('/deconnexion', [PlaceholderController::class, 'logout'], $auth);

    $router->get('/inscription', [PlaceholderController::class, 'register']);
    $router->post('/inscription', [PlaceholderController::class, 'formSubmit'], $csrf);

    $router->get('/mot-de-passe/oublie', [PlaceholderController::class, 'forgotPassword']);
    $router->post('/mot-de-passe/oublie', [PlaceholderController::class, 'formSubmit'], $csrf);
    $router->get('/mot-de-passe/reinitialisation', [PlaceholderController::class, 'resetPassword']);
    $router->post('/mot-de-passe/reinitialisation', [PlaceholderController::class, 'formSubmit'], $csrf);

    $router->get('/commandes', [PlaceholderController::class, 'orders'], $auth);
    $router->get('/commandes/creation', [PlaceholderController::class, 'orderCreate'], $auth);
    $router->get('/commandes/creation/{menuId}', [PlaceholderController::class, 'orderCreate'], $auth);
    $router->post('/commandes', [PlaceholderController::class, 'formSubmit'], $authCsrf);

    $router->get('/mon-compte', [PlaceholderController::class, 'account'], $auth);

    $router->get('/employe', [PlaceholderController::class, 'employeeDashboard'], $employeeAccess);
    $router->get('/employe/commandes', [PlaceholderController::class, 'employeeOrders'], $employeeAccess);

    $router->get('/admin', [PlaceholderController::class, 'adminDashboard'], $adminAccess);
    $router->get('/admin/statistiques', [PlaceholderController::class, 'adminStatistics'], $adminAccess);

    $router->get('/contact', [PlaceholderController::class, 'contact']);
    $router->post('/contact', [PlaceholderController::class, 'formSubmit'], $csrf);

    $router->get('/mentions-legales', [PlaceholderController::class, 'legalNotice']);
    $router->get('/cgv', [PlaceholderController::class, 'terms']);
};
