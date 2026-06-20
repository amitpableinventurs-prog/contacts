<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration {
    public function up(): void
    {
        $this->migrator->add('anthropic.fake_mode', env('ANTHROPIC_API_KEY') === null);
        $this->migrator->addEncrypted('anthropic.api_key', env('ANTHROPIC_API_KEY'));
        $this->migrator->add('anthropic.model', env('ANTHROPIC_MODEL', 'claude-opus-4-7'));
        $this->migrator->add('anthropic.max_tokens', 1024);
    }
};
