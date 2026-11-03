<?php

declare(strict_types=1);

namespace Tests\Integration;

use Tests\Support\TestDatabase;

/**
 * Controle d'acces relu en base (AuthMiddleware, RoleMiddleware).
 *
 * Chaque cas tourne dans un processus PHP separe car une redirection appelle exit.
 */
final class MiddlewareAccessTest extends DatabaseTestCase
{
    /**
     * @return array<string, mixed>
     */
    private function runMiddleware(string $middleware, int $userId, string $roles = ''): array
    {
        $command = sprintf(
            'DB_NAME=%s %s %s %s %d %s 2>&1',
            escapeshellarg(TestDatabase::name()),
            escapeshellarg(PHP_BINARY),
            escapeshellarg(dirname(__DIR__) . '/Support/run_middleware.php'),
            escapeshellarg($middleware),
            $userId,
            $roles === '' ? '' : escapeshellarg($roles)
        );

        $output = (string) shell_exec($command);
        $result = json_decode($output, true);
        self::assertIsArray($result, 'Sortie inattendue : ' . $output);

        return $result;
    }

    protected function tearDown(): void
    {
        if (!isset($this->pdo)) {
            return;
        }

        $this->setActive(self::EMPLOYEE_ID, true);
    }

    public function testCompteActifAccepteEtRoleNormaliseEnSession(): void
    {
        $result = $this->runMiddleware('auth', self::EMPLOYEE_ID);

        self::assertTrue($result['handled']);
        self::assertSame('employe', $result['session_role']);
    }

    public function testCompteDesactiveRefuseParAuthMiddleware(): void
    {
        $this->setActive(self::EMPLOYEE_ID, false);
        $result = $this->runMiddleware('auth', self::EMPLOYEE_ID);

        self::assertNull($result['handled'], 'La requete doit etre interrompue par une redirection.');
        self::assertSame(302, $result['http']);
        self::assertNull($result['session_user_id']);
    }

    public function testCompteDesactiveRefuseParRoleMiddleware(): void
    {
        $this->setActive(self::EMPLOYEE_ID, false);
        $result = $this->runMiddleware('role', self::EMPLOYEE_ID, 'employe,administrateur');

        self::assertNull($result['handled']);
        self::assertSame(302, $result['http']);
        self::assertNull($result['session_user_id']);
    }

    public function testEmployeAccedeAuxRoutesEmploye(): void
    {
        $result = $this->runMiddleware('role', self::EMPLOYEE_ID, 'employe,administrateur');

        self::assertTrue($result['handled']);
        self::assertSame('employe', $result['session_role']);
    }

    public function testAdministrateurAccedeAuxRoutesAdministrateur(): void
    {
        $result = $this->runMiddleware('role', self::ADMIN_ID, 'administrateur');

        self::assertTrue($result['handled']);
        self::assertSame('administrateur', $result['session_role']);
    }

    public function testClientRefuseSurRouteEmploye(): void
    {
        $result = $this->runMiddleware('role', self::CUSTOMER_ID, 'employe,administrateur');

        self::assertFalse($result['handled']);
        self::assertSame(403, $result['http']);
        self::assertSame(self::CUSTOMER_ID, $result['session_user_id'], 'Un role insuffisant ne deconnecte pas.');
    }

    public function testEmployeRefuseSurRouteAdministrateur(): void
    {
        $result = $this->runMiddleware('role', self::EMPLOYEE_ID, 'administrateur');

        self::assertFalse($result['handled']);
        self::assertSame(403, $result['http']);
    }
}
