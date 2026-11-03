<?php

declare(strict_types=1);

namespace Tests\Integration;

use App\Models\ReviewModel;
use PHPUnit\Framework\Attributes\DataProvider;

/**
 * Un avis ne peut etre depose que sur sa propre commande terminee, une seule fois.
 */
final class ReviewEligibilityTest extends DatabaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->pdo->exec('DELETE FROM avis WHERE id_commande = ' . self::ORDER_ID);
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function statutsNonTermines(): iterable
    {
        foreach (['en_attente', 'acceptee', 'en_preparation', 'en_cours_de_livraison', 'livre', 'en_attente_retour_materiel', 'annulee'] as $status) {
            yield $status => [$status];
        }
    }

    #[DataProvider('statutsNonTermines')]
    public function testAvisRefuseAvantCommandeTerminee(string $status): void
    {
        $this->setOrder($status, false);

        self::assertFalse((new ReviewModel())->create(self::CUSTOMER_ID, self::ORDER_ID, 5, 'Tres bon'));
        self::assertSame(0, $this->reviewCount());
    }

    public function testAvisAccepteApresCommandeTermineePuisUneSeuleFois(): void
    {
        $this->setOrder('terminee', false);

        self::assertTrue((new ReviewModel())->create(self::CUSTOMER_ID, self::ORDER_ID, 5, 'Tres bon'));
        self::assertFalse((new ReviewModel())->create(self::CUSTOMER_ID, self::ORDER_ID, 4, 'Second avis'));
        self::assertSame(1, $this->reviewCount());
    }

    public function testAvisRefusePourLaCommandeDUnAutreClient(): void
    {
        $this->setOrder('terminee', false);

        self::assertFalse((new ReviewModel())->create(7, self::ORDER_ID, 5, 'Pas ma commande'));
    }

    public function testNoteHorsBornesRefusee(): void
    {
        $this->setOrder('terminee', false);

        self::assertFalse((new ReviewModel())->create(self::CUSTOMER_ID, self::ORDER_ID, 6, 'Note invalide'));
        self::assertFalse((new ReviewModel())->create(self::CUSTOMER_ID, self::ORDER_ID, 0, 'Note invalide'));
    }

    private function reviewCount(): int
    {
        return (int) $this->pdo->query('SELECT COUNT(*) FROM avis WHERE id_commande = ' . self::ORDER_ID)->fetchColumn();
    }
}
