<?php

declare(strict_types=1);

namespace Tests\Integration;

use App\Models\OrderModel;
use App\Models\MenuModel;
use PHPUnit\Framework\Attributes\DataProvider;

final class OrderStockTest extends DatabaseTestCase
{
    private int $menuId = 0;

    protected function setUp(): void
    {
        parent::setUp();

        $this->pdo->exec("INSERT INTO menus (id_regime, id_theme, titre, description, conditions, nombre_personnes_minimum, prix_minimum, stock_disponible, actif)
            VALUES (1, 1, 'Menu test stock', 'Test', 'Test', 2, 80, 3, 1)");
        $this->menuId = (int) $this->pdo->lastInsertId();
    }

    protected function tearDown(): void
    {
        if ($this->menuId !== 0) {
            $statement = $this->pdo->prepare('DELETE FROM commandes WHERE id_menu = ?');
            $statement->execute([$this->menuId]);
            $statement = $this->pdo->prepare('DELETE FROM menus WHERE id_menu = ?');
            $statement->execute([$this->menuId]);
        }

        parent::tearDown();
    }

    public function testCreationReserveUneUniteEtEnregistreLHistorique(): void
    {
        $orderId = $this->createOrder();

        self::assertGreaterThan(0, $orderId);
        self::assertSame(2, $this->stock());
        self::assertSame(1, $this->reserved($orderId));
        self::assertSame(1, $this->orderHistoryCount($orderId));
    }

    public function testStockEpuiseRefuseSansCommandeNiHistorique(): void
    {
        $this->setStock(0);
        $orders = $this->tableCount('commandes');
        $history = $this->tableCount('commande_statuts');

        self::assertSame(0, $this->createOrder());
        self::assertSame(0, $this->stock());
        self::assertSame($orders, $this->tableCount('commandes'));
        self::assertSame($history, $this->tableCount('commande_statuts'));
    }

    public function testMenuInactifRefuseSansCommandeNiHistorique(): void
    {
        $this->pdo->prepare('UPDATE menus SET actif = 0 WHERE id_menu = ?')->execute([$this->menuId]);
        $orders = $this->tableCount('commandes');
        $history = $this->tableCount('commande_statuts');

        self::assertSame(0, $this->createOrder());
        self::assertSame(3, $this->stock());
        self::assertSame($orders, $this->tableCount('commandes'));
        self::assertSame($history, $this->tableCount('commande_statuts'));
    }

    public function testMinimumModifieRefuseLaCommandeSansConsommerLeStock(): void
    {
        $this->pdo->prepare('UPDATE menus SET nombre_personnes_minimum = 4 WHERE id_menu = ?')->execute([$this->menuId]);
        self::assertSame(0, $this->createOrder());
        self::assertSame(3, $this->stock());
    }

    public function testModificationAdminAvecStockPerimeEstRefusee(): void
    {
        $initialStock = $this->stock();
        self::assertGreaterThan(0, $this->createOrder());
        $data = [
            'id_regime' => 1, 'id_theme' => 1, 'titre' => 'Titre modifié',
            'description' => 'Test', 'conditions' => 'Test', 'nombre_personnes_minimum' => 2,
            'prix_minimum' => 80.0, 'stock_disponible' => $initialStock, 'actif' => 1,
        ];

        self::assertFalse((new MenuModel())->updateBasic($this->menuId, $data, $initialStock));
        self::assertSame(2, $this->stock());
        $data['stock_disponible'] = 5;
        self::assertTrue((new MenuModel())->updateBasic($this->menuId, $data, 2));
        self::assertSame(5, $this->stock());
    }

    public function testDernieresUnitesPuisRefus(): void
    {
        $this->setStock(2);
        $first = $this->createOrder();
        $second = $this->createOrder();
        $history = $this->tableCount('commande_statuts');
        $orders = $this->tableCount('commandes');

        self::assertGreaterThan(0, $first);
        self::assertGreaterThan(0, $second);
        self::assertNotSame($first, $second);
        self::assertSame(1, $this->reserved($first));
        self::assertSame(1, $this->reserved($second));
        self::assertSame(0, $this->stock());
        self::assertSame(0, $this->createOrder());
        self::assertSame($orders, $this->tableCount('commandes'));
        self::assertSame($history, $this->tableCount('commande_statuts'));
    }

    public function testDeuxCommandesSimultaneesPourLaDernierePlace(): void
    {
        $this->setStock(1);
        $orders = $this->tableCount('commandes');
        $history = $this->tableCount('commande_statuts');
        $workers = [];
        $this->pdo->beginTransaction();

        try {
            // Bloquer la ligne permet de constater que les deux demandes attendent ensemble.
            $lock = $this->pdo->prepare('SELECT id_menu FROM menus WHERE id_menu = ? FOR UPDATE');
            $lock->execute([$this->menuId]);

            for ($i = 0; $i < 2; $i++) {
                $pipes = [];
                $process = proc_open(
                    [PHP_BINARY, dirname(__DIR__) . '/Support/OrderStockWorker.php', (string) $this->menuId, (string) self::CUSTOMER_ID],
                    [1 => ['pipe', 'w'], 2 => ['pipe', 'w']],
                    $pipes
                );
                self::assertIsResource($process);
                $workers[] = ['process' => $process, 'pipes' => $pipes];
            }

            $deadline = microtime(true) + 10;
            $waiting = 0;
            do {
                $waiting = 0;
                foreach ($this->pdo->query('SHOW FULL PROCESSLIST')->fetchAll(\PDO::FETCH_ASSOC) as $processInfo) {
                    $query = (string) ($processInfo['Info'] ?? '');
                    if (($processInfo['db'] ?? null) === getenv('DB_NAME')
                        && str_contains($query, 'FROM menus WHERE id_menu =')
                        && str_contains($query, 'FOR UPDATE')) {
                        $waiting++;
                    }
                }
                if ($waiting < 2) {
                    usleep(50000);
                }
            } while ($waiting < 2 && microtime(true) < $deadline);

            self::assertGreaterThanOrEqual(2, $waiting, 'Les deux commandes doivent attendre le verrou du menu.');
            $this->pdo->commit();

            $results = [];
            foreach ($workers as $worker) {
                stream_set_timeout($worker['pipes'][1], 10);
                stream_set_timeout($worker['pipes'][2], 10);
                $output = stream_get_contents($worker['pipes'][1]);
                $errors = stream_get_contents($worker['pipes'][2]);
                self::assertSame('', trim($errors));
                self::assertMatchesRegularExpression('/^\d+$/', trim($output));
                $results[] = (int) trim($output);
            }

            self::assertCount(1, array_filter($results, static fn (int $id): bool => $id > 0));
            self::assertCount(1, array_filter($results, static fn (int $id): bool => $id === 0));
            self::assertSame(0, $this->stock());
            self::assertSame($orders + 1, $this->tableCount('commandes'));
            self::assertSame($history + 1, $this->tableCount('commande_statuts'));
        } finally {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            foreach ($workers as $worker) {
                $state = proc_get_status($worker['process']);
                if ($state['running']) {
                    proc_terminate($worker['process']);
                }
                fclose($worker['pipes'][1]);
                fclose($worker['pipes'][2]);
                proc_close($worker['process']);
            }
        }
    }

    public static function acteurs(): iterable
    {
        yield 'client' => ['client'];
        yield 'employe' => ['employe'];
    }

    public static function statutsAnnulablesParEmploye(): iterable
    {
        foreach (['en_attente', 'acceptee', 'en_preparation', 'en_cours_de_livraison', 'livre', 'en_attente_retour_materiel'] as $status) {
            yield $status => [$status];
        }
    }

    #[DataProvider('statutsAnnulablesParEmploye')]
    public function testEmployeRestitueLaReservationQuelQueSoitLeStatutAnnulable(string $status): void
    {
        $orderId = $this->createOrder();
        $this->pdo->prepare('UPDATE commandes SET statut_actuel = ? WHERE id_commande = ?')->execute([$status, $orderId]);

        self::assertTrue((new OrderModel())->cancelByEmployee($orderId, self::EMPLOYEE_ID, 'email', 'Test'));
        self::assertSame(3, $this->stock());
        self::assertSame(0, $this->reserved($orderId));
    }

    public function testClientNeRecuperePasLeStockSiLaCommandeEstAcceptee(): void
    {
        $orderId = $this->createOrder();
        $this->pdo->prepare("UPDATE commandes SET statut_actuel = 'acceptee' WHERE id_commande = ?")->execute([$orderId]);

        self::assertFalse((new OrderModel())->cancelPendingForUser($orderId, self::CUSTOMER_ID, 'Trop tard'));
        self::assertSame(2, $this->stock());
        self::assertSame(1, $this->reserved($orderId));
    }

    public function testEchecInsertionRestaureLeStock(): void
    {
        try {
            (new OrderModel())->create(999999999, $this->menuId, [
                'date_prestation' => '2030-01-01', 'heure_livraison' => '12:00:00',
                'adresse_livraison' => '1 rue Test', 'code_postal_livraison' => '33000',
                'ville_livraison' => 'Bordeaux', 'distance_km' => 0.0,
                'commentaire_client' => '', 'nombre_personnes' => 2,
            ]);
            self::fail('La clé étrangère doit refuser un client inexistant.');
        } catch (\PDOException $exception) {
            self::assertSame(3, $this->stock());
        }
    }

    public function testModificationClientNeChangePasLeStock(): void
    {
        $orderId = $this->createOrder();
        $data = [
            'date_prestation' => '2030-01-02', 'heure_livraison' => '13:00:00',
            'adresse_livraison' => '2 rue Test', 'code_postal_livraison' => '33000',
            'ville_livraison' => 'Bordeaux', 'distance_km' => 0.0,
            'commentaire_client' => '', 'nombre_personnes' => 3,
        ];

        self::assertTrue((new OrderModel())->updatePendingForUser($orderId, self::CUSTOMER_ID, $data));
        self::assertSame(2, $this->stock());
        self::assertSame(1, $this->reserved($orderId));
    }

    public function testModificationClientSansChangementEstAcceptee(): void
    {
        $orderId = $this->createOrder();
        $data = $this->orderData();
        $model = new OrderModel();

        self::assertTrue($model->updatePendingForUser($orderId, self::CUSTOMER_ID, $data));
        self::assertTrue($model->updatePendingForUser($orderId, self::CUSTOMER_ID, $data));
        self::assertSame(2, $this->stock());
    }

    public function testModificationClientApresAnnulationRefuseeSansHistorique(): void
    {
        $orderId = $this->createOrder();
        self::assertTrue($this->cancel('client', $orderId));
        $history = $this->orderHistoryCount($orderId);

        self::assertFalse((new OrderModel())->updatePendingForUser($orderId, self::CUSTOMER_ID, $this->orderData()));
        self::assertSame($history, $this->orderHistoryCount($orderId));
        self::assertSame(3, $this->stock());
    }

    public function testModificationEmployeSansChangementEstAcceptee(): void
    {
        $orderId = $this->createOrder();
        $data = $this->orderData();
        $model = new OrderModel();

        self::assertTrue($model->updateByEmployeeAfterContact($orderId, self::EMPLOYEE_ID, $data, 'email', 'Contact client'));
        self::assertTrue($model->updateByEmployeeAfterContact($orderId, self::EMPLOYEE_ID, $data, 'email', 'Contact client'));
        self::assertSame(2, $this->stock());
        self::assertSame(1, $this->reserved($orderId));
    }

    public function testModificationEmployeApresAnnulationRefusee(): void
    {
        $orderId = $this->createOrder();
        self::assertTrue($this->cancel('employe', $orderId));
        $history = $this->orderHistoryCount($orderId);

        self::assertFalse((new OrderModel())->updateByEmployeeAfterContact($orderId, self::EMPLOYEE_ID, $this->orderData(), 'email', 'Trop tard'));
        self::assertSame($history, $this->orderHistoryCount($orderId));
    }

    #[DataProvider('acteurs')]
    public function testAnnulationRestitueUneSeuleFois(string $acteur): void
    {
        $orderId = $this->createOrder();
        self::assertGreaterThan(0, $orderId);
        self::assertSame(2, $this->stock());
        $history = $this->orderHistoryCount($orderId);

        self::assertTrue($this->cancel($acteur, $orderId));
        self::assertSame(3, $this->stock());
        self::assertSame($history + 1, $this->orderHistoryCount($orderId));
        self::assertFalse($this->cancel($acteur, $orderId));
        self::assertSame(3, $this->stock());
        self::assertSame($history + 1, $this->orderHistoryCount($orderId));
    }

    #[DataProvider('acteurs')]
    public function testCommandeHistoriqueNonReserveeNeRestituePas(string $acteur): void
    {
        $statement = $this->pdo->prepare("INSERT INTO commandes (id_utilisateur, id_menu, date_prestation, heure_livraison, adresse_livraison, code_postal_livraison, ville_livraison, nombre_personnes, prix_menu, prix_total) VALUES (?, ?, '2030-01-01', '12:00:00', '1 rue Test', '33000', 'Bordeaux', 2, 80, 80)");
        $statement->execute([self::CUSTOMER_ID, $this->menuId]);
        $orderId = (int) $this->pdo->lastInsertId();

        self::assertSame(0, $this->reserved($orderId));
        self::assertTrue($this->cancel($acteur, $orderId));
        self::assertSame(3, $this->stock());
        self::assertSame(1, $this->orderHistoryCount($orderId));
        self::assertFalse($this->cancel($acteur, $orderId));
        self::assertSame(3, $this->stock());
    }

    public function testAnnulationsRefuseesNeRestituentPas(): void
    {
        $orderId = $this->createOrder();
        self::assertGreaterThan(0, $orderId);
        $model = new OrderModel();
        $history = $this->orderHistoryCount($orderId);

        self::assertFalse($model->cancelPendingForUser($orderId, self::ADMIN_ID, 'Autre utilisateur'));
        self::assertFalse($model->cancelByEmployee($orderId, self::EMPLOYEE_ID, 'invalide', 'Motif'));
        self::assertFalse($model->cancelByEmployee($orderId, self::EMPLOYEE_ID, 'email', ''));
        $this->pdo->prepare("UPDATE commandes SET statut_actuel = 'terminee' WHERE id_commande = ?")->execute([$orderId]);
        self::assertFalse($model->cancelPendingForUser($orderId, self::CUSTOMER_ID, 'Trop tard'));
        self::assertFalse($model->cancelByEmployee($orderId, self::EMPLOYEE_ID, 'email', 'Trop tard'));
        self::assertSame(2, $this->stock());
        self::assertSame(1, $this->reserved($orderId));
        self::assertSame($history, $this->orderHistoryCount($orderId));
    }

    private function createOrder(): int
    {
        return (new OrderModel())->create(self::CUSTOMER_ID, $this->menuId, [
            'date_prestation' => '2030-01-01',
            'heure_livraison' => '12:00:00',
            'adresse_livraison' => '1 rue Test',
            'code_postal_livraison' => '33000',
            'ville_livraison' => 'Bordeaux',
            'distance_km' => 0.0,
            'commentaire_client' => '',
            'nombre_personnes' => 2,
        ]);
    }

    /**
     * @return array{date_prestation: string, heure_livraison: string, adresse_livraison: string, code_postal_livraison: string, ville_livraison: string, distance_km: float, commentaire_client: string, nombre_personnes: int}
     */
    private function orderData(): array
    {
        return [
            'date_prestation' => '2030-01-02', 'heure_livraison' => '13:00:00',
            'adresse_livraison' => '2 rue Test', 'code_postal_livraison' => '33000',
            'ville_livraison' => 'Bordeaux', 'distance_km' => 0.0,
            'commentaire_client' => '', 'nombre_personnes' => 3,
        ];
    }

    private function cancel(string $acteur, int $orderId): bool
    {
        $model = new OrderModel();

        return $acteur === 'client'
            ? $model->cancelPendingForUser($orderId, self::CUSTOMER_ID, 'Test')
            : $model->cancelByEmployee($orderId, self::EMPLOYEE_ID, 'email', 'Test');
    }

    private function setStock(int $stock): void
    {
        $this->pdo->prepare('UPDATE menus SET stock_disponible = ? WHERE id_menu = ?')->execute([$stock, $this->menuId]);
    }

    private function stock(): int
    {
        $statement = $this->pdo->prepare('SELECT stock_disponible FROM menus WHERE id_menu = ?');
        $statement->execute([$this->menuId]);
        return (int) $statement->fetchColumn();
    }

    private function reserved(int $orderId): int
    {
        $statement = $this->pdo->prepare('SELECT stock_reserve FROM commandes WHERE id_commande = ?');
        $statement->execute([$orderId]);
        return (int) $statement->fetchColumn();
    }

    private function orderHistoryCount(int $orderId): int
    {
        $statement = $this->pdo->prepare('SELECT COUNT(*) FROM commande_statuts WHERE id_commande = ?');
        $statement->execute([$orderId]);
        return (int) $statement->fetchColumn();
    }

    private function tableCount(string $table): int
    {
        return (int) $this->pdo->query('SELECT COUNT(*) FROM ' . $table)->fetchColumn();
    }
}
