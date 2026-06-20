<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration {
    public function up(): void
    {
        $this->migrator->add('mail.mailer', env('MAIL_MAILER', 'log'));
        $this->migrator->add('mail.from_email', env('MAIL_FROM_ADDRESS', 'noreply@laracontact.test'));
        $this->migrator->add('mail.from_name', env('MAIL_FROM_NAME', 'LaraContact'));
        $this->migrator->add('mail.smtp_host', env('MAIL_HOST'));
        $this->migrator->add('mail.smtp_port', env('MAIL_PORT', 587));
        $this->migrator->add('mail.smtp_username', env('MAIL_USERNAME'));
        $this->migrator->addEncrypted('mail.smtp_password', env('MAIL_PASSWORD'));
        $this->migrator->add('mail.smtp_encryption', env('MAIL_ENCRYPTION', 'tls'));
        $this->migrator->addEncrypted('mail.api_key', null);
        $this->migrator->add('mail.api_domain', null);
    }
};
