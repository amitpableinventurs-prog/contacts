<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class TwilioSettings extends Settings
{
    public bool $fake_mode;
    public ?string $sid;
    public ?string $token;
    public ?string $api_key;
    public ?string $api_secret;
    public ?string $application_sid;
    public ?string $phone_number;

    public static function group(): string
    {
        return 'twilio';
    }

    public static function encrypted(): array
    {
        return ['token', 'api_secret'];
    }
}
