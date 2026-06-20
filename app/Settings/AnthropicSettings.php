<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class AnthropicSettings extends Settings
{
    public bool $fake_mode;
    public ?string $api_key;
    public string $model;
    public int $max_tokens;

    public static function group(): string
    {
        return 'anthropic';
    }

    public static function encrypted(): array
    {
        return ['api_key'];
    }
}
