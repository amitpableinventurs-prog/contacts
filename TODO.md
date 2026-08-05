# TODO: Fix 404 error when uploading DP (display picture)

## Root Cause
The `public/storage` symlink is broken/missing. Uploaded DP images are stored via the `public` disk (to `storage/app/public/`), but the web server serves them from `public/storage/` which is a **real directory** (not a symlink to `storage/app/public`). Since `storage/app/public` doesn't exist, uploaded photos can never be served → 404.

## Steps

- [x] Analyze codebase and confirm root cause
- [x] Inspect contents of `public/storage` (existing files to preserve)
- [x] Create `storage/app/public` directory
- [x] Move existing files from `public/storage/` into `storage/app/public/`
- [x] Remove the real `public/storage` directory
- [x] Create a directory junction `public/storage` → `storage/app/public`
- [x] Add `StorageFileController` + `/storage/{path}` route as a fallback for
      hosts where the symlink/junction isn't followed (e.g. symlink() disabled
      on shared hosting) — covered by `tests/Feature/StorageFileTest.php`
- [x] Verify the fix (structure + storage:link behavior; full test suite green)

