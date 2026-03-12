<?php
namespace App\Helpers;

class MailHelper
{
    /**
     * Check if SMTP mail is properly configured in .env
     */
    public static function isConfigured(): bool
    {
        $mailer = config('mail.default');
        if ($mailer === 'log' || $mailer === 'array')
            return false;

        $host = config('mail.mailers.smtp.host');
        $from = config('mail.from.address');

        return !empty($host)
            && $host !== 'mailpit'
            && $host !== 'localhost'
            && !empty($from)
            && $from !== 'hello@example.com';
    }

    /**
     * Get a human-readable status message
     */
    public static function statusMessage(): string
    {
        if (self::isConfigured()) {
            return '✅ Correo configurado correctamente (' . config('mail.mailers.smtp.host') . ')';
        }
        return '⚠️ SMTP no configurado. Configura las variables MAIL_* en .env para enviar correos.';
    }

    /**
     * List required .env variables
     */
    public static function requiredVars(): array
    {
        return [
            'MAIL_MAILER' => config('mail.default'),
            'MAIL_HOST' => config('mail.mailers.smtp.host'),
            'MAIL_PORT' => config('mail.mailers.smtp.port'),
            'MAIL_USERNAME' => config('mail.mailers.smtp.username') ? '••••' : '(vacío)',
            'MAIL_PASSWORD' => config('mail.mailers.smtp.password') ? '••••' : '(vacío)',
            'MAIL_ENCRYPTION' => config('mail.mailers.smtp.encryption'),
            'MAIL_FROM_ADDRESS' => config('mail.from.address'),
            'MAIL_FROM_NAME' => config('mail.from.name'),
        ];
    }
}
