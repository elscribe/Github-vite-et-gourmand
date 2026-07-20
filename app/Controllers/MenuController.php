<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\BaseController;
use App\Core\Response;
use App\Models\MenuModel;
use App\Services\MenuPresentation;

/**
 * Controleur du catalogue public des menus.
 */
final class MenuController extends BaseController
{
    public function index(): void
    {
        $menuModel = new MenuModel();

        $this->view('menus/index', [
            'pageTitle' => 'Nos menus - Vite & Gourmand',
            'bodyClass' => 'page-menus',
            'menus' => $menuModel->findActiveMenus(),
            'themes' => $menuModel->findThemes(),
            'regimes' => $menuModel->findRegimes(),
        ]);
    }

    public function show(string $id): void
    {
        $menuId = (int) $id;
        $menuModel = new MenuModel();
        $menu = $menuModel->findActiveMenuById($menuId);

        if ($menu === null) {
            Response::status(404);

            $this->view('errors/404', [
                'pageTitle' => 'Menu introuvable - Vite & Gourmand',
            ]);

            return;
        }

        $presentation = MenuPresentation::forMenu($menu);

        $this->view('menus/show', [
            'pageTitle' => $presentation['title'] . ' - Vite & Gourmand',
            'bodyClass' => 'page-menu-detail',
            'menu' => $menu,
            'images' => $menuModel->findImagesByMenuId($menuId),
            'dishes' => $menuModel->findDishesByMenuId($menuId),
            'allergens' => $menuModel->findAllergensByMenuId($menuId),
        ]);
    }
}
