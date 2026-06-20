# Changelog

All notable changes to LaraContact will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [2.0.0] — 2026-05-19

### Added
- **Bulk sends** — Email, SMS, and WhatsApp campaigns to any selection of contacts, with mail-merge tokens (`{{first_name}}`, `{{name}}`, `{{company}}`, `{{email}}`, `{{phone}}`). Rate-limited via Laravel's job queue (configurable per channel), with a live auto-refreshing progress page.
- **Bulk-send templates** — save any composition by name and reload it with one click on the composer. Workspace-scoped.
- **Two-factor authentication** — TOTP via Google Authenticator / 1Password / Authy / Bitwarden with 8 single-use recovery codes. Setup flow with live QR code, login-challenge interception, ±60s clock-drift tolerance, encrypted-at-rest secrets.
- **Workspace data export** — one-click ZIP containing CSVs of every contact, message, email, reminder, group, and tag, plus a `README.txt` manifest. Lives on the Workspace Members page.
- **Premium web installer** — redesigned 5-step wizard with gradient hero, animated stepper, card-style driver picker (SQLite / MySQL / Postgres), grouped requirements checks, celebration finish.
- **Demo data on install** — opt-in checkbox seeds 12 sample contacts, 4 groups, 7 tags, and 5 reminders so buyers see a populated app immediately.
- **AI tag suggestions** — Claude picks the best-fitting tags from your workspace's library based on the contact's notes, company, and title.
- **Reminders calendar grid** — monthly view with click-to-schedule, color-coded chips per day.
- **Custom fields per contact** — key/value pairs editable inline on the contact form.
- **CSV import template download** — pre-populated headers + one example row.
- **Onboarding checklist** — 6-step interactive card on the dashboard that links each step to its setup page.
- **Mail settings UI** — pick driver (SMTP / Resend / Mailgun / Postmark / SES) and credentials without touching `.env`.
- **Branding settings UI** — logo upload + live-applied primary color (CSS custom properties, dark-mode aware).
- **AI settings UI** — encrypted Anthropic API key + test-connection button.
- **Public registration toggle** — turn invite-only on or off from General settings.

### Changed
- Twilio send failures now surface a human-readable inline error (e.g. "The From number isn't owned by this Twilio account") instead of crashing as a 500.
- `MARKETING.md` "What's inside" reorganized with a new **Security & data ownership** section.
- Test count now 100 (282 assertions) — up from 72.

### Fixed
- Echo/Reverb is no longer initialized unless `VITE_REVERB_APP_KEY` is set. Previously, missing Reverb config threw synchronously inside `app.js` and silently killed every Alpine dropdown and the command palette.
- Primary color now applies in dark mode (CSS specificity bug — overrode both `:root` and `html.dark`).
- Logout button now actually logs out (a duplicate `type="button"` attribute on `<x-ui.dropdown-menu-item>` overrode `type="submit"`).
- Bulk-send composer no longer 500s on Blade parse error (`{{ '{{token}}' }}` rewritten as `@{{token}}`).
- Installer database step now uses vanilla JS for driver toggling — Alpine-only version sometimes didn't initialize on first paint.
- Installer can now recover from a partial migration (`migrate:fresh` rather than `migrate`).
- App service provider's mail-settings override wraps lazy property access in try/catch, so the first request before migration doesn't crash.

### Removed
- Stub fields and pre-build CSS fallbacks in the installer (no longer needed — Vite build ships in the distribution ZIP).

---

## [1.0.0] — Initial release

- Built on Laravel 13 + Tailwind 3 + Alpine.js 3
- Multi-user team workspaces with invitations
- Two-way SMS and WhatsApp via Twilio
- Click-to-call in-browser dialer (Twilio Voice)
- Queued email composer with open-tracking pixel
- AI contact enrichment, spell-check, and translation (Anthropic Claude)
- Reminders with list and calendar views
- Audit log with field-level diffs (Spatie ActivityLog)
- Sanctum-protected JSON API
- Command palette (⌘K)
- Custom fields, tags, groups
- CSV import with auto-column mapping
- Bulk operations on contacts (group, tag, delete)
- Duplicate detection and merge
- Pest feature test coverage
