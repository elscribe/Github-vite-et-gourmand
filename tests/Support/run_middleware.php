<?php

declare(strict_types=1);

/**
 * Execute un middleware dans un processus separe.
 *
 * Response::redirect() termine le script avec exit : le resultat est donc
 * ecrit en JSON dans une fonction d'arret.
 *
 * Usage : php run_middleware.php auth|role <id_utilisateur> [roles,separes]
 */

require dirname(__DIR__) . '/bootstrap.php';

[, $middleware, $userId] = $argv;
$roles = isset($argv[3]) ? explode(',', $argv[3]) : [];

session_start();
$_SESSION['auth_user_id'] = (int) $userId;
$_SESSION['auth_user_role'] = 'valeur_de_session_initiale';

$result = ['handled' => null, 'output' => ''];

register_shutdown_function(static function () use (&$result): void {
    $result['http'] = http_response_code();
    $result['session_user_id'] = $_SESSION['auth_user_id'] ?? null;
    $result['session_role'] = $_SESSION['auth_user_role'] ?? null;
    $result['output'] .= (string) ob_get_clean();
    echo json_encode($result);
});

ob_start();
$instance = $middleware === 'auth'
    ? new App\Middlewares\AuthMiddleware()
    : new App\Middlewares\RoleMiddleware($roles);
$result['handled'] = $instance->handle();
