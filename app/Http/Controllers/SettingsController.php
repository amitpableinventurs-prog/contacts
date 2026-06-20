<?php

namespace App\Http\Controllers;

use App\Services\AnthropicClient;
use App\Settings\AnthropicSettings;
use App\Settings\GeneralSettings;
use App\Settings\MailSettings;
use App\Settings\TwilioSettings;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SettingsController extends Controller
{
    // ── General ──────────────────────────────────────────────────────────

    public function index(): RedirectResponse
    {
        Gate::authorize('manage-settings');
        return redirect()->route('settings.general');
    }

    public function general(GeneralSettings $settings): View
    {
        Gate::authorize('manage-settings');
        return view('settings.general', compact('settings'));
    }

    public function updateGeneral(Request $request, GeneralSettings $settings): RedirectResponse
    {
        Gate::authorize('manage-settings');

        $data = $request->validate([
            'app_name'           => ['required', 'string', 'max:120'],
            'app_description'    => ['required', 'string', 'max:255'],
            'default_locale'     => ['required', 'string', 'in:en,fr,es,ar,zh-CN'],
            'allow_registration' => ['nullable', 'boolean'],
            'footer_text'        => ['nullable', 'string', 'max:300'],
        ]);

        $data['allow_registration'] = (bool) ($data['allow_registration'] ?? false);
        $settings->fill($data)->save();

        return back()->with('toast', ['type' => 'success', 'message' => 'General settings saved.']);
    }

    // ── Branding ──────────────────────────────────────────────────────────

    public function branding(GeneralSettings $settings): View
    {
        Gate::authorize('manage-settings');
        return view('settings.branding', compact('settings'));
    }

    public function updateBranding(Request $request, GeneralSettings $settings): RedirectResponse
    {
        Gate::authorize('manage-settings');

        $data = $request->validate([
            'primary_color'   => ['required', 'string', 'regex:/^#([A-Fa-f0-9]{6})$/'],
            'logo'            => ['nullable', 'image', 'mimes:png,jpg,jpeg,svg,webp', 'max:2048'],
            'remove_logo'     => ['nullable', 'boolean'],
            'email_signature' => ['nullable', 'string', 'max:2000'],
        ]);

        if (! empty($data['remove_logo']) && $settings->logo_path) {
            Storage::disk('public')->delete($settings->logo_path);
            $settings->logo_path = null;
        }
        if ($request->hasFile('logo')) {
            if ($settings->logo_path) Storage::disk('public')->delete($settings->logo_path);
            $settings->logo_path = $request->file('logo')->store('branding', 'public');
        }

        $settings->fill([
            'primary_color'   => $data['primary_color'],
            'email_signature' => $data['email_signature'] ?? null,
        ])->save();

        return back()->with('toast', ['type' => 'success', 'message' => 'Branding saved.']);
    }

    // ── Mail ──────────────────────────────────────────────────────────────

    public function mail(MailSettings $settings): View
    {
        Gate::authorize('manage-settings');
        return view('settings.mail', compact('settings'));
    }

    public function updateMail(Request $request, MailSettings $settings): RedirectResponse
    {
        Gate::authorize('manage-settings');

        $data = $request->validate([
            'mailer'          => ['required', 'in:log,smtp,resend,mailgun,postmark,ses'],
            'from_email'      => ['required', 'email', 'max:255'],
            'from_name'       => ['required', 'string', 'max:120'],
            'smtp_host'       => ['nullable', 'string', 'max:255'],
            'smtp_port'       => ['nullable', 'integer'],
            'smtp_username'   => ['nullable', 'string', 'max:255'],
            'smtp_password'   => ['nullable', 'string', 'max:255'],
            'smtp_encryption' => ['nullable', 'in:tls,ssl,starttls'],
            'api_key'         => ['nullable', 'string', 'max:255'],
            'api_domain'      => ['nullable', 'string', 'max:255'],
        ]);

        if (empty($data['smtp_password'])) $data['smtp_password'] = $settings->smtp_password;
        if (empty($data['api_key']))       $data['api_key']       = $settings->api_key;

        $settings->fill($data)->save();

        return back()->with('toast', ['type' => 'success', 'message' => 'Mail settings saved.']);
    }

    public function sendTestMail(Request $request): RedirectResponse
    {
        Gate::authorize('manage-settings');

        try {
            Mail::raw('Test message from your CRM.', function ($m) {
                $m->to(Auth::user()->email)->subject('Test — '.config('app.name'));
            });
            return back()->with('toast', ['type' => 'success', 'message' => 'Test email sent to '.Auth::user()->email.'.']);
        } catch (\Throwable $e) {
            return back()->with('toast', ['type' => 'error', 'message' => 'Test failed: '.$e->getMessage()]);
        }
    }

    // ── Twilio ────────────────────────────────────────────────────────────

    public function twilio(TwilioSettings $settings): View
    {
        Gate::authorize('manage-settings');
        return view('settings.twilio', compact('settings'));
    }

    public function updateTwilio(Request $request, TwilioSettings $settings): RedirectResponse
    {
        Gate::authorize('manage-settings');

        $data = $request->validate([
            'fake_mode'       => ['nullable', 'boolean'],
            'sid'             => ['nullable', 'string', 'max:64'],
            'token'           => ['nullable', 'string', 'max:255'],
            'api_key'         => ['nullable', 'string', 'max:64'],
            'api_secret'      => ['nullable', 'string', 'max:255'],
            'application_sid' => ['nullable', 'string', 'max:64'],
            'phone_number'    => ['nullable', 'string', 'max:32'],
        ]);

        $data['fake_mode'] = (bool) ($data['fake_mode'] ?? false);
        if (empty($data['token']))      $data['token']      = $settings->token;
        if (empty($data['api_secret'])) $data['api_secret'] = $settings->api_secret;

        $settings->fill($data)->save();

        return back()->with('toast', ['type' => 'success', 'message' => 'Twilio settings saved.']);
    }

    // ── AI ────────────────────────────────────────────────────────────────

    public function ai(AnthropicSettings $settings): View
    {
        Gate::authorize('manage-settings');
        return view('settings.ai', compact('settings'));
    }

    public function updateAi(Request $request, AnthropicSettings $settings): RedirectResponse
    {
        Gate::authorize('manage-settings');

        $data = $request->validate([
            'fake_mode'  => ['nullable', 'boolean'],
            'api_key'    => ['nullable', 'string', 'max:255'],
            'model'      => ['required', 'string', 'max:80'],
            'max_tokens' => ['required', 'integer', 'min:64', 'max:8192'],
        ]);

        $data['fake_mode'] = (bool) ($data['fake_mode'] ?? false);
        if (empty($data['api_key'])) $data['api_key'] = $settings->api_key;

        $settings->fill($data)->save();

        return back()->with('toast', ['type' => 'success', 'message' => 'AI settings saved.']);
    }

    public function testAi(AnthropicClient $client): RedirectResponse
    {
        Gate::authorize('manage-settings');

        try {
            $result = $client->extractContact("Test User\ntest@example.com");
            $msg = $client->isFake()
                ? 'AI is in fake mode (regex fallback). Add an API key to use real Claude.'
                : 'Claude responded: '.($result['name'] ?? 'no name parsed');
            return back()->with('toast', ['type' => 'success', 'message' => $msg]);
        } catch (\Throwable $e) {
            return back()->with('toast', ['type' => 'error', 'message' => 'AI test failed: '.$e->getMessage()]);
        }
    }
}
