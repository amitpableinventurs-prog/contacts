<?php

use App\Http\Controllers\ActivityLogsController;
use App\Http\Controllers\ApiTokensController;
use App\Http\Controllers\AuditController;
use App\Http\Controllers\LoginLogsController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\BulkSendsController;
use App\Http\Controllers\CallsController;
use App\Http\Controllers\ContactsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmailsController;
use App\Http\Controllers\GroupsController;
use App\Http\Controllers\ImportsController;
use App\Http\Controllers\InstallerController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RemindersController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\SmsController;
use App\Http\Controllers\TagsController;
use App\Http\Controllers\TrackingController;
use App\Http\Controllers\TwoFactorController;
use App\Http\Controllers\Webhooks\TwilioWebhookController;
use App\Http\Controllers\WorkspaceController;
use App\Http\Controllers\WorkspaceExportController;
use Illuminate\Support\Facades\Route;

// Auth
Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store']);
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

// Installer (public, no auth)
Route::get('/install', [InstallerController::class, 'welcome'])->name('install.welcome');
Route::get('/install/requirements', [InstallerController::class, 'requirements'])->name('install.requirements');
Route::get('/install/database', [InstallerController::class, 'database'])->name('install.database');
Route::post('/install/database', [InstallerController::class, 'saveDatabase'])->name('install.database.save');
Route::get('/install/admin', [InstallerController::class, 'admin'])->name('install.admin');
Route::post('/install/admin', [InstallerController::class, 'saveAdmin'])->name('install.admin.save');
Route::get('/install/finish', [InstallerController::class, 'finish'])->name('install.finish');

// Webhooks (public, no auth — authenticity checked via Twilio signature)
Route::post('/webhooks/twilio/sms', [TwilioWebhookController::class, 'incomingSms'])
    ->middleware('twilio.signature')->name('webhooks.twilio.sms');

// Email tracking pixel (public)
Route::get('/track/{trackingId}', [TrackingController::class, 'emailPixel'])->name('tracking.email');


