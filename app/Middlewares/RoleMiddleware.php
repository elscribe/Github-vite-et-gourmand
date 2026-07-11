<?php

declare(strict_types=1);

namespace App\Middlewares;

use App\Core\Response;
use App\Core\Session;

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
        if (Session::hasRole($this->roles)) {
            return true;
        }

        Response::status(403);
        echo 'Acces refuse.';

        return false;
    }
}
