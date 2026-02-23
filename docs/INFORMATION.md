# Integration Summary

## Overview
Integrated the Taste.it Bootstrap template into the Laravel Blade system and routed all template pages.

## Assets
- Moved template assets (CSS, JS, images, fonts) into public/assets/template/.
- Updated all asset references to use the Laravel helper: {{ asset('assets/template/...') }}.

## Layout and Views
- Created the master layout with template head, header, and footer in resources/views/layouts/app.blade.php.
- Added a home page view in resources/views/home.blade.php with the template’s index content.
- Added additional template pages:
  - resources/views/about.blade.php
  - resources/views/chef.blade.php
  - resources/views/menu.blade.php
  - resources/views/reservation.blade.php
  - resources/views/blog.blade.php
  - resources/views/blog-single.blade.php
  - resources/views/contact.blade.php

## Routing
- Updated routes/web.php to add named routes for:
  - / (home)
  - /about
  - /chef
  - /menu
  - /reservation
  - /blog
  - /blog-single
  - /contact
- Updated the navbar in resources/views/layouts/app.blade.php to use named routes and active state.

## Hosting Fixes
- Added root-level rewrite rules in .htaccess to forward requests into /public when the project root is used as the document root.
- Added a JS asset base (`window.TEMPLATE_ASSET_BASE`) and fixed the map marker path in public/assets/template/js/google-map.js.

## Result
The site now renders the Taste.it template through Blade and Laravel routing, preserving the original responsive design and layout behavior.

## Production Incident Fix (Feb 2026)
- Error seen: `ParseError ... unexpected end of file, expecting "elseif" or "else" or "endif"` reported near `resources/views/layouts/app.blade.php` end.
- Root cause: JSON-LD keys `@context` and `@type` in Blade were interpreted as directives.
- Code fix: escaped those keys to `@@context` and `@@type` in `resources/views/layouts/app.blade.php`.
- Operational fix after deploy:
  - `php artisan optimize:clear`
  - `php artisan view:cache`

## Email Booking Notifications
- Added booking controller (app/Http/Controllers/BookingController.php) to handle form submissions.
- Added mailables for admin and user notifications:
  - app/Mail/AdminBookingNotification.php
  - app/Mail/UserBookingConfirmation.php
- Added shared HTML email layout and templates:
  - resources/views/emails/layout.blade.php
  - resources/views/emails/admin-booking.blade.php
  - resources/views/emails/user-booking.blade.php
- Wired booking forms to POST /booking and added success/error UI on the reservation page.
- Updated .env.example with SMTP 465/SSL placeholders.

## Newsletter System
- Added newsletter subscribe form in the footer and wired it to POST /newsletter/subscribe.
- Created NewsletterSubscriber model and migration:
  - app/Models/NewsletterSubscriber.php
  - database/migrations/2026_02_05_000003_create_newsletter_subscribers_table.php
- Added newsletter controller:
  - app/Http/Controllers/NewsletterController.php
- Added newsletter mailable and template:
  - app/Mail/NewsletterDigest.php
  - resources/views/emails/newsletter-digest.blade.php
- Added Artisan command and scheduled it every 3 days:
  - app/Console/Commands/SendNewsletterDigest.php
  - routes/console.php
- Registered command discovery in bootstrap/app.php.

## Content & Copy Updates
- Updated home/about/reservation “Perfect Ingredients” section with a Gordon Ramsay–inspired quote.
- Rewrote homepage testimonials and chef bios with human, unique text and mirrored testimonials on the about page.
- Updated footer brand description and newsletter blurb in resources/views/layouts/app.blade.php.

## SEO & Search Updates
- Updated layout metadata in resources/views/layouts/app.blade.php:
  - Dynamic title fallback with `@yield('title', 'Oceanova - Enjoy Fine Dining')`
  - Meta description
  - Canonical URL (`url()->current()`)
  - Social preview image tags (`og:image`, `twitter:image`)
  - Restaurant JSON-LD schema
- Updated robots file and sitemap support:
  - public/robots.txt allows crawling and references sitemap.xml
  - public/sitemap.xml created with core site URLs
- Added Google site verification file in public/google6f74536275d2d25b.html.
- Updated schema address to: Plot 7/8 Okun-Ajah Community Rd.

## Server CLI PHP 8.3 Profile Fix
- Composer initially failed because server default CLI PHP resolved to 8.1 while project requires `>= 8.3`.
- Resolved by forcing PHP 8.3 in shell profile and aliasing composer through that PHP binary:
  - `export PATH=/opt/alt/php83/usr/bin:$PATH`
  - `alias php='/opt/alt/php83/usr/bin/php'`
  - `alias composer='php /usr/local/bin/composer'`
- Reload profile with `source ~/.bash_profile` (or `~/.bashrc` on shells that ignore `.bash_profile`).

## Digital Menu Rebuild (Blade Component + Tailwind)
- Created reusable Blade component:
  - resources/views/components/menu-item.blade.php
- Rebuilt resources/views/menu.blade.php as a mobile-first digital menu with:
  - Sticky category navigation
  - Black-and-gold theme with white cards
  - Responsive grid (1/2/3 columns)
  - Label chips for dietary tags (V, L, P, S)
- Replaced placeholder tier content with full provided menu content (breakfast, soups, mains, national dishes, platters/sides).
- Added menu card image support:
  - `x-menu-item` now accepts `image`
  - deterministic fallback images are used when no image is provided
