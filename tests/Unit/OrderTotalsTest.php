<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Models\OrderModel;
use PHPUnit\Framework\TestCase;

/**
 * Calcul du prix d'une commande : prix du menu, remise de volume et livraison.
 */
final class OrderTotalsTest extends TestCase
{
    private const MENU = ['nombre_personnes_minimum' => 10, 'prix_minimum' => 400.00];

    public function testPrixAuMinimumDePersonnesABordeaux(): void
    {
        $totals = (new OrderModel())->calculateTotals(self::MENU, 10, 'Bordeaux', 0.0);

        self::assertSame(
            ['prix_menu' => 400.0, 'remise' => 0.0, 'prix_livraison' => 0.0, 'prix_total' => 400.0],
            $totals
        );
    }

    public function testPasDeRemiseAQuatrePersonnesAuDessusDuMinimum(): void
    {
        $totals = (new OrderModel())->calculateTotals(self::MENU, 14, 'Bordeaux', 0.0);

        self::assertSame(560.0, $totals['prix_menu']);
        self::assertSame(0.0, $totals['remise']);
    }

    public function testRemiseDeDixPourcentACinqPersonnesAuDessusDuMinimum(): void
    {
        $totals = (new OrderModel())->calculateTotals(self::MENU, 15, 'Bordeaux', 0.0);

        self::assertSame(600.0, $totals['prix_menu']);
        self::assertSame(60.0, $totals['remise']);
        self::assertSame(540.0, $totals['prix_total']);
    }

    public function testLivraisonGratuiteABordeauxQuelleQueSoitLaCasse(): void
    {
        $totals = (new OrderModel())->calculateTotals(self::MENU, 10, '  BORDEAUX ', 12.0);

        self::assertSame(0.0, $totals['prix_livraison']);
    }

    public function testLivraisonHorsBordeauxCinqEurosPlusCinquanteNeufCentimesDuKilometre(): void
    {
        $totals = (new OrderModel())->calculateTotals(self::MENU, 10, 'Pessac', 10.0);

        self::assertSame(10.9, $totals['prix_livraison']);
        self::assertSame(410.9, $totals['prix_total']);
    }

    public function testRemiseEtLivraisonCumulees(): void
    {
        $totals = (new OrderModel())->calculateTotals(self::MENU, 20, 'Arcachon', 65.0);

        self::assertSame(800.0, $totals['prix_menu']);
        self::assertSame(80.0, $totals['remise']);
        self::assertSame(43.35, $totals['prix_livraison']);
        self::assertSame(763.35, $totals['prix_total']);
    }

    public function testExempleDocumenteCocktailBordelaisQuinzePersonnesAPessac(): void
    {
        $menu = ['nombre_personnes_minimum' => 10, 'prix_minimum' => 220.00];

        $totals = (new OrderModel())->calculateTotals($menu, 15, 'Pessac', 10.0);

        self::assertSame(
            ['prix_menu' => 330.0, 'remise' => 33.0, 'prix_livraison' => 10.9, 'prix_total' => 307.9],
            $totals
        );
    }
}
