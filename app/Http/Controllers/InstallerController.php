<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\User;
use App\Support\Installer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;

class InstallerController extends Controller
{
    public function welcome(): View
    {
        return view('installer.welcome', ['step' => 1]);
    }

    public function requirements(): View
    {
        $checks = Installer::requirements();
        $allPass = ! in_array(false, $checks, true);
        return view('installer.requirements', compact('checks', 'allPass') + ['step' => 2]);
    }

    public function database(): View
    {
        return view('installer.database', ['step' => 3]);
    }

    public function saveDatabase(Request $request): RedirectResponse
    {
        // Migrations can take a few seconds; some hosting envs default to a
        // tight request timeout. Lift it for the install step only.
        @set_time_limit(120);
        @ini_set('max_execution_time', '120');

        $data = $request->validate([
            'driver' => ['required', 'in:sqlite,mysql,pgsql'],
            'host' => ['nullable', 'string', 'max:120'],
            'port' => ['nullable', 'integer'],
            'database' => ['required', 'string', 'max:120'],
            'username' => ['nullable', 'string', 'max:120'],
            'password' => ['nullable', 'string', 'max:255'],
        ]);

        $env = ['DB_CONNECTION' => $data['driver']];

        if ($data['driver'] === 'sqlite') {
            // database/database.sqlite — create the file if missing
            $sqliteFile = base_path('database/'.$data['database']);
            if (! str_ends_with($sqliteFile, '.sqlite')) $sqliteFile .= '.sqlite';
            if (! is_file($sqliteFile)) @touch($sqliteFile);
            $env['DB_DATABASE'] = $sqliteFile;
        } else {
            $env['DB_HOST'] = $data['host'] ?: '127.0.0.1';
            $env['DB_PORT'] = (string) ($data['port'] ?: ($data['driver'] === 'mysql' ? 3306 : 5432));
            $env['DB_DATABASE'] = $data['database'];
            $env['DB_USERNAME'] = $data['username'] ?? '';
            $env['DB_PASSWORD'] = $data['password'] ?? '';
        }

        // Make sure APP_KEY exists (generate one if .env was blank/missing).
        if (empty(env('APP_KEY')) && empty(config('app.key'))) {
            $env['APP_KEY'] = 'base64:'.base64_encode(random_bytes(32));
        }
        // Default APP_URL to wherever they hit the installer from.
        if (empty(env('APP_URL')) || env('APP_URL') === 'http://localhost') {
            $env['APP_URL'] = url('/');
        }

        Installer::writeEnv($env);

        // Apply config in-memory and test the connection.
        Config::set('database.connections.'.$data['driver'].'.database', $env['DB_DATABASE']);
        if ($data['driver'] !== 'sqlite') {
            Config::set("database.connections.{$data['driver']}.host", $env['DB_HOST']);
            Config::set("database.connections.{$data['driver']}.port", $env['DB_PORT']);
            Config::set("database.connections.{$data['driver']}.username", $env['DB_USERNAME']);
            Config::set("database.connections.{$data['driver']}.password", $env['DB_PASSWORD']);
        }
        Config::set('database.default', $data['driver']);
        DB::purge($data['driver']);

        try {
            DB::connection($data['driver'])->getPdo();
        } catch (\Throwable $e) {
            return back()
                ->withInput()
                ->withErrors(['database' => 'Could not connect: '.$e->getMessage()]);
        }

        // Run all migrations. Use migrate:fresh — this is a fresh install,
        // and if a previous attempt was interrupted, the DB may be in a
        // partial state that plain `migrate` can't recover.
        try {
            Artisan::call('migrate:fresh', ['--force' => true]);
        } catch (\Throwable $e) {
            return back()
                ->withInput()
                ->withErrors(['database' => 'Migration failed: '.$e->getMessage()]);
        }

        return redirect()->route('install.admin');
    }

    public function admin(): View
    {
        return view('installer.admin', ['step' => 4]);
    }

    public function saveAdmin(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'app_name' => ['required', 'string', 'max:120'],
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'seed_demo' => ['nullable', 'boolean'],
        ]);

        // Persist app name to .env too.
        Installer::writeEnv(['APP_NAME' => $data['app_name']]);

        // Create the admin user. UserObserver auto-creates the personal team.
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'email_verified_at' => now(),
        ]);

        // Optional: load demo data so the buyer has something to click on
        // immediately. Failures here are non-fatal — install still succeeds.
        $seeded = null;
        if ($request->boolean('seed_demo') && $user->currentTeam) {
            try {
                $seeded = \App\Support\DemoData::seed($user->currentTeam, $user);
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::warning('Demo seeding failed: '.$e->getMessage());
            }
        }

        // Stash for the finish step
        session([
            'installer.admin_email' => $user->email,
            'installer.seeded' => $seeded,
        ]);

        return redirect()->route('install.finish');
    }

    public function finish(): View
    {
        $email = session('installer.admin_email', 'admin');
        $seeded = session('installer.seeded');

        // Final caches + lock file.
        try {
            Artisan::call('storage:link');
        } catch (\Throwable) { /* ignore — already linked, etc. */ }
        try {
            Artisan::call('config:clear');
            Artisan::call('view:clear');
            Artisan::call('route:clear');
        } catch (\Throwable) {}

        Installer::markInstalled($email);

        return view('installer.finish', ['step' => 5, 'email' => $email, 'seeded' => $seeded]);
    }
}
