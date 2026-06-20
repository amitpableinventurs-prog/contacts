<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class GeneralSettings extends Settings
{
    public string $app_name;
    public string $app_description;
    public string $default_locale;
    public string $primary_color;
    public ?string $logo_path;
    public bool $allow_registration;
    public ?string $email_signature;
    public ?string $footer_text;

    public static function group(): string
    {
        return 'general';
    }
}
