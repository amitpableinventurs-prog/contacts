<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration {
    public function up(): void
    {
        $this->migrator->add('general.allow_registration', true);
        $this->migrator->add('general.email_signature', null);
        $this->migrator->add('general.footer_text', null);
    }
};
