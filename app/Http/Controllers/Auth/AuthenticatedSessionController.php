<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\IpLoginLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        // Block inactive accounts immediately after credential check.
        $user = Auth::user();
        if ($user && ! ($user->is_active ?? true)) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return back()->withErrors(['email' => 'Your account has been deactivated. Contact a Super Admin.']);
        }

        // 2FA intercept
        if ($user && $user->two_factor_confirmed_at) {
            Auth::guard('web')->logout();
            $request->session()->put([
                'two_factor.pending_user_id' => $user->id,
                'two_factor.remember'        => $request->boolean('remember'),
                'two_factor.intended'        => $request->session()->pull('url.intended'),
            ]);
            return redirect()->route('two-factor.challenge');
        }

        $request->session()->regenerate();

        // Always go to dashboard — avoids redirecting a lower-privilege user
        // to a page they visited before login but can't access (e.g. /settings).
        return redirect()->route('dashboard');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $sessionId = $request->session()->getId();
        $userId    = Auth::id();

        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Stamp the logout time on the matching login record.
        if ($userId && $sessionId) {
            IpLoginLog::where('user_id', $userId)
                ->where('session_id', $sessionId)
                ->whereNull('logout_at')
                ->latest()
                ->first()
                ?->update(['logout_at' => now()]);
        }

        return redirect('/');
    }
}
