<?php

namespace App\Providers;

use App\Listeners\LogSuccessfulLogin;
use App\Models\User;
use App\Settings\MailSettings;
use App\Support\Roles;
use Illuminate\Auth\Events\Login;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        Schema::defaultStringLength(191);

        if (env('FORCE_HTTPS', false)) {
            URL::forceScheme('https');
        }

        RateLimiter::for('api', fn (Request $request) =>
            Limit::perMinute(60)->by($request->user()?->id ?: $request->ip())
        );
        RateLimiter::for('bulk-sms',      fn () => Limit::perMinute(60));
        RateLimiter::for('bulk-whatsapp', fn () => Limit::perMinute(60));
        RateLimiter::for('bulk-email',    fn () => Limit::perMinute(60));

        Event::listen(Login::class, LogSuccessfulLogin::class);

        $this->defineGates();
        $this->overrideMailConfig();
    }

    protected function defineGates(): void
    {
        // Super Admin bypasses every gate check.
        Gate::before(fn (User $user) => $user->isSuperAdmin() ? true : null);

        // ── Users management ─────────────────────────────────────────────
        // Super Admin + Admin + Manager can manage users.
        // (Manager is restricted to Clerk accounts only in UsersController.)
        Gate::define('manage-users', fn (User $user) =>
            $user->hasRole(Roles::SUPER_ADMIN, Roles::ADMIN, Roles::MANAGER)
        );

        // ── Site settings — Super Admin + Admin ───────────────────────────
        Gate::define('manage-settings', fn (User $user) =>
            $user->hasRole(Roles::SUPER_ADMIN, Roles::ADMIN)
        );

        // ── Contacts ──────────────────────────────────────────────────────
        // All roles can view/create/delete contacts and add notes/ratings.
        Gate::define('contacts.viewAny', fn (User $user) =>
            $user->current_team_id !== null
        );
        Gate::define('contacts.create', fn (User $user) =>
            $user->current_team_id !== null
        );
        Gate::define('contacts.view', fn (User $user) =>
            $user->current_team_id !== null
        );
        Gate::define('contacts.delete', fn (User $user) =>
            $user->current_team_id !== null
        );
        Gate::define('contacts.notes', fn (User $user) =>
            $user->current_team_id !== null
        );
        Gate::define('contacts.rate', fn (User $user) =>
            $user->current_team_id !== null
        );

        // Only Super Admin, Admin, Manager can edit contacts.
        Gate::define('contacts.update', fn (User $user) =>
            $user->hasRole(Roles::SUPER_ADMIN, Roles::ADMIN, Roles::MANAGER)
        );

        // ── Modules for Manager and above ─────────────────────────────────
        $managerPlus = fn (User $user) =>
            $user->hasRole(Roles::SUPER_ADMIN, Roles::ADMIN, Roles::MANAGER);

        Gate::define('manage-groups',    $managerPlus);
        Gate::define('manage-tags',      $managerPlus);
        Gate::define('view-tags', fn (User $user) => $user->current_team_id !== null);
        Gate::define('messaging',        $managerPlus);
        Gate::define('manage-calls',     $managerPlus);
        Gate::define('manage-emails',    $managerPlus);
        Gate::define('manage-reminders', $managerPlus);
        Gate::define('manage-bulk',      $managerPlus);

        // Manager can approve/reject contacts submitted by Clerks.
        Gate::define('approve-contacts', $managerPlus);

        // ── Import / Export — Super Admin + Admin + Manager + Clerk ────────
        // Managers can import with full overwrite (they already have
        // contacts.update + manage-tags). Clerks may only create new
        // contacts via import (no overwrite, no new-tag creation) — see
        // ImportsController::store(). Both can export the contacts they
        // can already view.
        Gate::define('manage-imports', fn (User $user) =>
            $user->hasRole(Roles::SUPER_ADMIN, Roles::ADMIN, Roles::MANAGER, Roles::CLERK)
        );
        Gate::define('manage-export', fn (User $user) =>
            $user->hasRole(Roles::SUPER_ADMIN, Roles::ADMIN, Roles::MANAGER, Roles::CLERK)
        );

        // ── Audit / Activity logs ──────────────────────────────────────────
        // Super Admin sees all; Admin + Manager see Clerk + Manager logs only.
        Gate::define('view-audit', fn (User $user) =>
            $user->hasRole(Roles::SUPER_ADMIN, Roles::ADMIN, Roles::MANAGER)
        );

        // ── Login logs ────────────────────────────────────────────────────
        // Admin+ sees all. Manager sees only Clerk + Manager logins.
        Gate::define('view-login-logs', fn (User $user) =>
            $user->hasRole(Roles::SUPER_ADMIN, Roles::ADMIN, Roles::MANAGER)
        );
    }

    protected function overrideMailConfig(): void
    {
        try {
            $s      = app(MailSettings::class);
            $mailer = $s->mailer;

            Config::set('mail.default', $mailer);
            Config::set('mail.from.address', $s->from_email);
            Config::set('mail.from.name', $s->from_name);

            if ($mailer === 'smtp') {
                Config::set('mail.mailers.smtp', [
                    'transport'    => 'smtp',
                    'host'         => $s->smtp_host,
                    'port'         => $s->smtp_port,
                    'encryption'   => $s->smtp_encryption,
                    'username'     => $s->smtp_username,
                    'password'     => $s->smtp_password,
                    'timeout'      => null,
                    'local_domain' => env('MAIL_EHLO_DOMAIN'),
                    'stream'       => [
                        'ssl' => [
                            'allow_self_signed' => true,
                            'verify_peer'       => false,
                            'verify_peer_name'  => false,
                        ],
                    ],
                ]);
            }
            if ($mailer === 'resend')   { Config::set('services.resend.key', $s->api_key); }
            if ($mailer === 'mailgun')  { Config::set('services.mailgun.secret', $s->api_key); Config::set('services.mailgun.domain', $s->api_domain); }
            if ($mailer === 'postmark') { Config::set('services.postmark.token', $s->api_key); }
            if ($mailer === 'ses') {
                // api_key stores "ACCESS_KEY_ID|SECRET_ACCESS_KEY|REGION" for SES
                [$key, $secret, $region] = array_pad(explode('|', $s->api_key ?? ''), 3, null);
                Config::set('services.ses.key',    $key);
                Config::set('services.ses.secret', $secret);
                Config::set('services.ses.region', $region ?? 'us-east-1');
            }
        } catch (\Throwable) {
            // Settings table not migrated yet — fall back to env config.
        }
    }
}
