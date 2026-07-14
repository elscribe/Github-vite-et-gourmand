<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\BaseController;

/**
 * Controleur de la page d'accueil publique.
 *
 * Un controleur recoit la requete HTTP selectionnee par le routeur et decide
 * quelle vue doit etre affichee. Les regles metier seront ajoutees plus tard
 * dans les modeles ou les services, pas directement dans ce controleur.
 */
final class HomeController extends BaseController
{
    /**
     * Affiche la page d'accueil de depart.
     */
    public function index(): void
    {
        $this->view('home/index', [
            'pageTitle' => 'Accueil - Vite & Gourmand',
            'bodyClass' => 'page-home',
        ]);
    }

    /**
     * Redirige l'URL /accueil vers l'accueil principal.
     */
    public function redirectToHome(): void
    {
        $this->redirect('/');
    }
}
