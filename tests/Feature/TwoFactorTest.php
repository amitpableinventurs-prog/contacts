<?php

use App\Support\TwoFactor;

beforeEach(function () {
    $this->user = makeUser(['password' => bcrypt('password12345')]);
});

it('lets a user start the 2FA setup', function () {
    $this->actingAs($this->user)
        ->post('/profile/two-factor/enable')
        ->assertRedirect(route('two-factor.show'));

    $this->user->refresh();
    expect($this->user->two_factor_secret)->not->toBeNull();
    expect($this->user->two_factor_confirmed_at)->toBeNull();
    expect(TwoFactor::recoveryCodes($this->user))->toHaveCount(8);
});

it('confirms 2FA when a valid OTP is supplied', function () {
    TwoFactor::generate($this->user);
    $this->user->refresh();

    $code = TwoFactor::engine()->getCurrentOtp(TwoFactor::secret($this->user));

    $this->actingAs($this->user)
        ->post('/profile/two-factor/confirm', ['code' => $code])
        ->assertRedirect(route('two-factor.show'));

    $this->user->refresh();
    expect($this->user->two_factor_confirmed_at)->not->toBeNull();
});

it('rejects an invalid OTP', function () {
    TwoFactor::generate($this->user);
    $this->user->refresh();

    $this->actingAs($this->user)
        ->post('/profile/two-factor/confirm', ['code' => '000000'])
        ->assertSessionHasErrors('code');

    expect($this->user->fresh()->two_factor_confirmed_at)->toBeNull();
});

it('disables 2FA and clears the secret', function () {
    TwoFactor::generate($this->user);
    $this->user->forceFill(['two_factor_confirmed_at' => now()])->save();

    $this->actingAs($this->user)
        ->delete('/profile/two-factor')
        ->assertRedirect(route('two-factor.show'));

    $this->user->refresh();
    expect($this->user->two_factor_secret)->toBeNull();
    expect($this->user->two_factor_confirmed_at)->toBeNull();
});

it('intercepts login and redirects to the challenge when 2FA is on', function () {
    TwoFactor::generate($this->user);
    $this->user->forceFill(['two_factor_confirmed_at' => now()])->save();

    $res = $this->post('/login', [
        'email' => $this->user->email,
        'password' => 'password12345',
    ]);

    $res->assertRedirect(route('two-factor.challenge'));
    expect(auth()->check())->toBeFalse();
    expect(session('two_factor.pending_user_id'))->toBe($this->user->id);
});

it('completes login when the challenge OTP is valid', function () {
    TwoFactor::generate($this->user);
    $this->user->forceFill(['two_factor_confirmed_at' => now()])->save();

    // First half — get into the pending state.
    $this->post('/login', ['email' => $this->user->email, 'password' => 'password12345']);

    $code = TwoFactor::engine()->getCurrentOtp(TwoFactor::secret($this->user));
    $this->post('/two-factor-challenge', ['code' => $code])
        ->assertRedirect(route('dashboard'));

    expect(auth()->id())->toBe($this->user->id);
});

it('completes login when a recovery code is used', function () {
    TwoFactor::generate($this->user);
    $this->user->forceFill(['two_factor_confirmed_at' => now()])->save();
    $codes = TwoFactor::recoveryCodes($this->user);

    $this->post('/login', ['email' => $this->user->email, 'password' => 'password12345']);

    $this->post('/two-factor-challenge', ['recovery_code' => $codes[0]])
        ->assertRedirect(route('dashboard'));

    // The recovery code is single-use.
    expect(TwoFactor::recoveryCodes($this->user->fresh()))->toHaveCount(7);
});

it('rejects a wrong challenge OTP', function () {
    TwoFactor::generate($this->user);
    $this->user->forceFill(['two_factor_confirmed_at' => now()])->save();

    $this->post('/login', ['email' => $this->user->email, 'password' => 'password12345']);

    $this->post('/two-factor-challenge', ['code' => '000000'])
        ->assertSessionHasErrors('code');

    expect(auth()->check())->toBeFalse();
});

it('lets users without 2FA log in normally', function () {
    $this->post('/login', [
        'email' => $this->user->email,
        'password' => 'password12345',
    ])->assertRedirect(route('dashboard'));

    expect(auth()->id())->toBe($this->user->id);
});
