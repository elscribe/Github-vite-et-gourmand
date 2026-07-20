<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\BaseController;
use App\Core\Response;

/**
 * Temporary controller used to reserve Sprint 1 routes before real features are implemented.
 */
final class PlaceholderController extends BaseController
{
    public function menus(): void
    {
        $this->show('Menus', 'Public menu catalog');
    }

    public function menuDetail(string $id): void
    {
        $this->show('Menu detail', 'Dynamic route prepared for menu #' . $id);
    }

    public function login(): void
    {
        $this->show('Login', 'Authentication for users, employees, and administrators');
    }

    public function logout(): void
    {
        $this->show('Logout', 'Logout route reserved for the future authentication flow');
    }

    public function register(): void
    {
        $this->show('Register', 'Public user account creation');
    }

    public function forgotPassword(): void
    {
        $this->show('Forgot password', 'Password reset link request');
    }

    public function resetPassword(): void
    {
        $this->show('Reset password', 'Password update through a secure token');
    }

    public function orders(): void
    {
        $this->show('Orders', 'User order list and order tracking');
    }

    public function orderCreate(?string $menuId = null): void
    {
        $label = $menuId === null ? 'Order creation' : 'Order creation for menu #' . $menuId;

        $this->show('Order', $label);
    }

    public function account(): void
    {
        $this->show('My account', 'Personal information, orders, and reviews');
    }

    public function employeeDashboard(): void
    {
        $this->show('Employee area', 'Orders, menus, dishes, opening hours, and review management');
    }

    public function employeeOrders(): void
    {
        $this->show('Employee orders', 'Order filtering and status update');
    }

    public function adminDashboard(): void
    {
        $this->show('Administrator area', 'Administration dashboard with employee permissions');
    }

    public function adminStatistics(): void
    {
        $this->show('Administrator statistics', 'MongoDB dashboard and period filters');
    }

    public function contact(): void
    {
        $this->show('Contact', 'Public contact form');
    }

    public function legalNotice(): void
    {
        $this->showPublicPage(
            'Mentions légales',
            'Informations légales principales de Vite & Gourmand, présentées simplement pour le livrable UX/UI.',
            'Éditeur du site',
            [
                'Vite & Gourmand',
                'Traiteur événementiel à Bordeaux',
                '12 rue des Vignes, 33000 Bordeaux',
                'contact@viteetgourmand.fr',
                'Responsable de publication : Julie Martin',
                'Hébergement : information à compléter au moment de la mise en production.',
                'Données personnelles : les informations collectées servent uniquement à la gestion des commandes, des contacts et des avis clients.',
            ],
            'Point clé UX : ces pages secondaires complètent le footer et sécurisent le parcours public.'
        );
    }

    public function terms(): void
    {
        $this->showPublicPage(
            'Conditions générales de vente',
            'Règles essentielles liées aux réservations, commandes et prestations de traiteur.',
            'Commande et validation',
            [
                '1. Une demande de menu est enregistrée avec les informations du client, la date, l’adresse et le nombre de personnes.',
                '2. Une commande peut être modifiée ou annulée par le client uniquement tant qu’elle n’a pas été acceptée par l’équipe.',
                '3. Après acceptation, toute modification interne nécessite un contact client préalable par appel ou email.',
                '4. Les statuts affichés au client suivent les étapes : reçue, acceptée, en préparation, en livraison, livrée.',
                '5. Les avis clients sont publiés après validation par l’équipe.',
            ],
            'Point clé UX : rendre visibles les règles d’annulation, de modification et de contact client.'
        );
    }

    public function privacy(): void
    {
        $this->showPublicPage(
            'Confidentialité',
            'Informations sur la protection des données personnelles collectées pendant le parcours client.',
            'Données personnelles',
            [
                'Les données saisies dans les formulaires servent uniquement à gérer les demandes, les commandes et les avis.',
                'Les informations de contact permettent à l’équipe de confirmer une prestation ou de répondre à une question.',
                'Les accès internes sont réservés au personnel autorisé.',
                'Aucune donnée de paiement n’est stockée dans cette version du projet.',
            ],
            'Point clé UX : expliquer simplement pourquoi les informations sont demandées.'
        );
    }

    public function formSubmit(): void
    {
        $this->show('Form processing', 'POST route reserved for server validation and CSRF protection');
    }

    private function show(string $sectionTitle, string $sectionDescription): void
    {
        Response::status(501);

        $this->view('placeholder/show', [
            'pageTitle' => $sectionTitle . ' - Sprint 1',
            'sectionTitle' => $sectionTitle,
            'sectionDescription' => $sectionDescription,
        ]);
    }

    /**
     * @param list<string> $contentLines
     */
    private function showPublicPage(
        string $sectionTitle,
        string $sectionDescription,
        string $contentTitle,
        array $contentLines,
        string $keyPoint
    ): void {
        $this->view('placeholder/show', [
            'pageTitle' => $sectionTitle . ' - Vite & Gourmand',
            'bodyClass' => 'page-legal',
            'sectionTitle' => $sectionTitle,
            'sectionDescription' => $sectionDescription,
            'isPublicInfoPage' => true,
            'contentTitle' => $contentTitle,
            'contentLines' => $contentLines,
            'keyPoint' => $keyPoint,
        ]);
    }
}
