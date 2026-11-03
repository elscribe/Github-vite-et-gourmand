<?php

declare(strict_types=1);

namespace Tests\Integration;

use PHPUnit\Framework\TestCase;
use RuntimeException;
use Tests\Support\TestDatabase;

/** Parcours HTTP réel : un menu épuisé ne peut pas être commandé. */
final class OrderStockHttpTest extends TestCase
{
    public function testMenuEpuiseEtCommandeForgeeRefusee(): void
    {
        $server = null;
        $cookieFile = false;
        $sessionDirectory = null;
        $menuId = 0;

        try {
            try {
                $pdo = TestDatabase::pdo();
            } catch (RuntimeException $exception) {
                self::markTestSkipped($exception->getMessage());
            }

            $pdo->exec("INSERT INTO menus (id_regime, id_theme, titre, description, conditions, nombre_personnes_minimum, prix_minimum, stock_disponible, actif)
                VALUES (1, 1, 'Menu HTTP stock epuise', 'Test', 'Test', 2, 80, 0, 1)");
            $menuId = (int) $pdo->lastInsertId();

            $cookieFile = tempnam(sys_get_temp_dir(), 'vg-stock-cookie-');
            if ($cookieFile === false) {
                throw new RuntimeException('Création du fichier de cookies impossible.');
            }
            $sessionDirectory = sys_get_temp_dir() . '/vg-stock-sessions-' . bin2hex(random_bytes(8));
            if (!mkdir($sessionDirectory, 0700)) {
                throw new RuntimeException('Création du dossier de sessions impossible.');
            }

            $socket = stream_socket_server('tcp://127.0.0.1:0', $errorCode, $errorMessage);
            if (!is_resource($socket)) {
                throw new RuntimeException('Port local indisponible : ' . $errorMessage);
            }
            $address = stream_socket_get_name($socket, false);
            fclose($socket);
            $port = (int) substr(strrchr((string) $address, ':'), 1);

            $environment = is_array(getenv()) ? getenv() : [];
            $environment['DB_NAME'] = TestDatabase::name();
            $environment['APP_ENV'] = 'testing';
            $environment['APP_DEBUG'] = '0';
            $environment['APP_DISPLAY_ERRORS'] = '0';
            $environment['APP_LOG_ERRORS'] = '0';
            $environment['SESSION_NAME'] = 'vg_stock_' . bin2hex(random_bytes(4));
            $environment['MAIL_MAILER'] = 'log';
            $environment['MAIL_LOG_PATH'] = $sessionDirectory . '/mail.log';
            $server = proc_open(
                [PHP_BINARY, '-d', 'session.save_path=' . $sessionDirectory, '-S', '127.0.0.1:' . $port, '-t', 'public', 'public/index.php'],
                [0 => ['file', '/dev/null', 'r'], 1 => ['file', '/dev/null', 'w'], 2 => ['file', '/dev/null', 'w']],
                $pipes,
                dirname(__DIR__, 2),
                $environment,
            );
            if (!is_resource($server)) {
                throw new RuntimeException('Démarrage du serveur HTTP impossible.');
            }

            $deadline = microtime(true) + 3;
            $ready = false;
            do {
                if (!proc_get_status($server)['running']) {
                    throw new RuntimeException('Le serveur HTTP de test s’est arrêté au démarrage.');
                }
                $probe = @fsockopen('127.0.0.1', $port, $probeError, $probeMessage, 0.1);
                if (is_resource($probe)) {
                    fclose($probe);
                    $ready = true;
                    break;
                }
                usleep(50000);
            } while (microtime(true) < $deadline);
            if (!$ready) {
                throw new RuntimeException('Le serveur HTTP de test ne répond pas.');
            }

            $detail = $this->request($port, 'GET', '/menus/' . $menuId, [], $cookieFile);
            self::assertSame(200, $detail['status']);
            self::assertStringContainsString('Menu HTTP stock epuise', $detail['body']);
            self::assertStringContainsString('Ce menu est épuisé.', $detail['body']);
            self::assertStringNotContainsString('Commander ce menu', $detail['body']);
            self::assertStringNotContainsString('/commandes/creation/' . $menuId, $detail['body']);

            $loginPage = $this->request($port, 'GET', '/connexion', [], $cookieFile);
            self::assertSame(200, $loginPage['status']);
            $login = $this->request($port, 'POST', '/connexion', [
                '_csrf_token' => $this->token($loginPage['body']),
                'email' => 'claire.martin@example.test',
                'password' => 'ClientVite2026!',
            ], $cookieFile);
            self::assertSame(302, $login['status']);
            self::assertSame('/mon-compte', $login['headers']['location'] ?? null);

            $form = $this->request($port, 'GET', '/commandes/creation', [], $cookieFile);
            self::assertSame(200, $form['status']);
            self::assertStringContainsString('action="/commandes"', $form['body']);
            $ordersBefore = (int) $pdo->query('SELECT COUNT(*) FROM commandes')->fetchColumn();
            $historyBefore = (int) $pdo->query('SELECT COUNT(*) FROM commande_statuts')->fetchColumn();

            // Le POST contourne volontairement l'option désactivée du formulaire.
            $fields = [
                '_csrf_token' => $this->token($form['body']),
                'id_menu' => (string) $menuId,
                'date_prestation' => (new \DateTimeImmutable('+7 days'))->format('Y-m-d'),
                'heure_livraison' => '12:00',
                'adresse_livraison' => '1 rue du Test',
                'code_postal_livraison' => '33000',
                'ville_livraison' => 'Bordeaux',
                'distance_km' => '0',
                'commentaire_client' => '',
                'nombre_personnes' => '2',
            ];
            $response = $this->request($port, 'POST', '/commandes', $fields, $cookieFile);

            self::assertSame(200, $response['status']);
            self::assertStringContainsString('Ce menu n’est plus disponible.', html_entity_decode($response['body'], ENT_QUOTES | ENT_HTML5, 'UTF-8'));
            self::assertSame($ordersBefore, (int) $pdo->query('SELECT COUNT(*) FROM commandes')->fetchColumn());
            self::assertSame($historyBefore, (int) $pdo->query('SELECT COUNT(*) FROM commande_statuts')->fetchColumn());
            $stock = $pdo->prepare('SELECT stock_disponible FROM menus WHERE id_menu = ?');
            $stock->execute([$menuId]);
            self::assertSame(0, (int) $stock->fetchColumn());

            // Une place est rendue disponible : la même commande peut alors être créée.
            $pdo->prepare('UPDATE menus SET stock_disponible = 1 WHERE id_menu = ?')->execute([$menuId]);
            $fields['_csrf_token'] = $this->token($response['body']);
            $success = $this->request($port, 'POST', '/commandes', $fields, $cookieFile);
            self::assertSame(302, $success['status']);
            self::assertMatchesRegularExpression('#^/commandes/\d+$#', $success['headers']['location'] ?? '');
            self::assertSame($ordersBefore + 1, (int) $pdo->query('SELECT COUNT(*) FROM commandes')->fetchColumn());
            self::assertSame($historyBefore + 1, (int) $pdo->query('SELECT COUNT(*) FROM commande_statuts')->fetchColumn());
            $stock->execute([$menuId]);
            self::assertSame(0, (int) $stock->fetchColumn());

            // Un journal indisponible ne doit pas masquer une commande déjà enregistrée.
            $pdo->prepare('UPDATE menus SET stock_disponible = 1 WHERE id_menu = ?')->execute([$menuId]);
            $mailLog = $sessionDirectory . '/mail.log';
            if (is_file($mailLog)) {
                unlink($mailLog);
            }
            mkdir($mailLog);
            $afterMailFailure = $this->request($port, 'POST', '/commandes', $fields, $cookieFile);
            self::assertSame(302, $afterMailFailure['status']);
            self::assertMatchesRegularExpression('#^/commandes/\d+$#', $afterMailFailure['headers']['location'] ?? '');
            self::assertSame($ordersBefore + 2, (int) $pdo->query('SELECT COUNT(*) FROM commandes')->fetchColumn());
            $stock->execute([$menuId]);
            self::assertSame(0, (int) $stock->fetchColumn());
            $detailAfterMailFailure = $this->request($port, 'GET', $afterMailFailure['headers']['location'], [], $cookieFile);
            self::assertStringContainsString('notification n’a pas pu être préparée', html_entity_decode($detailAfterMailFailure['body'], ENT_QUOTES | ENT_HTML5, 'UTF-8'));
        } finally {
            if (is_resource($server)) {
                proc_terminate($server);
                proc_close($server);
            }
            if ($cookieFile !== false && is_file($cookieFile)) {
                unlink($cookieFile);
            }
            if ($sessionDirectory !== null && is_dir($sessionDirectory)) {
                foreach (glob($sessionDirectory . '/*') ?: [] as $sessionFile) {
                    is_dir($sessionFile) ? rmdir($sessionFile) : unlink($sessionFile);
                }
                rmdir($sessionDirectory);
            }
            TestDatabase::drop();
        }
    }

    private function token(string $body): string
    {
        if (preg_match('/name="_csrf_token" value="([^"]+)"/', $body, $matches) !== 1) {
            self::fail('Jeton CSRF absent du formulaire.');
        }
        return html_entity_decode($matches[1], ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }

    /** @param array<string, string> $fields
     * @return array{status: int, headers: array<string, string>, body: string}
     */
    private function request(int $port, string $method, string $path, array $fields, string $cookieFile): array
    {
        $command = ['curl', '--silent', '--show-error', '--include', '--max-time', '10',
            '--request', $method, '--cookie', $cookieFile, '--cookie-jar', $cookieFile];
        foreach ($fields as $name => $value) {
            $command[] = '--data-urlencode';
            $command[] = $name . '=' . $value;
        }
        $command[] = 'http://127.0.0.1:' . $port . $path;
        $process = proc_open($command, [1 => ['pipe', 'w'], 2 => ['pipe', 'w']], $pipes);
        if (!is_resource($process)) {
            throw new RuntimeException('Démarrage du client HTTP impossible.');
        }
        try {
            $raw = stream_get_contents($pipes[1]);
            $error = stream_get_contents($pipes[2]);
        } finally {
            fclose($pipes[1]);
            fclose($pipes[2]);
            $exitCode = proc_close($process);
        }
        if ($exitCode !== 0 || $raw === false) {
            throw new RuntimeException('Requête HTTP échouée : ' . trim((string) $error));
        }
        $separator = strpos($raw, "\r\n\r\n");
        if ($separator === false) {
            throw new RuntimeException('Réponse HTTP invalide.');
        }
        $headerBlock = substr($raw, 0, $separator);
        $headers = [];
        foreach (explode("\r\n", $headerBlock) as $line) {
            if (str_contains($line, ':')) {
                [$name, $value] = explode(':', $line, 2);
                $headers[strtolower(trim($name))] = trim($value);
            }
        }
        preg_match('/HTTP\/\d(?:\.\d)?\s+(\d{3})/', $headerBlock, $status);
        return ['status' => (int) ($status[1] ?? 0), 'headers' => $headers, 'body' => substr($raw, $separator + 4)];
    }
}
