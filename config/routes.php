<?php

declare(strict_types=1);

use App\Controllers\ErrorController;
use App\Controllers\HomeController;
use App\Controllers\PlaceholderController;
use App\Core\Router;

return static function (Router $router): void {
    $router->setNotFoundHandler([ErrorController::class, 'notFound']);

    $router->get('/', [HomeController::class, 'index']);

    $router->get('/menus', [PlaceholderController::class, 'menus']);
    $router->get('/menus/{id}', [PlaceholderController::class, 'menuDetail']);

    $router->get('/connexion', [PlaceholderController::class, 'login']);
    $router->post('/connexion', [PlaceholderController::class, 'formSubmit']);
    $router->get('/deconnexion', [PlaceholderController::class, 'logout']);

    $router->get('/inscription', [PlaceholderController::class, 'register']);
    $router->post('/inscription', [PlaceholderController::class, 'formSubmit']);

    $router->get('/mot-de-passe/oublie', [PlaceholderController::class, 'forgotPassword']);
    $router->post('/mot-de-passe/oublie', [PlaceholderController::class, 'formSubmit']);
    $router->get('/mot-de-passe/reinitialisation', [PlaceholderController::class, 'resetPassword']);
    $router->post('/mot-de-passe/reinitialisation', [PlaceholderController::class, 'formSubmit']);

    $router->get('/commandes', [PlaceholderController::class, 'orders']);
    $router->get('/commandes/creation', [PlaceholderController::class, 'orderCreate']);
    $router->get('/commandes/creation/{menuId}', [PlaceholderController::class, 'orderCreate']);
    $router->post('/commandes', [PlaceholderController::class, 'formSubmit']);

    $router->get('/mon-compte', [PlaceholderController::class, 'account']);

    $router->get('/employe', [PlaceholderController::class, 'employeeDashboard']);
    $router->get('/employe/commandes', [PlaceholderController::class, 'employeeOrders']);

    $router->get('/admin', [PlaceholderController::class, 'adminDashboard']);
    $router->get('/admin/statistiques', [PlaceholderController::class, 'adminStatistics']);

    $router->get('/contact', [PlaceholderController::class, 'contact']);
    $router->post('/contact', [PlaceholderController::class, 'formSubmit']);

    $router->get('/mentions-legales', [PlaceholderController::class, 'legalNotice']);
    $router->get('/cgv', [PlaceholderController::class, 'terms']);
};
