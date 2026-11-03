<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Controllers\OrderController;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;

/**
 * Validation serveur du formulaire de commande (date et minimum de personnes).
 *
 * `validateOrderData()` est privee : elle est appelee par reflexion pour la
 * tester sans base de donnees ni requete HTTP.
 */
final class OrderValidationTest extends TestCase
{
    private const MENU = ['nombre_personnes_minimum' => 10];

    /**
     * @param array<string, mixed> $overrides
     *
     * @return array<string, string>
     */
    private function validate(array $overrides, ?array $menu = self::MENU): array
    {
        $data = $overrides + [
            'date_prestation' => (new \DateTimeImmutable('+7 days'))->format('Y-m-d'),
            'heure_livraison' => '12:00',
            'adresse_livraison' => '1 place de la Bourse',
            'code_postal_livraison' => '33000',
            'ville_livraison' => 'Bordeaux',
            'distance_km' => 0.0,
            'commentaire_client' => '',
            'nombre_personnes' => 10,
        ];

        $controller = (new \ReflectionClass(OrderController::class))->newInstanceWithoutConstructor();

        return (new ReflectionMethod(OrderController::class, 'validateOrderData'))->invoke($controller, $data, $menu);
    }

    public function testCommandeValideSansErreur(): void
    {
        self::assertSame([], $this->validate([]));
    }

    public function testNombreDePersonnesSousLeMinimumRefuse(): void
    {
        self::assertArrayHasKey('nombre_personnes', $this->validate(['nombre_personnes' => 9]));
    }

    public function testNombreDePersonnesEgalAuMinimumAccepte(): void
    {
        self::assertArrayNotHasKey('nombre_personnes', $this->validate(['nombre_personnes' => 10]));
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function datesMalFormees(): iterable
    {
        yield 'vide' => [''];
        yield 'texte' => ['abc'];
        yield 'format francais' => ['03/12/2030'];
        yield 'jour inexistant' => ['2030-02-30'];
        yield 'mois inexistant' => ['2030-13-01'];
        yield 'suffixe' => ['2030-11-03x'];
        yield 'espace initial' => [' 2030-12-01'];
    }

    #[DataProvider('datesMalFormees')]
    public function testDateMalFormeeRefusee(string $date): void
    {
        self::assertSame('La date de prestation doit être valide.', $this->validate(['date_prestation' => $date])['date_prestation'] ?? null);
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function datesIncompletes(): iterable
    {
        yield 'jour sur un chiffre' => ['2030-11-3'];
        yield 'mois sur un chiffre' => ['2030-1-15'];
        yield 'annee sur deux chiffres' => ['30-11-03'];
    }

    #[DataProvider('datesIncompletes')]
    public function testDateAuFormatIncompletRefusee(string $date): void
    {
        self::assertSame('La date de prestation doit être valide.', $this->validate(['date_prestation' => $date])['date_prestation'] ?? null);
    }

    public function testDatePasseeRefusee(): void
    {
        $yesterday = (new \DateTimeImmutable('yesterday'))->format('Y-m-d');

        self::assertSame(
            'La date de prestation doit être future ou actuelle.',
            $this->validate(['date_prestation' => $yesterday])['date_prestation'] ?? null
        );
    }

    public function testDateDuJourAcceptee(): void
    {
        self::assertArrayNotHasKey('date_prestation', $this->validate(['date_prestation' => (new \DateTimeImmutable('today'))->format('Y-m-d')]));
    }

    public function testMenuInexistantRefuse(): void
    {
        self::assertArrayHasKey('id_menu', $this->validate([], null));
    }
}
