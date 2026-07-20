<?php

declare(strict_types=1);

namespace App\Services;

/**
 * Centralise les notifications email.
 *
 * En local, le mode "log" ecrit les emails dans storage/logs/mail.log.
 * Cela permet de tester les notifications sans serveur SMTP.
 */
final class MailService
{
    private string $mailer;
    private string $logPath;
    private string $fromAddress;
    private string $fromName;

    public function __construct()
    {
        $this->mailer = getenv('MAIL_MAILER') ?: 'log';
        $this->logPath = dirname(__DIR__, 2) . '/storage/logs/mail.log';
        $this->fromAddress = getenv('MAIL_FROM_ADDRESS') ?: 'contact@vitegourmand.test';
        $this->fromName = getenv('MAIL_FROM_NAME') ?: 'Vite & Gourmand';
    }

    public function send(string $to, string $subject, string $body): void
    {
        if ($this->mailer === 'log') {
            $this->writeLog($to, $subject, $body);
            return;
        }

        $this->writeLog($to, '[SMTP non configure] ' . $subject, $body);
    }

    private function writeLog(string $to, string $subject, string $body): void
    {
        $entry = [
            'date' => date('Y-m-d H:i:s'),
            'from' => $this->fromName . ' <' . $this->fromAddress . '>',
            'to' => $to,
            'subject' => $subject,
            'body' => $body,
        ];

        $content = "---- EMAIL ----\n";
        foreach ($entry as $key => $value) {
            $content .= strtoupper($key) . ': ' . $value . "\n";
        }
        $content .= "\n";

        file_put_contents($this->logPath, $content, FILE_APPEND | LOCK_EX);
    }
}
