<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class MailSettings extends Settings
{
    public string $mailer;
    public string $from_email;
    public string $from_name;

    // SMTP
    public ?string $smtp_host;
    public ?int $smtp_port;
    public ?string $smtp_username;
    public ?string $smtp_password;
    public ?string $smtp_encryption;

    // API drivers
    public ?string $api_key;
    public ?string $api_domain;

    public static function group(): string
    {
        return 'mail';
    }

    public static function encrypted(): array
    {
        return ['smtp_password', 'api_key'];
    }
}
