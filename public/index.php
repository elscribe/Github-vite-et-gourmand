<?php

declare(strict_types=1);

/**
 * Controleur frontal de l'application.
 *
 * Toutes les requetes HTTP entrent dans l'application PHP par ce fichier. Il
 * charge l'autoload PSR-4 de Composer, l'environnement, les erreurs, la session
 * et les routes, puis demande au Router d'appeler le controleur correspondant.
 */

use App\Core\Env;
use App\Core\ErrorHandler;
use App\Core\Router;
use App\Core\Session;

require dirname(__DIR__) . '/vendor/autoload.php';

Env::load(dirname(__DIR__) . '/.env');

$appConfig = require dirname(__DIR__) . '/config/app.php';
$sessionConfig = require dirname(__DIR__) . '/config/session.php';

date_default_timezone_set($appConfig['timezone']);
ErrorHandler::register($appConfig);
Session::configure($sessionConfig);
Session::start();

$router = new Router();

$registerRoutes = require dirname(__DIR__) . '/config/routes.php';
$registerRoutes($router);

$router->dispatch(
    $_SERVER['REQUEST_METHOD'] ?? 'GET',
    parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/'
);
