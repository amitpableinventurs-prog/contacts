# LaraContact — User Guide

Welcome! This guide walks through every feature in LaraContact from the user's perspective. If you're a developer installing the app, see [INSTALL.md](./INSTALL.md) first.

---

## Table of contents

1. [Signing in](#1-signing-in)
2. [The dashboard](#2-the-dashboard)
3. [Working with contacts](#3-working-with-contacts)
4. [Importing from CSV](#4-importing-from-csv)
5. [Communication: SMS, WhatsApp, Calls, Email](#5-communication)
6. [AI-assisted workflows](#6-ai-assisted-workflows)
7. [Reminders and follow-ups](#7-reminders-and-follow-ups)
8. [Workspace and team invitations](#8-workspace-and-team-invitations)
9. [Library: Groups and Tags](#9-library-groups-and-tags)
10. [Audit log](#10-audit-log)
11. [Settings reference](#11-settings-reference)
12. [Public JSON API](#12-public-json-api)
13. [Keyboard shortcuts](#13-keyboard-shortcuts)
14. [Frequently asked questions](#14-frequently-asked-questions)

---

## 1. Signing in

- Navigate to your install URL.
- The landing page has **Sign in** and **Get started** buttons.
- Registering creates your **personal workspace** automatically — there's no separate "create company" step.
- If your admin has disabled public registration, you'll only see **Sign in**. You'll need an invitation from a workspace owner.

**Forgot your password?** Click *Forgot?* on the login page. A reset link is emailed to you (provided mail is configured).

---

## 2. The dashboard

The dashboard is your daily home. Top to bottom:

- **🚀 Getting started checklist** — A 6-step onboarding card with progress bar. Each step links you to the relevant page. Dismiss it once you're set up.
- **Hero strip** — Greeting + quick-action buttons (Add contact, Compose email, Import, Template).
- **KPI cards** — Contacts, Messages, Calls, Emails — with a month-over-month delta chip (green up, red down, grey flat).
- **Pipeline** — Contacts grouped by lifecycle stage (lead, prospect, customer, partner, vendor) with progress bars.
- **Recent activity** — A unified feed of SMS, WhatsApp, calls, and emails.
- **Today's reminders** — Top 3 due, with an overdue counter.
- **🔥 Hot prospects** — Contacts auto-filtered by tags like `vip`, `hot`, `enterprise`, `follow-up`.
- **🎂 Birthdays** — Upcoming contact birthdays.

---

## 3. Working with contacts

### 3.1 Adding a contact

Three ways:

1. **Manually**: Click **Add contact** on the dashboard or `/contacts`. Fill in the form. Only **Name** is required.
2. **From a paste**: On the create page, paste an email signature, LinkedIn snippet, or business card text into the **AI-assisted fill** box and click **Suggest fields**. The form auto-populates name, email, phone, company, job title, website, and LinkedIn.
3. **From CSV**: See [§4 Importing from CSV](#4-importing-from-csv).

### 3.2 The contact form fields

- **Basics**: name (required), email, phone, company, job title, website, address
- **Lifecycle stage**: lead / prospect / customer / partner / vendor — drives the dashboard pipeline view
- **Birthday**: shown on the dashboard birthday card
- **Group**: contacts belong to one group (Customers, Leads, etc.)
- **Tags**: many-to-many free-form labels (VIP, follow-up, etc.). Click **✨ Suggest from context** to have AI suggest tags based on the contact's notes + company + title.
- **Social**: optional Twitter, LinkedIn, Facebook handles
- **Notes**: free-text scratchpad
- **Custom fields**: per-contact key/value pairs. Click **Add field** to create a new row (e.g. *"Birthday gift idea" → "Coffee tour"*).

### 3.3 Viewing a contact

Open any contact to see:

- **Left rail**: avatar, name, title, company, lifecycle badge, group, key contact details (email, phone, website, address, birthday, last contacted, owner), tag chips.
- **Tabs**:
  - **Activity** — chronological feed of all SMS, WhatsApp, calls, and emails with this contact
  - **Notes** — the free-text notes you wrote
  - **Custom fields** — your custom key/value pairs
- **Action bar (top right)**: Call (logs a call entry), SMS (opens the message thread), Edit, and a **⋯** menu with Delete.
- If duplicates of this contact exist in your workspace (same email or phone), a **⚠ Potential duplicate** banner appears with a **Review & merge** button.

### 3.4 Searching and filtering

- **Search bar**: matches name, email, phone, or company (server-side)
- **Group filter**: dropdown of your groups
- **Tag chips**: click any tag at the top of the page to filter; click again to unfilter
- **Command palette (⌘K)**: global search from anywhere

### 3.5 Bulk operations

Select multiple contacts via the checkbox column. A sticky action bar appears:

- **Add to group** — bulk-assign a group
- **Add tag** — bulk-tag
- **Delete** — soft-delete (recoverable from DB)

### 3.6 Duplicate merge

Open a contact, look for the **⚠ Potential duplicates** banner. Click **Review & merge**, check the boxes for the duplicates you want to absorb, and submit. Behavior:

- Blank fields on the kept contact get filled from duplicates
- All tags are combined
- All SMS, WhatsApp, calls, and emails are reassigned to the kept contact
- Duplicate records are hard-deleted

The whole operation runs in a transaction — if any step fails, nothing changes.

---

## 4. Importing from CSV

1. From the dashboard, click **Template** to download `contacts-import-template.csv` with the right headers + one example row.
2. Fill in your rows. Required column: **Name**. Optional: Email, Phone, Company, Job title, Website, Address, Notes, **Tags** (comma-separated, e.g. `vip, follow-up`).
3. Click **Import** (dashboard) or **Import CSV** (`/contacts/import`).
4. Upload your file. The preview page auto-detects which CSV column maps to which contact field. Adjust if needed.
5. Optionally assign the entire batch to a group via the dropdown.
6. Click **Import contacts**.

Tags column behavior: if a tag doesn't exist in your workspace yet, it's **created on the fly** with auto-slug.

---

## 5. Communication

### 5.1 SMS and WhatsApp

- Go to **Messages** in the sidebar, or click **SMS** on a contact.
- The thread view shows iMessage-style bubbles. Outbound messages are colored with your primary color; WhatsApp is green; inbound is muted grey.
- Type your message, choose **SMS** or **WhatsApp** in the toggle, click **Send**.
- **AI assist** buttons inline:
  - **Fix** — rewrites your message to correct spelling and grammar (preserves voice)
  - **Translate** — pick from 9 languages
- **Two-way SMS**: when a contact replies to your Twilio number, the message arrives in the thread automatically and a **toast notification** appears in any open browser session. The page title also gets a `•` prefix until you click back to that tab.

In **fake mode** (no Twilio credentials configured), messages are recorded with a `FAKE...` SID and the UI shows a banner. Inbound webhook still works — useful for testing.

### 5.2 Calls

- Go to **Calls** in the sidebar.
- The dialer pad accepts manual numbers, or click **Call** on any contact to log a call.
- Browser-based voice calling requires real Twilio credentials + a TwiML Application SID configured in **Settings → Twilio**.
- The right side shows recent call history with status badges.

### 5.3 Email

- Go to **Email** in the sidebar → click **Compose**.
- Search and pick a contact via the autocomplete field.
- Write subject + body. Email is queued and dispatched via your configured mail driver.
- The email contains an invisible tracking pixel — once the recipient opens it, the **Opens** column in `/emails` increments.

---

## 6. AI-assisted workflows

LaraContact integrates with Anthropic Claude for four user-facing features. All work in **fake mode** (heuristic fallbacks) if no API key is set.

| Feature | Where to use it | What it does |
|---|---|---|
| **Contact enrichment** | `/contacts/create` → AI-assisted fill card | Paste signature/text → extracts name, email, phone, company, title, website, LinkedIn |
| **Spell-check & grammar fix** | SMS thread composer → **Fix** button | Rewrites your message correctly without changing voice |
| **Translation** | SMS thread composer → **Translate** button | 9 languages (English, Spanish, French, German, Italian, Portuguese, Chinese, Japanese, Arabic) |
| **Tag suggestion** | `/contacts/create` → Tags card → **Suggest from context** | Picks the best-fitting tags from your workspace's library based on notes + company + title |

Configure your Anthropic API key in **Settings → AI**. Click **Test AI connection** to verify.

---

## 7. Reminders and follow-ups

### 7.1 List view (`/reminders`)

Pending reminders grouped by:

- **Overdue** (red badge)
- **Today** (amber)
- **This week**
- **Later**

Each row has a circle to mark complete and a Delete button. The footer shows recently completed reminders, dimmed and struck through.

### 7.2 Calendar view (`/reminders/calendar`)

Monthly grid. Click any day to schedule a reminder for that date — a modal pops up. Each day shows up to 3 reminder chips colored by status. Use the arrows to navigate months, or the **Today** button to jump back.

### 7.3 Notifications

Reminders fire via the `reminders:send-due` scheduled command (runs every 5 minutes). When a reminder is due:

- If **Email me** is checked → an email is sent to the reminder's owner
- If **SMS me** is checked and your user profile has a phone → an SMS is sent via Twilio

Each reminder is notified only once, then marked `notified_at`.

---

## 8. Workspace and team invitations

LaraContact supports multiple workspaces per user. Each workspace has its own contacts, tags, groups, settings, and audit log.

### 8.1 Inviting a teammate

- Owner only.
- Go to **Workspace → Members** in the sidebar.
- Enter their email + role (Admin or Member) → **Send invite**.
- They receive an email with an accept link. The link is valid for 7 days.
- Once they accept, they appear in the members list with their role badge.

### 8.2 Removing a member

Owner only. Click **Remove** on any member row. The owner cannot be removed. If the removed user had the workspace set as their current workspace, they're auto-switched to their personal workspace.

### 8.3 Switching workspaces

Click the workspace name in the sidebar header → dropdown lists every workspace you belong to → click to switch. Your current workspace ID is saved on your user record.

### 8.4 Public registration toggle

The owner can disable public registration in **Settings → General**. When off, the `/register` URL returns 404 and the **Create account** link disappears from the login page. The only way to onboard new users becomes a team invitation.

---

## 9. Library: Groups and Tags

### 9.1 Groups

A contact can belong to **at most one group**. Use for big-picture buckets (Customers, Leads, Partners). Each group has a color shown in the contact list.

- `/groups` — list, inline create, inline edit, delete
- Deleting a group does NOT delete its contacts; their `group_id` is set to null

### 9.2 Tags

A contact can have **many tags**. Use for flexible labels (VIP, hot, follow-up, referral). Each tag has an accent color and an auto-generated slug.

- `/tags` — list, inline create, inline edit, delete
- Tag names must be unique per workspace
- Tags used in CSV imports (`Tags` column with comma-separated values) are auto-created if missing

---

## 10. Audit log

`/audit` shows every change to every contact in this workspace: who, what, when. The diff renders old and new values for every changed field.

Powered by `spatie/laravel-activitylog`. The `Contact` model uses `logOnlyDirty` and `dontSubmitEmptyLogs` so the log isn't spammed with no-op saves.

---

## 11. Settings reference

Click your avatar → **Settings**, or the **Settings** link in the sidebar.

### General
- App name, tagline, locale
- Footer text (shown on landing page)
- **Public registration toggle**

### Branding
- Logo upload (PNG / JPG / SVG / WebP, max 2 MB)
- Primary color — drives every button, link, and accent in the entire app (live-applied via CSS custom properties)
- Email signature — appended to outbound contact emails

### Mail
- Mailer driver (log, SMTP, Resend, Mailgun, Postmark, SES)
- From email + name
- SMTP host/port/user/encrypted-password/encryption
- API drivers: encrypted API key + (Mailgun) domain
- **Send test email** button

### Twilio
- Fake mode toggle
- Account SID + Auth Token (encrypted)
- Phone number, API Key SID/Secret (encrypted), Application SID
- Inbound webhook URL is shown — copy it into your Twilio Console

### AI
- Fake mode toggle
- Anthropic API key (encrypted)
- Model selector (Opus 4.7 / Sonnet 4.6 / Haiku 4.5)
- Max tokens
- **Test AI connection** button

---

## 12. Public JSON API

LaraContact exposes a Sanctum-protected JSON API.

### Creating a token

- Click your avatar → **API tokens**.
- Click **Create token**, give it a name. The token is shown **once**; copy it before navigating away.

### Endpoints

| Method | Path | Description |
|---|---|---|
| `GET` | `/api/user` | Current authenticated user |
| `GET` | `/api/contacts` | Paginated contacts (`?q=`, `?per_page=25`) |
| `GET` | `/api/contacts/{id}` | One contact with relations |
| `POST` | `/api/contacts` | Create contact |
| `PUT` | `/api/contacts/{id}` | Update contact |
| `DELETE` | `/api/contacts/{id}` | Soft-delete contact |

### Example

```bash
curl -H "Authorization: Bearer YOUR_TOKEN" \
     -H "Accept: application/json" \
     https://yourdomain.com/api/contacts
```

### Rate limit

60 requests/minute per token. Exceeded requests get `429 Too Many Requests` with a `Retry-After` header.

---

## 13. Keyboard shortcuts

| Shortcut | Action |
|---|---|
| ⌘K / Ctrl+K | Open command palette (global search + nav) |
| ↑ / ↓ | Navigate within command palette |
| Enter | Open selected result |
| Esc | Close any modal or palette |

---

## 14. Frequently asked questions

**Q: My SMS sends say `FAKE...` and don't actually arrive on phones.**
A: You're in fake mode (no Twilio credentials). Go to **Settings → Twilio**, paste your Account SID, Auth Token, and phone number, and uncheck **Fake mode**.

**Q: I added an Anthropic API key but AI still seems to fall back.**
A: Make sure **Fake mode** is unchecked in **Settings → AI**. Also use the **Test AI connection** button to confirm the key works.

**Q: How do I export my contacts?**
A: Not currently built into the UI. You can pull them via the JSON API, or run `SELECT * FROM contacts WHERE team_id = ?` against your database. CSV export is on the roadmap.

**Q: Can I import vCards?**
A: Not yet — only CSV. vCard support is on the roadmap.

**Q: Reminders never email me even though they're overdue.**
A: Verify the scheduler is running. On a managed host that's a cron entry; in dev that's `php artisan schedule:work` in a separate terminal.

**Q: Where are uploaded logos stored?**
A: `storage/app/public/branding/`. Make sure you ran `php artisan storage:link` so they're served from `public/storage/branding/`.

**Q: Can I customize the email templates?**
A: Yes — they live in `resources/views/emails/`. Edit them directly. The current templates are minimal inline-styled HTML.

**Q: Does it work on mobile browsers?**
A: Yes. The sidebar collapses into a slide-over on small screens, tables hide secondary columns, and the dashboard re-stacks into a single column.

**Q: Is dark mode supported?**
A: Yes — toggle it via the moon/sun icon in the top bar. Your preference is saved in `localStorage` and respected on next visit. System preference is honored on first load.

**Q: Can the same user belong to multiple workspaces?**
A: Yes. Accept multiple team invitations, then use the workspace switcher in the sidebar header to flip between them.

---

Found something missing or unclear? Check the Envato item page for support contact details.
