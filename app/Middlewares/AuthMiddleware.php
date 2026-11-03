<?php

declare(strict_types=1);

namespace App\Middlewares;

use App\Core\Response;
use App\Core\Session;
use App\Models\UserModel;

/**
 * Verifie qu'un utilisateur est connecte avant d'acceder a une route protegee.
 */
final class AuthMiddleware implements MiddlewareInterface
{
    public function handle(): bool
    {
        $userId = Session::userId();

        if ($userId !== null) {
            $user = (new UserModel())->findById($userId);

            if ($user !== null && (int) $user['actif'] === 1) {
                Session::put('auth_user_role', (new UserModel())->normalizeRole((string) $user['role']));
                return true;
            }

            Session::logout();
        }

        Session::flash('error', 'Vous devez vous connecter pour acceder a cette page.');
        Response::redirect('/connexion');

        return false;
    }
}
