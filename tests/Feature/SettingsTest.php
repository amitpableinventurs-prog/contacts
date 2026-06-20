<?php

use App\Settings\GeneralSettings;
use App\Settings\TwilioSettings;

beforeEach(function () {
    $this->actingAs(makeUser());
});

it('renders the general settings page', function () {
    $this->get('/settings/general')->assertOk()->assertSee('Application name');
});

it('updates general settings', function () {
    $this->patch('/settings/general', [
        'app_name' => 'Test CRM',
        'app_description' => 'Just testing',
        'default_locale' => 'fr',
    ])->assertRedirect();

    $settings = app(GeneralSettings::class);
    expect($settings)
        ->app_name->toBe('Test CRM')
        ->default_locale->toBe('fr');
});

it('updates branding (primary color) settings', function () {
    $this->patch('/settings/branding', [
        'primary_color' => '#ff00aa',
    ])->assertRedirect();

    expect(app(GeneralSettings::class)->primary_color)->toBe('#ff00aa');
});

it('rejects an invalid primary color', function () {
    $this->patch('/settings/branding', [
        'primary_color' => 'not-a-hex',
    ])->assertSessionHasErrors('primary_color');
});

it('keeps an encrypted twilio token when left blank on update', function () {
    $s = app(TwilioSettings::class);
    $s->token = 'SECRETTOKEN';
    $s->save();

    $this->patch('/settings/twilio', [
        'sid' => 'AC123',
        'fake_mode' => '0',
        // token intentionally blank → should not be wiped
    ])->assertRedirect();

    expect(app(TwilioSettings::class)->token)->toBe('SECRETTOKEN');
});
