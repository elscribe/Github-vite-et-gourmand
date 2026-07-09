<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\BaseController;
use App\Core\Response;

/**
 * Controleur temporaire pour reserver les routes de Sprint 1 sans feature.
 */
final class PlaceholderController extends BaseController
{
    public function menus(): void
    {
        $this->show('Menus', 'Catalogue public des menus');
    }

    public function menuDetail(string $id): void
    {
        $this->show('Detail menu', 'Route parametree preparee pour le menu #' . $id);
    }

    public function login(): void
    {
        $this->show('Connexion', 'Authentification utilisateur, employe et administrateur');
    }

    public function register(): void
    {
        $this->show('Inscription', 'Creation publique de compte utilisateur');
    }

    public function forgotPassword(): void
    {
        $this->show('Mot de passe oublie', 'Demande de lien de reinitialisation');
    }

    public function resetPassword(): void
    {
        $this->show('Reinitialisation mot de passe', 'Changement de mot de passe via jeton');
    }

    public function orders(): void
    {
        $this->show('Commandes', 'Liste et suivi des commandes utilisateur');
    }

    public function orderCreate(?string $menuId = null): void
    {
        $label = $menuId === null ? 'Creation de commande' : 'Creation de commande pour le menu #' . $menuId;
        $this->show('Commande', $label);
    }

    public function account(): void
    {
        $this->show('Mon compte', 'Informations personnelles, commandes et avis');
    }

    public function employeeDashboard(): void
    {
        $this->show('Espace employe', 'Gestion des commandes, menus, plats, horaires et avis');
    }

    public function employeeOrders(): void
    {
        $this->show('Commandes employe', 'Filtrage et mise a jour des statuts de commande');
    }

    public function adminDashboard(): void
    {
        $this->show('Espace administrateur', 'Administration et acces aux fonctions employe');
    }

    public function adminStatistics(): void
    {
        $this->show('Statistiques administrateur', 'Dashboard MongoDB et filtres par periode');
    }

    public function contact(): void
    {
        $this->show('Contact', 'Formulaire public de contact');
    }

    public function formSubmit(): void
    {
        $this->show('Traitement formulaire', 'Route POST reservee pour validation serveur et CSRF');
    }

    private function show(string $sectionTitle, string $sectionDescription): void
    {
        Response::status(501);

        $this->view('placeholder/show', [
            'pageTitle' => $sectionTitle . ' - Sprint 0',
            'sectionTitle' => $sectionTitle,
            'sectionDescription' => $sectionDescription,
        ]);
    }
}
