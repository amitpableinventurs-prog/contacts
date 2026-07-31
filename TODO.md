# Import/Export - Add missing fields

## Steps
- [x] 1. Analyze codebase - Understand current import/export fields
- [x] 2. Get user approval on plan
- [x] 3. Update `ImportsController::FIELDS` constant - Add `birthday`, `rating`, `lifecycle_stage`, `facebook`, `twitter`, `linkedin`, `status`, `admin_comment`, `phone_country`
- [x] 4. Update `ImportsController::template()` - Add new headers & example row
- [x] 5. Update `ImportsController::buildMappingPreview()` - Add auto-mapping rules for new fields
- [x] 6. Update `resources/views/imports/upload.blade.php` - Update supported fields list
- [x] 7. Update `resources/views/workspace/export.blade.php` - Ensure Gender is listed if missing

## ✅ Complete

