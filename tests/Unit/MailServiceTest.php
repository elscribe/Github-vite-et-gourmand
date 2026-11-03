<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Services\MailService;
use PHPUnit\Framework\TestCase;

/**
 * Un e-mail est prepare apres l'enregistrement d'une action :
 * son echec ne doit pas interrompre la page.
 */
final class MailServiceTest extends TestCase
{
    private string $directory;
    private string|false $previousMailer;
    private string|false $previousLogPath;

    protected function setUp(): void
    {
        $this->directory = sys_get_temp_dir() . '/vg-mail-' . bin2hex(random_bytes(4));
        mkdir($this->directory, 0700);
        $this->previousMailer = getenv('MAIL_MAILER');
        $this->previousLogPath = getenv('MAIL_LOG_PATH');
        putenv('MAIL_MAILER=log');
    }

    protected function tearDown(): void
    {
        putenv($this->previousMailer === false ? 'MAIL_MAILER' : 'MAIL_MAILER=' . $this->previousMailer);
        putenv($this->previousLogPath === false ? 'MAIL_LOG_PATH' : 'MAIL_LOG_PATH=' . $this->previousLogPath);

        foreach (glob($this->directory . '/*') ?: [] as $file) {
            unlink($file);
        }
        rmdir($this->directory);
    }

    public function testEmailEcritDansLeJournal(): void
    {
        putenv('MAIL_LOG_PATH=' . $this->directory . '/mail.log');

        self::assertTrue((new MailService())->send('client@example.test', 'Sujet test', 'Corps test'));
        self::assertStringContainsString('SUBJECT: Sujet test', (string) file_get_contents($this->directory . '/mail.log'));
    }

    public function testJournalInaccessibleNInterromptPasLAction(): void
    {
        putenv('MAIL_LOG_PATH=' . $this->directory . '/dossier-absent/mail.log');

        // L'echec est signale dans le journal d'erreurs PHP, sans exception.
        $this->expectErrorLog();

        self::assertFalse((new MailService())->send('client@example.test', 'Sujet test', 'Corps test'));
        self::assertFileDoesNotExist($this->directory . '/dossier-absent/mail.log');
    }
}
