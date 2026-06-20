<?php

namespace App\Http\Controllers;

use App\Support\TwoFactor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class TwoFactorController extends Controller
{
    /** Profile section: render the "Two-factor authentication" card. */
    public function show(Request $request): View
    {
        $user = $request->user();
        $qr = null;
        $codes = [];
        if ($user->two_factor_secret && ! $user->two_factor_confirmed_at) {
            $qr = TwoFactor::qrCodeSvg($user);
            $codes = TwoFactor::recoveryCodes($user);
        }
        return view('profile.two-factor', compact('user', 'qr', 'codes'));
    }

    /** Generate a new secret + recovery codes (overwrites any prior unconfirmed setup). */
    public function enable(Request $request): RedirectResponse
    {
        TwoFactor::generate($request->user());
        return redirect()->route('two-factor.show')
            ->with('toast', ['type' => 'success', 'message' => 'Scan the QR code and enter a code to finish enabling 2FA.']);
    }

    /** Confirm the setup by verifying a code from the authenticator. */
    public function confirm(Request $request): RedirectResponse
    {
        $data = $request->validate(['code' => ['required', 'string', 'size:6']]);
        $user = $request->user();

        if (! TwoFactor::verify($user, $data['code'])) {
            return back()->withErrors(['code' => 'Invalid code. Make sure your authenticator clock is correct.']);
        }

        $user->forceFill(['two_factor_confirmed_at' => now()])->save();

        return redirect()->route('two-factor.show')
            ->with('toast', ['type' => 'success', 'message' => 'Two-factor authentication is now enabled.']);
    }

    /** Disable 2FA — clears the secret + recovery codes. */
    public function disable(Request $request): RedirectResponse
    {
        $request->user()->forceFill([
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'two_factor_confirmed_at' => null,
        ])->save();

        return redirect()->route('two-factor.show')
            ->with('toast', ['type' => 'success', 'message' => 'Two-factor authentication disabled.']);
    }

    /** Challenge: prompt for the OTP after login. */
    public function challenge(Request $request): View|RedirectResponse
    {
        if (! session('two_factor.pending_user_id')) {
            return redirect()->route('login');
        }
        return view('auth.two-factor-challenge');
    }

    /** Verify the challenge OTP (or a recovery code) and complete the login. */
    public function verifyChallenge(Request $request): RedirectResponse
    {
        $id = session('two_factor.pending_user_id');
        $intended = session('two_factor.intended');
        abort_if(! $id, 419);

        $user = \App\Models\User::findOrFail($id);
        $data = $request->validate([
            'code' => ['nullable', 'string'],
            'recovery_code' => ['nullable', 'string'],
        ]);

        $ok = false;
        if (! empty($data['code'])) {
            $ok = TwoFactor::verify($user, trim($data['code']));
        } elseif (! empty($data['recovery_code'])) {
            $ok = TwoFactor::consumeRecoveryCode($user, $data['recovery_code']);
        }

        if (! $ok) {
            return back()->withErrors(['code' => 'Invalid code.']);
        }

        Auth::login($user, (bool) session('two_factor.remember', false));
        $request->session()->forget(['two_factor.pending_user_id', 'two_factor.remember', 'two_factor.intended']);

        return redirect()->intended($intended ?: route('dashboard'));
    }
}
