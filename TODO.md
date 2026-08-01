# TODO - Contacts List & Details Layout Changes

## Completed

- [x] **DP image upload fix** — Added missing `</div>` in `_form.blade.php` photo upload wrapper
- [x] **Website field** — Added open/visible Website input between City and Birthday in `_form.blade.php`
- [x] **Area field** — Added Area field (migration, model, validation, form, show, import, export)
- [x] **Contacts list (results) columns** — `index.blade.php`:
  - Kept: Name, Category, Tags
  - Added: City (with Area subtext), Added date (date + time)
  - Removed: Company, Added by, Approved by
- [x] **Contact details** — `show.blade.php`:
  - Renamed "Owner" label to "Added by"
  - Added "Approved by" display
  - Kept "Created" date

## Verification
- [x] Migration ran successfully (`2026_07_22_000001_add_area_to_contacts_table`)
- [x] Blade views compile without errors (`php artisan view:cache`)