// Root → dashboard; guests get bounced to login by the auth middleware.
// Kept closure-free so the cached route survives serialization everywhere.
Route::redirect('/', '/dashboard');

Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // -------------------------------------------------------------------------
    // Users management (Super Admin only)
    // -------------------------------------------------------------------------
    Route::get('/users', [UsersController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UsersController::class, 'create'])->name('users.create');
    Route::post('/users', [UsersController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit', [UsersController::class, 'edit'])->name('users.edit');
    Route::patch('/users/{user}', [UsersController::class, 'update'])->name('users.update');
    Route::patch('/users/{user}/password', [UsersController::class, 'changePassword'])->name('users.password');
    Route::delete('/users/{user}', [UsersController::class, 'destroy'])->name('users.destroy');

    // -------------------------------------------------------------------------
    // Contacts
    // -------------------------------------------------------------------------
    Route::get('/contacts', [ContactsController::class, 'index'])->name('contacts.index');
    Route::get('/contacts/create', [ContactsController::class, 'create'])->name('contacts.create');
    // Static sub-paths before {contact} wildcard
    Route::get('/contacts/autocomplete', [ContactsController::class, 'autocomplete'])->name('contacts.autocomplete');
    Route::post('/contacts/suggest-tags', [ContactsController::class, 'suggestTags'])->name('contacts.suggest-tags');
    Route::get('/contacts/pending', [ContactsController::class, 'pending'])->name('contacts.pending');
    Route::post('/contacts/{contact}/approve', [ContactsController::class, 'approve'])->name('contacts.approve');
    Route::post('/contacts/{contact}/reject', [ContactsController::class, 'reject'])->name('contacts.reject');
    Route::post('/contacts/enrich', [ContactsController::class, 'enrich'])->name('contacts.enrich');
    Route::post('/contacts/bulk', [ContactsController::class, 'bulk'])->name('contacts.bulk');
    Route::post('/contacts', [ContactsController::class, 'store'])->name('contacts.store');
    Route::get('/contacts/{contact}', [ContactsController::class, 'show'])->name('contacts.show');
    Route::get('/contacts/{contact}/edit', [ContactsController::class, 'edit'])->name('contacts.edit');
    Route::put('/contacts/{contact}', [ContactsController::class, 'update'])->name('contacts.update');
    Route::delete('/contacts/{contact}', [ContactsController::class, 'destroy'])->name('contacts.destroy');
    Route::post('/contacts/{contact}/suspend', [ContactsController::class, 'suspend'])->name('contacts.suspend');
    Route::post('/contacts/{contact}/ban', [ContactsController::class, 'ban'])->name('contacts.ban');
    Route::post('/contacts/{contact}/rate', [ContactsController::class, 'rate'])->name('contacts.rate');
    Route::get('/contacts/{contact}/merge', [ContactsController::class, 'merge'])->name('contacts.merge');
    Route::post('/contacts/{contact}/merge', [ContactsController::class, 'mergeStore'])->name('contacts.merge.store');
    // Notes
    Route::post('/contacts/{contact}/notes', [ContactsController::class, 'storeNote'])->name('contacts.notes.store');
    Route::delete('/contacts/{contact}/notes/{note}', [ContactsController::class, 'destroyNote'])->name('contacts.notes.destroy');
    // Files
    Route::post('/contacts/{contact}/files', [ContactsController::class, 'storeFile'])->name('contacts.files.store');
    Route::delete('/contacts/{contact}/files/{file}', [ContactsController::class, 'destroyFile'])->name('contacts.files.destroy');
    // Gallery
    Route::post('/contacts/{contact}/gallery', [ContactsController::class, 'storeGallery'])->name('contacts.gallery.store');
    Route::delete('/contacts/{contact}/gallery/{image}', [ContactsController::class, 'destroyGallery'])->name('contacts.gallery.destroy');

    // -------------------------------------------------------------------------
    // Imports
    // -------------------------------------------------------------------------
    Route::get('/imports', [ImportsController::class, 'form'])->name('imports.form');
    Route::get('/imports/template', [ImportsController::class, 'template'])->name('imports.template');
    Route::post('/imports/preview', [ImportsController::class, 'preview'])->name('imports.preview');
    Route::post('/imports', [ImportsController::class, 'store'])->name('imports.store');
    Route::get('/imports/{import}/progress', [ImportsController::class, 'progress'])
        ->where('import', '[A-Za-z0-9]{24}')->name('imports.progress');
    Route::post('/imports/{import}/process', [ImportsController::class, 'process'])
        ->where('import', '[A-Za-z0-9]{24}')->name('imports.process');

    // -------------------------------------------------------------------------
    // Emails
    // -------------------------------------------------------------------------
    Route::get('/emails', [EmailsController::class, 'index'])->name('emails.index');
    Route::get('/emails/create', [EmailsController::class, 'create'])->name('emails.create');
    Route::post('/emails', [EmailsController::class, 'store'])->name('emails.store');

    // -------------------------------------------------------------------------
    // SMS / Messaging
    // -------------------------------------------------------------------------
    Route::get('/sms', [SmsController::class, 'index'])->name('sms.index');
    Route::get('/sms/{contact}', [SmsController::class, 'show'])->name('sms.show');
    Route::post('/sms/{contact}', [SmsController::class, 'store'])->name('sms.store');
    Route::post('/messaging/spell-check', [SmsController::class, 'spellCheck'])->name('messaging.spell-check');
    Route::post('/messaging/translate', [SmsController::class, 'translate'])->name('messaging.translate');

    // -------------------------------------------------------------------------
    // Calls
    // -------------------------------------------------------------------------
    Route::get('/calls', [CallsController::class, 'index'])->name('calls.index');
    Route::post('/calls/log', [CallsController::class, 'log'])->name('calls.log');
    Route::get('/calls/token', [CallsController::class, 'token'])->name('calls.token');

    // -------------------------------------------------------------------------
    // Reminders
    // -------------------------------------------------------------------------
    Route::get('/reminders', [RemindersController::class, 'index'])->name('reminders.index');
    Route::get('/reminders/calendar', [RemindersController::class, 'calendar'])->name('reminders.calendar');
    Route::post('/reminders', [RemindersController::class, 'store'])->name('reminders.store');
    Route::post('/reminders/{reminder}/complete', [RemindersController::class, 'complete'])->name('reminders.complete');
    Route::delete('/reminders/{reminder}', [RemindersController::class, 'destroy'])->name('reminders.destroy');

    // -------------------------------------------------------------------------
    // Tags
    // -------------------------------------------------------------------------
    Route::get('/tags', [TagsController::class, 'index'])->name('tags.index');
    Route::post('/tags', [TagsController::class, 'store'])->name('tags.store');
    Route::patch('/tags/{tag}', [TagsController::class, 'update'])->name('tags.update');
    Route::delete('/tags/{tag}', [TagsController::class, 'destroy'])->name('tags.destroy');

    // -------------------------------------------------------------------------
    // Groups
    // -------------------------------------------------------------------------
    Route::get('/groups', [GroupsController::class, 'index'])->name('groups.index');
    Route::post('/groups', [GroupsController::class, 'store'])->name('groups.store');
    Route::patch('/groups/{group}', [GroupsController::class, 'update'])->name('groups.update');
    Route::delete('/groups/{group}', [GroupsController::class, 'destroy'])->name('groups.destroy');

    // -------------------------------------------------------------------------
    // Bulk sends
    // -------------------------------------------------------------------------
    Route::get('/bulk-sends', [BulkSendsController::class, 'index'])->name('bulk-sends.index');
    Route::get('/bulk-sends/compose', [BulkSendsController::class, 'compose'])->name('bulk-sends.compose');
    Route::post('/bulk-sends', [BulkSendsController::class, 'store'])->name('bulk-sends.store');
    Route::get('/bulk-sends/{bulkSend}', [BulkSendsController::class, 'show'])->name('bulk-sends.show');
    Route::delete('/bulk-sends/templates/{template}', [BulkSendsController::class, 'destroyTemplate'])->name('bulk-sends.template.destroy');

    // -------------------------------------------------------------------------
    // Audit / Activity logs
    // -------------------------------------------------------------------------
    Route::get('/audit', [AuditController::class, 'index'])->name('audit.index');
    Route::get('/activity-logs', [ActivityLogsController::class, 'index'])->name('activity-logs.index');

    // -------------------------------------------------------------------------
    // IP Login logs
    // -------------------------------------------------------------------------
    Route::get('/login-logs', [LoginLogsController::class, 'index'])->name('login-logs.index');  // gate: view-login-logs

    // -------------------------------------------------------------------------
    // Profile
    // -------------------------------------------------------------------------
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // -------------------------------------------------------------------------
    // Two-factor authentication
    // -------------------------------------------------------------------------
    Route::get('/two-factor', [TwoFactorController::class, 'show'])->name('two-factor.show');
    Route::post('/two-factor', [TwoFactorController::class, 'enable'])->name('two-factor.enable');
    Route::delete('/two-factor', [TwoFactorController::class, 'disable'])->name('two-factor.disable');
    Route::post('/two-factor/confirm', [TwoFactorController::class, 'confirm'])->name('two-factor.confirm');

    // -------------------------------------------------------------------------
    // API tokens
    // -------------------------------------------------------------------------
    Route::get('/api-tokens', [ApiTokensController::class, 'index'])->name('api-tokens.index');
    Route::post('/api-tokens', [ApiTokensController::class, 'store'])->name('api-tokens.store');
    Route::delete('/api-tokens/{token}', [ApiTokensController::class, 'destroy'])->name('api-tokens.destroy');

    // -------------------------------------------------------------------------
    // Settings
    // -------------------------------------------------------------------------
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::get('/settings/general', [SettingsController::class, 'general'])->name('settings.general');
    Route::patch('/settings/general', [SettingsController::class, 'updateGeneral'])->name('settings.general.update');
    Route::get('/settings/branding', [SettingsController::class, 'branding'])->name('settings.branding');
    Route::patch('/settings/branding', [SettingsController::class, 'updateBranding'])->name('settings.branding.update');
    Route::get('/settings/mail', [SettingsController::class, 'mail'])->name('settings.mail');
    Route::patch('/settings/mail', [SettingsController::class, 'updateMail'])->name('settings.mail.update');
    Route::post('/settings/mail/test', [SettingsController::class, 'sendTestMail'])->name('settings.mail.test');
    Route::get('/settings/ai', [SettingsController::class, 'ai'])->name('settings.ai');
    Route::patch('/settings/ai', [SettingsController::class, 'updateAi'])->name('settings.ai.update');
    Route::post('/settings/ai/test', [SettingsController::class, 'testAi'])->name('settings.ai.test');
    Route::get('/settings/twilio', [SettingsController::class, 'twilio'])->name('settings.twilio');
    Route::patch('/settings/twilio', [SettingsController::class, 'updateTwilio'])->name('settings.twilio.update');

    // -------------------------------------------------------------------------
    // Workspace
    // -------------------------------------------------------------------------
    Route::post('/workspace/switch/{team}', [WorkspaceController::class, 'switchTeam'])->name('workspace.switch');
    Route::get('/workspace/export', [WorkspaceExportController::class, 'download'])->name('workspace.export');
});