- Fixed Blade rendering/syntax issues caused by mixed old/new menu markup.

## Menu Watermark Visibility Fix
- Issue: menu-page watermark was present but too faint on the black background.
- Verification: image assets existed in `public/images`, but visual contrast/opacity settings prevented clear display.
- Fix applied in `resources/views/menu.blade.php`:
  - watermark source set to `asset('images/oceanova.png')`
  - opacity increased to `opacity-20`
  - blend mode set to `mix-blend-screen`
  - tile size increased to `background-size: 260px auto`

## Inventory & Procurement Module (Feb 2026)

### Objective
Implemented a robust backend module for inventory and procurement operations with role-specific behavior for:

- Procurement Officer
- Kitchen Manager
- General Order Person

### Database Additions

#### New/Updated Migrations
- `database/migrations/2026_02_23_000010_add_role_to_users_table.php`
  - Adds `role` field to users with default `general_order_person`.
- `database/migrations/2026_02_23_000011_create_ingredients_table.php`
  - `name`, `unit`, `current_stock`, `min_stock_alert_level`.
- `database/migrations/2026_02_23_000012_create_procurements_table.php`
  - Links procurements to ingredients.
- `database/migrations/2026_02_23_000013_create_recipes_table.php`
  - Links meals (`menu_item_id`) to ingredients and required quantity.
- `database/migrations/2026_02_23_000014_create_inventory_logs_table.php`
  - Full movement audit trail (`in`, `out`, `waste`) with actor reference.

#### Constraint Strategy
- All domain tables use foreign key constraints.
- Ingredient-linked records cascade on ingredient deletion.
- `inventory_logs.user_id` is nullable and uses `nullOnDelete()`.
- Recipe enforces uniqueness per `(menu_item_id, ingredient_id)`.

### Domain Models

#### New Models
- `app/Models/Ingredient.php`
- `app/Models/Procurement.php`
- `app/Models/Recipe.php`
- `app/Models/InventoryLog.php`

#### Updated Models
- `app/Models/Meal.php`
  - Added `recipes()` relation.
- `app/Models/User.php`
  - Added `role` fillable, `inventoryLogs()` relation, and role helper methods:
    - `hasRole()`
    - `hasAnyRole()`

### Business Logic Service

#### File
- `app/Services/InventoryService.php`

#### Implemented Flows
- `stockIn(...)`
  - Creates procurement record
  - Increments ingredient stock
  - Writes inventory log type `in`

- `logWaste(...)`
  - Validates positive quantity
  - Ensures enough stock exists
  - Decrements stock
  - Writes inventory log type `waste`

- `processOrderStockOut(...)`
  - Reads order item quantities
  - Resolves recipes by meal
  - Aggregates required ingredients
  - Decrements stock and logs type `out`

- `processOrderStockAdjustment(...)`
  - On order edit, computes old-vs-new ingredient requirements
  - Applies delta:
    - positive delta => stock out
    - negative delta => restock (log type `in`)

### Transaction and Concurrency Guarantees
- All stock mutations run inside DB transactions.
- Ingredient rows are locked with `lockForUpdate()` during mutation.
- Any stock validation failure aborts and rolls back the transaction.

### Role Enforcement

#### Middleware
- `app/Http/Middleware/EnsureUserRole.php`
  - Enforces role access using user role helpers.

#### Registration
- `bootstrap/app.php`
  - Middleware alias registered as `role`.

### Controllers Added
- `app/Http/Controllers/ProcurementController.php`
  - Procurement listing and stock-in endpoint.
- `app/Http/Controllers/KitchenInventoryController.php`
  - Stock levels, low-stock alerts, and waste logging.

### Routes Added

#### Procurement Officer
- `GET /admin/procurements`
- `POST /admin/procurements`

#### Kitchen Manager
- `GET /admin/inventory/stock-levels`
- `GET /admin/inventory/low-stock`
- `POST /admin/inventory/waste`

All above are protected by `auth` + `role:*` middleware in `routes/web.php`.

### Order Flow Integration

#### Order Create
- `app/Filament/Resources/OrderResource/Pages/CreateOrder.php`
  - Overridden `handleRecordCreation()` executes:
    - create order
    - sync orderItems table
    - run inventory stock-out

#### Order Edit
- `app/Filament/Resources/OrderResource/Pages/EditOrder.php`
  - Overridden `handleRecordUpdate()` executes:
    - capture old items
    - update order
    - sync orderItems table
    - run inventory delta adjustment

#### General Order Person Access
- `OrderResource` now restricts visibility and create/edit to:
  - `general_order_person`

### Seeder Added

#### Ingredient Seeder
- `database/seeders/IngredientSeeder.php`
  - Includes the provided ingredient catalog.
  - Cleans input list:
    - trims values
    - skips empty values
    - removes placeholder value `Item Name`
    - de-duplicates case-insensitively
  - Sets defaults for seeded rows:
    - `unit` = `pcs`
    - `current_stock` = `0`
    - `min_stock_alert_level` = `10`

#### DatabaseSeeder Update
- `database/seeders/DatabaseSeeder.php`
  - Calls `IngredientSeeder`.
  - Creates default role users for testing:
    - procurement@example.com
    - kitchen@example.com
    - test@example.com

### Runbook
After pulling changes:

- `composer install`
- `composer dump-autoload`
- `php artisan migrate`
- `php artisan db:seed`
- `php artisan route:list --path=admin`

If needed after deploy:

- `php artisan optimize:clear`
