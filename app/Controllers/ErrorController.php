<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\BaseController;
use App\Core\Response;
use Throwable;

final class ErrorController extends BaseController
{
    public function notFound(): void
    {
        Response::status(404);

        $this->view('errors/404', [
            'pageTitle' => 'Page introuvable - Vite & Gourmand',
        ]);
    }

    public function serverError(?Throwable $throwable = null): void
    {
        Response::status(500);

        $this->view('errors/500', [
            'pageTitle' => 'Erreur serveur - Vite & Gourmand',
            'throwable' => $throwable,
        ]);
    }
}
