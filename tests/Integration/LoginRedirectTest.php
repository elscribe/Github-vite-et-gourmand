<?php

declare(strict_types=1);

namespace Tests\Integration;

use PHPUnit\Framework\Attributes\DataProvider;
use RuntimeException;
use Tests\Support\TestDatabase;
use Throwable;

/**
 * Vérifie le parcours HTTP réel de connexion et ses redirections par rôle.
 *
 * Response::redirect() appelle exit : ce test démarre donc le point d'entrée
 * public/index.php avec php -S et observe la réponse HTTP initiale.
 */
final class LoginRedirectTest extends DatabaseTestCase
{
    private const CLIENT_EMAIL = 'claire.martin@example.test';
    private const CLIENT_PASSWORD = 'ClientVite2026!';
    private const EMPLOYEE_EMAIL = 'lucas.employee@vitegourmand.test';
    private const EMPLOYEE_PASSWORD = 'EmployeVite2026!';
    private const ADMIN_EMAIL = 'admin.jose@vitegourmand.test';
    private const ADMIN_PASSWORD = 'AdminVite2026!';

    /** @var resource|null */
    private static $serverProcess = null;

    /** @var array<int, array{0: resource, 1: resource}> */
    private static array $serverPipes = [];

    private static ?string $skipReason = null;
    private static int $serverPort = 0;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        try {
            TestDatabase::name();
            self::startServer();
        } catch (Throwable $exception) {
            self::$skipReason = 'Test HTTP indisponible : ' . $exception->getMessage();
            self::stopServer();
            TestDatabase::drop();
        }
    }

    public static function tearDownAfterClass(): void
    {
        self::stopServer();
        TestDatabase::drop();

        parent::tearDownAfterClass();
    }

    /**
     * @param string $email
     * @param string $password
     * @param string $location
     */
    #[DataProvider('authenticatedAccounts')]
    public function testConnexionRedirigeChaqueRoleVersSonEspace(
        string $email,
        string $password,
        string $location,
    ): void {
        $this->skipWhenUnavailable();

        $response = $this->login($email, $password);

        self::assertSame(302, $response['status']);
        self::assertSame($location, $response['headers']['location'] ?? null);
    }

    public function testCompteEmployeDesactiveResteSurLeFormulaire(): void
    {
        $this->skipWhenUnavailable();

        $this->setActive(self::EMPLOYEE_ID, false);

        try {
            $response = $this->login(self::EMPLOYEE_EMAIL, self::EMPLOYEE_PASSWORD);

            self::assertSame(200, $response['status']);
            self::assertArrayNotHasKey('location', $response['headers']);
            self::assertStringContainsString('Identifiants invalides.', $response['body']);
        } finally {
            $this->setActive(self::EMPLOYEE_ID, true);
        }
    }

    /**
     * @return array<string, array{string, string, string}>
     */
    public static function authenticatedAccounts(): array
    {
        return [
            'client' => [self::CLIENT_EMAIL, self::CLIENT_PASSWORD, '/mon-compte'],
            'employe' => [self::EMPLOYEE_EMAIL, self::EMPLOYEE_PASSWORD, '/employe'],
            'administrateur' => [self::ADMIN_EMAIL, self::ADMIN_PASSWORD, '/admin'],
        ];
    }

    private function skipWhenUnavailable(): void
    {
        if (self::$skipReason !== null) {
            self::markTestSkipped(self::$skipReason);
        }
    }

    /**
     * @return array{status: int, headers: array<string, string>, body: string}
     */
    private function login(string $email, string $password): array
    {
        $cookieJar = tempnam(sys_get_temp_dir(), 'vg-login-cookie-');

        if ($cookieJar === false) {
            self::fail('Impossible de créer le cookie jar du test HTTP.');
        }

        try {
            $loginPage = $this->request('GET', '/connexion', [], $cookieJar);
            self::assertSame(200, $loginPage['status']);

            if (preg_match('/name="_csrf_token" value="([^"]+)"/', $loginPage['body'], $matches) !== 1) {
                self::fail('Jeton CSRF absent du formulaire de connexion.');
            }

            return $this->request(
                'POST',
                '/connexion',
                [
                    '_csrf_token' => $matches[1],
                    'email' => $email,
                    'password' => $password,
                ],
                $cookieJar,
            );
        } finally {
            if (is_file($cookieJar)) {
                unlink($cookieJar);
            }
        }
    }

    /**
     * @param array<string, string> $fields
     * @return array{status: int, headers: array<string, string>, body: string}
     */
    private function request(string $method, string $path, array $fields, string $cookieJar): array
    {
        $command = [
            'curl',
            '--silent',
            '--show-error',
            '--include',
            '--max-time',
            '10',
            '--request',
            $method,
            '--cookie',
            $cookieJar,
            '--cookie-jar',
            $cookieJar,
        ];

        foreach ($fields as $name => $value) {
            $command[] = '--data-urlencode';
            $command[] = $name . '=' . $value;
        }

        $command[] = sprintf('http://127.0.0.1:%d%s', self::$serverPort, $path);
        $pipes = [];
        $process = proc_open(
            implode(' ', array_map('escapeshellarg', $command)),
            [1 => ['pipe', 'w'], 2 => ['pipe', 'w']],
            $pipes,
        );

        if (!is_resource($process)) {
            self::fail('Impossible de démarrer le client HTTP du test.');
        }

        $rawResponse = stream_get_contents($pipes[1]);
        $error = stream_get_contents($pipes[2]);
        fclose($pipes[1]);
        fclose($pipes[2]);
        $exitCode = proc_close($process);

        if ($exitCode !== 0) {
            self::fail('La requête HTTP de test a échoué : ' . trim($error));
        }

        $separator = strpos($rawResponse, "\r\n\r\n");
        $headerBlock = $separator === false ? $rawResponse : substr($rawResponse, 0, $separator);
        $body = $separator === false ? '' : substr($rawResponse, $separator + 4);
        $headers = [];

        foreach (explode("\r\n", $headerBlock) as $line) {
            if (!str_contains($line, ':')) {
                continue;
            }

            [$name, $value] = explode(':', $line, 2);
            $headers[strtolower(trim($name))] = trim($value);
        }

        preg_match('/HTTP\/\d(?:\.\d)?\s+(\d{3})/', $headerBlock, $statusMatch);

        return [
            'status' => (int) ($statusMatch[1] ?? 0),
            'headers' => $headers,
            'body' => $body,
        ];
    }

    private static function startServer(): void
    {
        if (trim((string) shell_exec('command -v curl')) === '') {
            throw new RuntimeException('curl est nécessaire pour le test HTTP.');
        }

        $socket = stream_socket_server('tcp://127.0.0.1:0', $errorCode, $errorMessage);

        if (!is_resource($socket)) {
            throw new RuntimeException('Port local de test indisponible : ' . $errorMessage);
        }

        $address = stream_socket_get_name($socket, false);
        fclose($socket);
        self::$serverPort = (int) substr(strrchr((string) $address, ':'), 1);

        $projectRoot = dirname(__DIR__, 2);
        $environment = is_array(getenv()) ? getenv() : [];
        $environment['DB_NAME'] = TestDatabase::name();
        $environment['APP_ENV'] = 'testing';
        $environment['APP_DEBUG'] = '0';
        $environment['APP_DISPLAY_ERRORS'] = '0';
        $environment['APP_LOG_ERRORS'] = '0';
        $environment['SESSION_NAME'] = 'vg_test_session_' . bin2hex(random_bytes(4));

        // Tableau d'arguments : PHP est lance sans shell intermediaire, donc
        // proc_terminate() arrete bien le serveur (sous Linux, un "sh -c"
        // restait seul tue et laissait le serveur tourner).
        // La sortie du serveur est ignoree pour ne jamais remplir un tube.
        $pipes = [];
        self::$serverProcess = proc_open(
            [PHP_BINARY, '-S', '127.0.0.1:' . self::$serverPort, '-t', 'public', 'public/index.php'],
            [0 => ['file', '/dev/null', 'r'], 1 => ['file', '/dev/null', 'w'], 2 => ['file', '/dev/null', 'w']],
            $pipes,
            $projectRoot,
            $environment,
        );

        if (!is_resource(self::$serverProcess)) {
            throw new RuntimeException('Démarrage du serveur HTTP de test impossible.');
        }

        $deadline = microtime(true) + 3.0;

        do {
            $probe = @fsockopen('127.0.0.1', self::$serverPort, $probeError, $probeMessage, 0.1);

            if (is_resource($probe)) {
                fclose($probe);
                return;
            }

            usleep(50_000);
        } while (microtime(true) < $deadline);

        throw new RuntimeException('Serveur HTTP de test non disponible.');
    }

    private static function stopServer(): void
    {
        if (is_resource(self::$serverProcess)) {
            proc_terminate(self::$serverProcess);
            proc_close(self::$serverProcess);
        }

        foreach (self::$serverPipes as $pipe) {
            if (is_resource($pipe)) {
                fclose($pipe);
            }
        }

        self::$serverPipes = [];
        self::$serverProcess = null;
        self::$serverPort = 0;
    }
}
