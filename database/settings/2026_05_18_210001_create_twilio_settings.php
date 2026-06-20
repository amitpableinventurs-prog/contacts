<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration {
    public function up(): void
    {
        $this->migrator->add('twilio.fake_mode', true);
        $this->migrator->add('twilio.sid', null);
        $this->migrator->addEncrypted('twilio.token', null);
        $this->migrator->add('twilio.api_key', null);
        $this->migrator->addEncrypted('twilio.api_secret', null);
        $this->migrator->add('twilio.application_sid', null);
        $this->migrator->add('twilio.phone_number', null);
    }
};
