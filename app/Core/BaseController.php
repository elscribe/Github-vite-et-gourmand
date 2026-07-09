<?php

declare(strict_types=1);

namespace App\Core;

use RuntimeException;

/**
 * Classe parente de tous les controleurs.
 *
 * Les aides communes aux controleurs sont placees ici pour eviter de repeter
 * le meme code dans chaque controleur. Pour le squelette, elle contient
 * seulement un petit moteur de rendu de vues.
 */
abstract class BaseController
{
    /**
     * Charge un fichier de vue a l'interieur du layout principal.
     *
     * @param array<string, mixed> $data Variables rendues disponibles dans la vue.
     */
    protected function view(string $view, array $data = []): void
    {
        $viewPath = dirname(__DIR__) . '/Views/' . $view . '.php';
        $layoutPath = dirname(__DIR__) . '/Views/layouts/main.php';

        if (!is_file($viewPath)) {
            throw new RuntimeException('Vue introuvable : ' . $view);
        }

        $csrfToken = Security::csrfToken();

        extract($data, EXTR_SKIP);
        require $layoutPath;
    }

    protected function redirect(string $path): void
    {
        Response::redirect($path);
    }

    protected function e(mixed $value): string
    {
        return Security::escape($value);
    }
}
