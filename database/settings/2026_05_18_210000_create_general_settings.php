<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration {
    public function up(): void
    {
        $this->migrator->add('general.app_name', 'LaraContact');
        $this->migrator->add('general.app_description', 'Multi-user online contact management with calls, SMS, and email.');
        $this->migrator->add('general.default_locale', 'en');
        $this->migrator->add('general.primary_color', '#7c3aed');
    }
};
