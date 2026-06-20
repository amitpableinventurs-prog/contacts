# LaraContact — Installation & Deployment Guide

LaraContact is a multi-user, AI-assisted CRM built on Laravel 13. It ships with two-way SMS/WhatsApp, click-to-call, queued email, real-time inbound notifications, audit logging, and a Sanctum-powered JSON API.

This guide walks you from a fresh download to a live deployment.

---

## 1. System requirements

| Requirement | Minimum | Recommended |
|---|---|---|
| PHP | 8.3 | 8.3+ |
| Composer | 2.6+ | 2.8+ |
| Node.js | 22+ | 24+ |
| Database | SQLite (dev) | MySQL 8 or PostgreSQL 15 |
| Web server | PHP built-in (dev) | Nginx + PHP-FPM |
| Memory | 256 MB | 1 GB |
| Disk | 500 MB | 2 GB |
| Required PHP extensions | `mbstring`, `openssl`, `pdo`, `pdo_sqlite` or `pdo_mysql`, `tokenizer`, `xml`, `json`, `bcmath`, `fileinfo`, `curl`, `gd` (logo resizing) | |
| Optional | Redis (cache + queue), Mailpit (local email capture) | |

---

## 2. Quick start (5 minutes, local dev)

```bash
# 1. Extract the archive
unzip laracontact-v2.zip && cd laracontact-v2

# 2. Install dependencies
composer install
npm install

# 3. Bootstrap environment
cp .env.example .env
php artisan key:generate

# 4. Initialize the database (defaults to SQLite)
touch database/database.sqlite
php artisan migrate --seed

# 5. Link the public storage disk (needed for logo uploads)
php artisan storage:link

# 6. Build frontend assets
npm run build

# 7. Start the dev environment
composer run dev
```

`composer run dev` starts the web server, queue worker, Vite, and Laravel Reverb concurrently. Open **`http://localhost:8000`** and sign in with the seeded demo user:

- **Email:** `demo@laracontact.test`
- **Password:** `password`

---

## 3. Deployment options

### 3.1 Laravel Cloud (easiest)

