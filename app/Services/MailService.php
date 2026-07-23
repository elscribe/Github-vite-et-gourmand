<?php

declare(strict_types=1);

namespace App\Services;

/**
 * Centralise les notifications email.
 *
 * Mode "log" (par defaut, utilise en production/Fly.io) : ecrit les emails
 * dans storage/logs/mail.log, sans les envoyer reellement.
 *
 * Mode "smtp" : envoie reellement l'email via un serveur SMTP (par exemple
 * Mailtrap en sandbox de test), en plus de garder la trace dans le log.
 * Si l'envoi SMTP echoue pour une raison quelconque (reseau, identifiants),
 * l'erreur est capturee et notee dans le log : cela ne doit jamais bloquer
 * une inscription, une commande ou un message de contact.
 */
final class MailService
{
    private string $mailer;
    private string $logPath;
    private string $fromAddress;
    private string $fromName;
    private string $host;
    private int $port;
    private string $username;
    private string $password;

    public function __construct()
    {
        $this->mailer = getenv('MAIL_MAILER') ?: 'log';
        $this->logPath = dirname(__DIR__, 2) . '/storage/logs/mail.log';
        $this->fromAddress = getenv('MAIL_FROM_ADDRESS') ?: 'contact@vitegourmand.test';
        $this->fromName = getenv('MAIL_FROM_NAME') ?: 'Vite & Gourmand';
        $this->host = getenv('MAIL_HOST') ?: 'localhost';
        $this->port = (int) (getenv('MAIL_PORT') ?: 1025);
        $this->username = getenv('MAIL_USERNAME') ?: '';
        $this->password = getenv('MAIL_PASSWORD') ?: '';
    }

    public function send(string $to, string $subject, string $body): void
    {
        // On garde toujours une trace dans le log, meme en mode SMTP :
        // c'est la preuve locale utilisee pendant les tests et la recette.
        $this->writeLog($to, $subject, $body);

        if ($this->mailer !== 'smtp') {
            return;
        }

        try {
            $this->sendSmtp($to, $subject, $body);
        } catch (\Throwable $exception) {
            $this->writeLog(
                $to,
                '[SMTP echec, repli sur le log] ' . $subject,
                'Erreur lors de l\'envoi reel : ' . $exception->getMessage()
            );
        }
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

    /**
     * Envoie un email reel via une connexion SMTP simple (sans dependance
     * externe). Suffisant pour un serveur d'envoi de test comme Mailtrap :
     * connexion, STARTTLS, authentification, puis envoi du message.
     */
    private function sendSmtp(string $to, string $subject, string $body): void
    {
        $useImplicitTls = $this->port === 465;
        $address = ($useImplicitTls ? 'ssl://' : '') . $this->host . ':' . $this->port;

        $socket = @stream_socket_client($address, $errno, $errstr, 10);
        if ($socket === false) {
            throw new \RuntimeException('Connexion SMTP impossible : ' . $errstr);
        }

        stream_set_timeout($socket, 10);

        try {
            $this->readSmtpResponse($socket, 220);
            $this->sendSmtpCommand($socket, 'EHLO vitegourmand.local', 250);

            if (!$useImplicitTls) {
                $this->sendSmtpCommand($socket, 'STARTTLS', 220);
                if (!stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_TLS_CLIENT)) {
                    throw new \RuntimeException('Impossible de demarrer le chiffrement TLS.');
                }
                $this->sendSmtpCommand($socket, 'EHLO vitegourmand.local', 250);
            }

            if ($this->username !== '') {
                $this->sendSmtpCommand($socket, 'AUTH LOGIN', 334);
                $this->sendSmtpCommand($socket, base64_encode($this->username), 334);
                $this->sendSmtpCommand($socket, base64_encode($this->password), 235);
            }

            $this->sendSmtpCommand($socket, 'MAIL FROM:<' . $this->fromAddress . '>', 250);
            $this->sendSmtpCommand($socket, 'RCPT TO:<' . $to . '>', 250);
            $this->sendSmtpCommand($socket, 'DATA', 354);

            $headers = [
                'From: ' . $this->fromName . ' <' . $this->fromAddress . '>',
                'To: <' . $to . '>',
                'Subject: ' . $subject,
                'MIME-Version: 1.0',
                'Content-Type: text/plain; charset=UTF-8',
                'Date: ' . date('r'),
            ];
            $message = implode("\r\n", $headers) . "\r\n\r\n" . str_replace("\n", "\r\n", $body) . "\r\n.";
            $this->sendSmtpCommand($socket, $message, 250);

            $this->sendSmtpCommand($socket, 'QUIT', 221);
        } finally {
            fclose($socket);
        }
    }

    /**
     * @param resource $socket
     */
    private function sendSmtpCommand($socket, string $command, int $expectedCode): void
    {
        fwrite($socket, $command . "\r\n");
        $this->readSmtpResponse($socket, $expectedCode);
    }

    /**
     * @param resource $socket
     */
    private function readSmtpResponse($socket, int $expectedCode): void
    {
        $response = '';
        while (($line = fgets($socket, 515)) !== false) {
            $response .= $line;
            // Une reponse SMTP multi-lignes utilise un tiret ("250-") sauf
            // sur la derniere ligne, qui utilise un espace ("250 ").
            if (!isset($line[3]) || $line[3] === ' ') {
                break;
            }
        }

        $code = (int) substr($response, 0, 3);
        if ($code !== $expectedCode) {
            throw new \RuntimeException('Reponse SMTP inattendue (' . $code . ') : ' . trim($response));
        }
    }
}
