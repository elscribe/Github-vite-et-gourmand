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
        $this->show('Legal notice', 'Legal notice page required from the footer');
    }

    public function terms(): void
    {
        $this->show('Terms and conditions', 'Terms and conditions page required from the footer');
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
}