1. Push the project to a GitHub repository.
2. Sign in to [Laravel Cloud](https://cloud.laravel.com) and create a new project pointing at the repo.
3. Add a Postgres add-on or attach an external database; copy the connection vars into the environment editor.
4. Set the environment variables listed in [§4](#4-environment-variables) below.
5. Cloud auto-runs `composer install`, `php artisan migrate --force`, and `npm ci && npm run build` on every push.
6. Add a Cron rule for the scheduler:
   ```
   * * * * * php artisan schedule:run
   ```

### 3.2 Laravel Forge (recommended for production)

1. Provision a server (DigitalOcean, Hetzner, Vultr, AWS, etc.) through Forge.
2. Create a new site pointing at your domain. Web directory: `public`. PHP: 8.3.
3. Set up a Git deployment from your repo with this script:
   ```bash
   cd $FORGE_SITE_PATH
   git pull origin main
   $FORGE_COMPOSER install --no-interaction --prefer-dist --optimize-autoloader
   $FORGE_PHP artisan migrate --force
   npm ci
   npm run build
   $FORGE_PHP artisan storage:link
   $FORGE_PHP artisan optimize
   ```
4. Add the daemon for queue workers under **Daemons**:
   ```
   php /home/forge/your-site/artisan queue:work --sleep=1 --tries=3 --max-time=3600
   ```
5. Add the scheduler under **Scheduler**:
   ```
   * * * * * php /home/forge/your-site/artisan schedule:run >> /dev/null 2>&1
   ```
6. (Optional, for real-time SMS notifications) Add a second daemon:
   ```
   php /home/forge/your-site/artisan reverb:start --host=0.0.0.0 --port=8080
   ```
   Then proxy `wss://yourdomain.com/app` to `localhost:8080` via Nginx.

### 3.3 Manual VPS (Nginx + PHP-FPM)

```bash
# Install OS packages (Ubuntu 24.04 example)
sudo apt update
sudo apt install -y nginx php8.3 php8.3-fpm php8.3-mbstring php8.3-xml php8.3-bcmath \
                    php8.3-curl php8.3-mysql php8.3-sqlite3 php8.3-zip php8.3-intl \
                    mariadb-server composer nodejs npm git

# Clone + install
cd /var/www && sudo git clone https://your-repo laracontact && cd laracontact
sudo composer install --no-interaction --prefer-dist --optimize-autoloader
sudo npm ci && sudo npm run build

# Configure
sudo cp .env.example .env
sudo nano .env  # set APP_URL, APP_ENV=production, APP_DEBUG=false, DB_*, MAIL_*
sudo php artisan key:generate
sudo php artisan migrate --force
sudo php artisan storage:link
sudo php artisan config:cache route:cache view:cache

# Permissions
sudo chown -R www-data:www-data storage bootstrap/cache database
sudo chmod -R ug+rwX storage bootstrap/cache database
```

Nginx site config:
```nginx
server {
    listen 80;
    server_name yourdomain.com;
    root /var/www/laracontact/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/run/php/php8.3-fpm.sock;
    }

    location ~ /\.(?!well-known).* { deny all; }
}
```

Then run `certbot --nginx -d yourdomain.com` for free SSL.

### 3.4 Shared hosting (cPanel / Plesk)

LaraContact works on shared hosting if the host supports PHP 8.3:

1. Upload the project ZIP via File Manager and extract above `public_html`.
2. Point the domain's document root to `your-project/public`.
3. Use the hosting panel's "Composer" and "Node.js" tools to run `composer install` and `npm run build` (or upload pre-built `vendor/` and `public/build/`).
4. Use the panel's MySQL Wizard to create a DB; fill the credentials in `.env`.
5. SSH (if available): `php artisan migrate --force && php artisan storage:link`.
6. **Queue/Scheduler:** Shared hosts typically only support cron. Add `* * * * * cd /home/user/your-project && php artisan schedule:run >> /dev/null 2>&1`. Queued mail and reminders rely on this.

---

## 4. Environment variables

Minimum required in `.env`:

```env
APP_NAME=LaraContact
APP_ENV=production
APP_KEY=                          # php artisan key:generate
APP_DEBUG=false
APP_URL=https://yourdomain.com

# Database — pick one
DB_CONNECTION=sqlite              # or mysql / pgsql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=laracontact
# DB_USERNAME=your_user
# DB_PASSWORD=your_password

# Mail — defaults to writing to laravel.log
MAIL_MAILER=log                   # log | smtp | resend | mailgun | postmark | ses

# Queue (use database for shared hosting, redis for VPS+)
QUEUE_CONNECTION=database         # or redis

# Sessions / cache
SESSION_DRIVER=database
CACHE_STORE=database

# Broadcasting (optional, for real-time inbound SMS toasts)
BROADCAST_CONNECTION=reverb
REVERB_APP_ID=
REVERB_APP_KEY=
REVERB_APP_SECRET=
REVERB_HOST=yourdomain.com
REVERB_PORT=443
REVERB_SCHEME=https
VITE_REVERB_APP_KEY="${REVERB_APP_KEY}"
VITE_REVERB_HOST="${REVERB_HOST}"
VITE_REVERB_PORT="${REVERB_PORT}"
VITE_REVERB_SCHEME="${REVERB_SCHEME}"
```

**Most third-party credentials (Twilio, Anthropic, Mailgun/Resend/etc.) are configured from inside the app — Settings → Integrations** — and do not need to be set in `.env`.

---

## 5. Connecting Twilio (SMS, WhatsApp, Calls)

1. Sign up at [twilio.com](https://www.twilio.com).
2. Buy a phone number with **SMS + Voice** capability (~$1/month US).
3. (Optional) For WhatsApp, activate the [WhatsApp Business sandbox](https://www.twilio.com/console/sms/whatsapp/learn) or apply for a verified sender.
4. In LaraContact: **Settings → Twilio**, paste:
   - **Account SID** (from Twilio Console homepage)
   - **Auth Token**
   - **Phone Number** in E.164 format (e.g. `+14155558881`)
   - **API Key SID + Secret** and **Application SID** (only if you're enabling browser-based voice calling)
5. **Uncheck "Fake mode"** to enable real delivery.
6. In Twilio Console, set the **"A Message Comes In" webhook** for your number to:
   ```
   https://yourdomain.com/webhooks/twilio/sms
   ```
   This enables two-way SMS — inbound replies will arrive in the app and trigger real-time toasts.

---

## 6. Connecting Anthropic Claude (AI features)

1. Sign up at [console.anthropic.com](https://console.anthropic.com).
2. Create an API key (starts with `sk-ant-`).
3. In LaraContact: **Settings → AI**, paste the key.
4. Pick a model. Recommended:
   - `claude-opus-4-7` — best for AI enrichment + translation (smartest)
   - `claude-haiku-4-5-20251001` — faster + cheaper for spell-check + tag suggestions
5. Click **Test AI connection** to confirm.

If no key is set, AI features fall back to regex/heuristic mode and continue to work.

---

## 7. Mail provider setup

LaraContact supports multiple drivers through **Settings → Mail**:

| Driver | Best for | Setup |
|---|---|---|
| `log` | Local dev only | Writes to `storage/logs/laravel.log` |
| `smtp` | Most providers | Host/port/user/password/encryption |
| `resend` | Easiest API setup | API key only |
| `mailgun` | High-volume sending | API key + domain |
| `postmark` | Transactional | API key only |
| `ses` | AWS users | Configure via `.env` AWS keys |

Click **Send test email** in the settings page to verify.

---

## 8. Updating to a new version

```bash
cd /your/project
git pull origin main            # or replace files from new ZIP
composer install --no-dev --optimize-autoloader
npm ci
npm run build
php artisan migrate --force
php artisan optimize:clear
php artisan optimize
```

If you use queue workers / Reverb, restart them: `php artisan queue:restart`.

---

## 9. Backup

The two things to back up:

1. **Database** — `mysqldump`, `pg_dump`, or copy `database/database.sqlite`
2. **`storage/app/public/`** — uploaded logos, custom field attachments

`.env` should be backed up to a secrets vault (not your DB backup).

---

## 10. Troubleshooting

| Symptom | Likely cause | Fix |
|---|---|---|
| 500 on every page | `APP_KEY` not set | `php artisan key:generate` |
| Mixed-content errors on HTTPS | Reverse proxy not trusted | Set `APP_URL=https://...` and ensure `TrustProxies` middleware sees your proxy |
| `Class "Spatie\..." not found` after upgrade | Composer cache | `composer dump-autoload -o` |
| Logo doesn't appear | Storage link missing | `php artisan storage:link` |
| Reminders never fire | Scheduler not running | Add the cron line in §3.x |
| Queued emails never send | Worker not running | Run `php artisan queue:work` or add daemon |
| Twilio webhook 403 | Signature mismatch | Token in app must match Twilio Auth Token; webhook URL must be exact match (including `https://`) |
| `Inappropriate ioctl for device` | GPG signing in CLI | Set `GPG_TTY=$(tty)` or `git -c commit.gpgsign=false ...` |

---

## 11. License

This product is licensed per the Envato CodeCanyon license you purchased. See `LICENSE.md` for details.

## 12. Support

Support is provided via the Envato item page or the contact listed in the product description. Include your **Envato Purchase Code**, server stack details (`php -v`, OS, web server), and the relevant `storage/logs/laravel.log` excerpt when filing a ticket.
