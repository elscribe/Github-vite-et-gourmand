<?php

declare(strict_types=1);

namespace App\Middlewares;

use App\Core\Input;
use App\Core\Response;
use App\Core\Security;

/**
 * Verifie le token CSRF envoye par les formulaires.
 */
final class CsrfMiddleware implements MiddlewareInterface
{
    public function handle(): bool
    {
        if (Input::method() !== 'POST') {
            return true;
        }

        $token = Input::postString('_csrf_token');

        if (Security::verifyCsrfToken($token)) {
            return true;
        }

        Response::status(403);
        echo 'Requete invalide.';

        return false;
    }
}
