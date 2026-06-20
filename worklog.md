---
Task ID: 1
Agent: main
Task: Fix marketplace product images, use categories table, fix videos creators, bottom nav categories

Work Log:
- Investigated marketplace image issue: SQL files had correct /uploads/ paths but production DB still had old placehold.co URLs
- Added `runDataMigrations()` method in `core/App.php` that runs on every bootstrap to fix stale placehold.co URLs in all tables
- Migration uses per-migration flag files in `storage/migrations/` to run only once
- Covers: marketplace_listings, users, reels, videos, livestreams, marketplace_categories, posts
- Updated `MarketplaceController::index()` to JOIN with `marketplace_categories` table for both categories and products
- Updated product image resolution: now catches any external URL (placehold.co or any http://) and falls back to local uploads by product ID
- Updated `MarketplaceController::show()` with same image fix and categories JOIN
- Updated marketplace view category icons to use `cover_url` from categories table
- Added fallback for Videos page "Creators to Watch" when DB query fails (tries users table, then hard-coded local data)
- Verified bottom nav categories button already links to `/marketplace/categories`

Stage Summary:
- `core/App.php`: Added `runDataMigrations()` and `runMigration()` methods for automatic placehold.co URL fixing
- `app/Http/Controllers/MarketplaceController.php`: Rewrote `index()` and `show()` to use marketplace_categories table, improved image URL resolution
- `resources/views/marketplace/index.php`: Updated category icon to use `cover_url` from DB
- `app/Http/Controllers/VideoController.php`: Added multi-level fallback for creators to watch
