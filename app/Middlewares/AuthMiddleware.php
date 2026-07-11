<?php

declare(strict_types=1);

namespace App\Middlewares;

use App\Core\Response;
use App\Core\Session;

/**
 * Verifie qu'un utilisateur est connecte avant d'acceder a une route protegee.
 */
final class AuthMiddleware implements MiddlewareInterface
{
    public function handle(): bool
    {
        if (Session::isAuthenticated()) {
            return true;
        }

        Session::flash('error', 'Vous devez vous connecter pour acceder a cette page.');
        Response::redirect('/connexion');

        return false;
    }
}
