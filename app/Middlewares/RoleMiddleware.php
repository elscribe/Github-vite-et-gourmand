<?php

declare(strict_types=1);

namespace App\Middlewares;

use App\Core\Response;
use App\Core\Session;
use App\Models\UserModel;

/**
 * Verifie que l'utilisateur connecte possede un role autorise.
 */
final class RoleMiddleware implements MiddlewareInterface
{
    /**
     * @param list<string> $roles
     */
    public function __construct(
        private readonly array $roles
    ) {
    }

    public function handle(): bool
    {
        $userId = Session::userId();
        $user = $userId === null ? null : (new UserModel())->findById($userId);

        $normalizedRole = $user === null ? null : (new UserModel())->normalizeRole((string) $user['role']);

        if ($user !== null && (int) $user['actif'] === 1 && $normalizedRole !== null && in_array($normalizedRole, $this->roles, true)) {
            Session::put('auth_user_role', $normalizedRole);
            return true;
        }

        if ($user === null || (int) $user['actif'] !== 1) {
            Session::logout();
            Session::flash('error', 'Votre session n est plus active.');
            Response::redirect('/connexion');
            return false;
        }

        Response::status(403);
        echo 'Acces refuse.';

        return false;
    }
}
