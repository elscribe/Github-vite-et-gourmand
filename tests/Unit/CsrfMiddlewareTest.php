<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Core\Session;
use App\Middlewares\CsrfMiddleware;
use PHPUnit\Framework\TestCase;

/**
 * Vérifie le garde-fou CSRF sans démarrer la base de données.
 */
final class CsrfMiddlewareTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST = [];
        $_SESSION = [];
        http_response_code(200);
        Session::start();
    }

    protected function tearDown(): void
    {
        $_POST = [];
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SESSION = [];
        parent::tearDown();
    }

    public function testRequeteGetBypasseLeControleCsrf(): void
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';

        self::assertTrue((new CsrfMiddleware())->handle());
    }

    public function testJetonValideAutoriseLaRequetePost(): void
    {
        $_SESSION['_csrf_token'] = 'token-attendu';
        $_POST['_csrf_token'] = 'token-attendu';

        self::assertTrue((new CsrfMiddleware())->handle());
    }

    public function testJetonAbsentRefuseLaRequetePost(): void
    {
        ob_start();
        $handled = (new CsrfMiddleware())->handle();
        $output = ob_get_clean();

        self::assertFalse($handled);
        self::assertSame(403, http_response_code());
        self::assertStringContainsString('Requete invalide', $output);
    }

    public function testJetonIncorrectRefuseLaRequetePost(): void
    {
        $_SESSION['_csrf_token'] = 'token-attendu';
        $_POST['_csrf_token'] = 'token-invalide';

        ob_start();
        $handled = (new CsrfMiddleware())->handle();
        $output = ob_get_clean();

        self::assertFalse($handled);
        self::assertSame(403, http_response_code());
        self::assertStringContainsString('Requete invalide', $output);
    }

    public function testJetonPartielRefuseLaRequetePost(): void
    {
        $_SESSION['_csrf_token'] = 'token-attendu-complet';
        $_POST['_csrf_token'] = 'token-attendu';

        ob_start();
        $handled = (new CsrfMiddleware())->handle();
        $output = ob_get_clean();

        self::assertFalse($handled);
        self::assertSame(403, http_response_code());
        self::assertStringContainsString('Requete invalide', $output);
    }

    public function testJetonEnvoyeSansJetonDeSessionRefuseLaRequetePost(): void
    {
        $_POST['_csrf_token'] = 'token-envoye';

        ob_start();
        $handled = (new CsrfMiddleware())->handle();
        $output = ob_get_clean();

        self::assertFalse($handled);
        self::assertSame(403, http_response_code());
        self::assertStringContainsString('Requete invalide', $output);
    }
}
