# LaraContact

> A multi-user CRM with built-in two-way SMS, WhatsApp, click-to-call, queued email, and AI-assisted contact management. Built on Laravel 13.

## Quick start

```bash
cd laracontact
composer install --no-dev --optimize-autoloader
chmod -R u+rwX storage bootstrap/cache
```

Then open `https://yourdomain.com/install` in a browser. The 5-step web installer handles the database, app key, admin account, and (optional) demo data. No `.env` editing required.

Need a local-only spin? `php artisan serve` then `http://127.0.0.1:8000/install`.

## What's included

**Contacts & organization**
- Rich contact records with custom fields, lifecycle stages, social handles
- Tags + colored groups, CSV import with auto-column detection
- Duplicate detection + one-click merge
- Bulk operations on selection (group, tag, send, delete)

**Communication, unified**
- Two-way SMS & WhatsApp via Twilio (signature-validated webhook)
- Click-to-call browser dialer (Twilio Voice)
- Queued email with open-tracking pixel
- Bulk send to selected contacts (Email / SMS / WhatsApp) with mail-merge tokens
- Reusable bulk-send templates

**AI assistance** (Anthropic Claude — works in fake mode without an API key)
- Paste an email signature → extracts name, email, phone, company, title, website, LinkedIn
- Fix grammar / Translate (9 languages) on the message composer
- Suggest tags from a contact's context

**Security & data ownership**
- Two-factor authentication (TOTP + 8 recovery codes)
- Workspace data export (ZIP of every CSV)
- Audit log with field-level diffs
- Sanctum-protected JSON API with per-token rate limits

**Multi-tenant**
- Personal workspaces auto-created on signup
- Invite teammates by email
- Switch between workspaces from the sidebar dropdown

**Operations**
- Premium 5-step web installer
- Mail, Branding, AI, Twilio settings — all UI, no `.env`
- Live-applied primary color (CSS custom properties)
- Dark mode

## Tech stack

- **Backend:** PHP 8.3+, Laravel 13, SQLite/MySQL/Postgres
- **Frontend:** Tailwind 3, Alpine.js 3, Vite 8
- **Realtime:** Laravel Reverb (optional)
- **Queues:** Laravel's database/Redis driver (sync also works)
- **Tests:** Pest 4 — 100 feature tests, 282 assertions

## Documentation

- `Documentation/index.html` — installation & deployment (Forge, Cloud, VPS, cPanel)
- `Documentation/user-guide.html` — comprehensive end-user guide
- `CHANGELOG.md` — release notes
- `Marketing/` — sales copy, screenshot guide, brand mark

## Requirements

- PHP 8.3 or newer with the extensions: `mbstring`, `pdo`, `tokenizer`, `xml`, `ctype`, `json`, `bcmath`, `openssl`, `fileinfo`
- Composer 2.x
- A database (SQLite works without any setup; MySQL 8+ or Postgres 15+ also supported)
- Writable `storage/` and `bootstrap/cache/`
- (Optional) Twilio account for real SMS/WhatsApp/Voice
- (Optional) Anthropic API key for real AI features
- (Optional) Process supervisor (systemd, Supervisor) to run `queue:work` and `schedule:work`

## License

Per your Envato CodeCanyon license (Regular or Extended). Include your Purchase Code when contacting support.

## Support

Bug reports, install help, and feature questions: contact the author via your Envato item page. Include your Purchase Code, Laravel version, PHP version, and a description of what you tried.

---

Built with care. If you ship something cool, we'd love to hear about it.
