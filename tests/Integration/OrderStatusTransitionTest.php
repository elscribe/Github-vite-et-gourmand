<?php

declare(strict_types=1);

namespace Tests\Integration;

use App\Models\OrderModel;
use PHPUnit\Framework\Attributes\DataProvider;

/**
 * Transitions de statut appliquees par un employe (OrderModel::changeStatusByEmployee).
 */
final class OrderStatusTransitionTest extends DatabaseTestCase
{
    /**
     * @return iterable<string, array{string, bool, string}>
     */
    public static function transitionsAutorisees(): iterable
    {
        yield 'en_attente -> acceptee' => ['en_attente', false, 'acceptee'];
        yield 'acceptee -> en_preparation' => ['acceptee', false, 'en_preparation'];
        yield 'en_preparation -> en_cours_de_livraison' => ['en_preparation', false, 'en_cours_de_livraison'];
        yield 'en_cours_de_livraison -> livre' => ['en_cours_de_livraison', false, 'livre'];
        yield 'livre -> terminee sans materiel' => ['livre', false, 'terminee'];
        yield 'livre -> en_attente_retour_materiel avec materiel' => ['livre', true, 'en_attente_retour_materiel'];
        yield 'en_attente_retour_materiel -> terminee' => ['en_attente_retour_materiel', true, 'terminee'];
    }

    #[DataProvider('transitionsAutorisees')]
    public function testTransitionAutoriseeEstAppliqueeEtHistorisee(string $from, bool $loaned, string $to): void
    {
        $this->setOrder($from, $loaned);
        $history = $this->historyCount();

        self::assertTrue((new OrderModel())->changeStatusByEmployee(self::ORDER_ID, self::EMPLOYEE_ID, $to, 'test'));
        self::assertSame($to, $this->orderStatus());
        self::assertSame($history + 1, $this->historyCount());
    }

    /**
     * @return iterable<string, array{string, bool, string}>
     */
    public static function transitionsInterdites(): iterable
    {
        yield 'saut en_attente -> livre' => ['en_attente', false, 'livre'];
        yield 'retour arriere en_preparation -> acceptee' => ['en_preparation', false, 'acceptee'];
        yield 'statut identique' => ['acceptee', false, 'acceptee'];
        yield 'sortie de terminee' => ['terminee', false, 'en_attente'];
        yield 'sortie de annulee' => ['annulee', false, 'acceptee'];
        yield 'annulation par ce chemin' => ['acceptee', false, 'annulee'];
        yield 'statut inconnu' => ['acceptee', false, 'inconnu'];
        yield 'livre -> retour materiel sans materiel' => ['livre', false, 'en_attente_retour_materiel'];
        yield 'livre -> terminee avec materiel' => ['livre', true, 'terminee'];
    }

    #[DataProvider('transitionsInterdites')]
    public function testTransitionInterditeEstRefuseeSansEcriture(string $from, bool $loaned, string $to): void
    {
        $this->setOrder($from, $loaned);
        $history = $this->historyCount();

        self::assertFalse((new OrderModel())->changeStatusByEmployee(self::ORDER_ID, self::EMPLOYEE_ID, $to, 'test'));
        self::assertSame($from, $this->orderStatus());
        self::assertSame($history, $this->historyCount());
    }

    public function testCommandeInexistanteRefusee(): void
    {
        self::assertFalse((new OrderModel())->changeStatusByEmployee(999999, self::EMPLOYEE_ID, 'acceptee', 'test'));
    }

    public function testDoubleSoumissionDeLaMemeTransition(): void
    {
        $this->setOrder('acceptee', false);

        self::assertTrue((new OrderModel())->changeStatusByEmployee(self::ORDER_ID, self::EMPLOYEE_ID, 'en_preparation', 'premier'));
        self::assertFalse((new OrderModel())->changeStatusByEmployee(self::ORDER_ID, self::EMPLOYEE_ID, 'en_preparation', 'second'));
    }
}
