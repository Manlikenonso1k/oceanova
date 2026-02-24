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

## Procurement Dashboard Production Fix Log (Feb 2026)

### Incident A: Missing Filament page route
- Symptom:
  - `Route [filament.admin.pages.procurement-dashboard] not defined`
  - Triggered when loading `/admin`.
- Diagnosis:
  - `route:list` showed dashboard/procurement resources, but no procurement dashboard page route.
- Fixes:
  - Registered page in `app/Providers/Filament/AdminPanelProvider.php`.
  - Set explicit slug in `app/Filament/Pages/ProcurementDashboard.php`:
    - `protected static ?string $slug = 'procurement-dashboard';`
  - Added dedicated page blade:
    - `resources/views/filament/pages/procurement-dashboard.blade.php`.
- Verification:
  - `php artisan optimize:clear`
  - `php artisan route:list | grep -i procurement-dashboard`
  - Route present:
    - `admin/procurement-dashboard` (`filament.admin.pages.procurement-dashboard`)

### Incident B: Trait namespace fatal error
- Symptom:
  - `Trait "Filament\Pages\Concerns\HasFiltersForm" not found`
  - Appeared during `php artisan optimize:clear`.
- Root cause:
  - Trait namespace mismatch for installed Filament version.
- Fix:
  - Updated import in `app/Filament/Pages/ProcurementDashboard.php` to:
    - `Filament\Pages\Dashboard\Concerns\HasFiltersForm`

### Incident C: Supplier scorecard TableWidget key failure
- Symptom:
  - `TableWidget::getTableRecordKey(): Return value must be of type string, null returned`
  - Triggered on Livewire update while loading supplier performance widget.
- Root cause:
  - Grouped query rows had no `id`, so Filament table record key resolved to `null`.
- Fix in `app/Filament/Widgets/SupplierPerformanceScorecard.php`:
  - Added `selectRaw('MIN(id) as id')`.
  - Excluded invalid supplier groups:
    - `whereNotNull('supplier_name')`
    - `where('supplier_name', '!=', '')`
- Result:
  - Supplier performance widget renders without 500 errors.

## Frontend (Filament) Role Workflows

### Goal
Provide practical, UI-driven role workflows so operations can be executed from `/admin` without external API tools.

### Screens and Intended Users

#### Ingredients Screen
- Primary users: Kitchen Manager, Admin
- Main use cases:
  - Monitor stock balances
  - Identify low-stock items using built-in filter
  - Log waste with reason directly on each ingredient row

#### Procurements Screen
- Primary users: Procurement Officer, Admin
- Main use cases:
  - Stock in ingredients via create form
  - Track supplier deliveries and received timestamps

#### Recipes Screen
- Primary users: Admin
- Main use cases:
  - Configure per-menu-item ingredient consumption
  - Keep stock deduction mapping accurate for order operations

#### Inventory Logs Screen (Read-Only)
- Primary users: Kitchen Manager, Admin
- Main use cases:
  - Audit every stock movement in one place
  - Filter by movement type, date range, and actor
  - Verify traceability of stock in/out/waste actions

#### Users Screen (Admin Control)
- Primary users: Admin, Super Admin
- Main use cases:
  - Assign roles (`procurement_officer`, `kitchen_manager`, `general_order_person`)
  - Manage user identity data (name/email)
  - Reset/update passwords in admin panel
  - Create and remove user accounts

### Movement Auditing Logic

All frontend-driven inventory operations are logged to `inventory_logs` with actor (`user_id`) and reason:

- Stock in from Procurement screen → `type = in`
- Waste logged from Ingredients screen → `type = waste`
- Order-based consumption from order create/edit → `type = out` (and `in` for restock adjustments on quantity reductions)

Managers can review these records directly from the Inventory Logs screen without edit/delete permissions.

### Inventory Audit Export
- Inventory Logs screen includes `Export CSV` header action.
- Export respects currently applied filters:
  - movement type
  - date range
  - user/actor

### Dashboard Operational Visibility
- Added `Low Stock Alerts` dashboard table widget to `/admin`.
- Visibility:
  - kitchen_manager
  - admin
  - super_admin
- Shows only low-stock ingredients, including computed shortfall.

### Permission Boundaries in UI

- Procurement Officer:
  - Can use procurement frontend
  - Cannot manage recipes
  - Cannot edit ingredient master unless admin

- Kitchen Manager:
  - Can view ingredients and low-stock states
  - Can log waste
  - Cannot create procurements unless admin

- General Order Person:
  - Uses order screens
  - Triggers stock deduction automatically through recipes

- Admin:
  - Can supervise and access all role-gated flows

### Frontend Operational Sequence (Recommended)

1. Admin seeds/maintains ingredients and recipes.
2. Procurement Officer performs stock in for incoming deliveries.
3. Kitchen Manager monitors low stock and logs waste.
4. General Order Person creates/updates orders.
5. System auto-adjusts inventory and writes audit logs.

### Production Verification Steps

1. Login as procurement officer and create procurement.
2. Confirm ingredient `current_stock` increased.
3. Login as kitchen manager and log waste for same ingredient.
4. Confirm `current_stock` reduced correctly.
5. Create an order with recipe-linked menu items.
6. Confirm ingredient deductions and log entries are present.
7. Open Inventory Logs and export filtered CSV.
8. Open Users screen as admin and confirm role updates persist.

## Bar Management Module (Detailed Implementation)

### Business Goal
- Create a dedicated beverage inventory lane for barman operations.
- Keep bar inventory control independent from kitchen stock handling.
- Track period-based stock accountability with variance analysis.
- Enable barman procurement with receipt evidence upload.

### Schema Work Completed

#### Ingredient Extensions
Migration: database/migrations/2026_02_23_000016_add_category_sub_category_price_to_ingredients_table.php

Added to ingredients table:
- category (string, default Kitchen)
- sub_category (nullable string)
- price (decimal 12,2, default 0)
- category/sub_category index

#### Bar Stock Sheet Table
Migration: database/migrations/2026_02_23_000017_create_bar_stock_sheets_table.php

Created table: bar_stock_sheets

Columns:
- ingredient_id
- period_start, period_end
- opening_stock, added_stock
- trans_in, trans_out
- sales
- total_stock
- expected_closing
- closing_stock
- variance
- recorded_by

### Model Layer

#### New Model
app/Models/BarStockSheet.php

Includes:
- fillable for all stock-sheet fields
- decimal/date casts
- relations: ingredient(), recorder()

#### Updated Model
app/Models/Ingredient.php

Updated with:
- fillable: category, sub_category, price
- cast: price
- relation: barStockSheets()

### Inventory Service Logic

Updated file: app/Services/InventoryService.php

Added methods:

1) calculateBarStockMetrics(...)
- Total Stock = (Opening + Added + Trans In) - Trans Out
- Expected Closing = Total Stock - Sales
- Variance = Physical Closing - Expected Closing

2) createBarStockSheet(...)
- Pulls Added Stock from procurements in date range.
- Pulls Transfer In/Out from inventory_logs reason patterns.
- Pulls Sales from order-linked out logs.
- Stores calculated totals + variance in bar_stock_sheets.

### Barman UI Resource

Added:
- app/Filament/Resources/BarmanResource.php
- app/Filament/Resources/BarmanResource/Pages/ListBarmen.php
- app/Filament/Resources/BarmanResource/Pages/CreateBarman.php
- app/Filament/Resources/BarmanResource/Pages/EditBarman.php

Capabilities:
- Manage only beverage ingredients.
- Manage name, sub-category, unit, price, stock, alert level.
- Filter and sort by beverage categories.

Hard Scope:
- Query constrained to category = Beverage.
- Create/Edit mutate category to Beverage.

Access:
- barman, admin, super_admin

### Receipt Upload Enablement for Barman

Updated:
- app/Filament/Resources/ProcurementResource.php

Enhancements:
- barman granted view/create procurement access.
- barman ingredient selection limited to Beverage category.
- barman procurement list limited to records whose ingredient category is Beverage.

Receipt support:
- Uses existing procurement receipt upload field.
- Accepts PDF/JPG/PNG/WEBP.
- Mobile camera capture available via device/browser picker.

### Role Handling

Current runtime role system remains users.role-based.

Updated:
- app/Filament/Resources/UserResource.php
  - added barman as assignable role option.

Compatibility addition:
- database/seeders/RoleAndPermissionSeeder.php
  - safely creates barman role only if Spatie package is installed.

### Seeding

Added:
- database/seeders/BarInventorySeeder.php

Seed coverage:
- Cognac, Whiskey, Red Wine, White Wine, Sweet Wine Red, Sweet Wine White,
  Tequila, Vodka/Gin, Liqueur, Spark/Soft.

All seeded rows include:
- category = Beverage
- mapped sub_category
- Naira price mapping
- unit = pcs
- stock defaults for startup operations

DatabaseSeeder updates:
- calls RoleAndPermissionSeeder
- calls BarInventorySeeder
- creates a default barman user account

### Deployment Steps

Required:
- php artisan migrate
- php artisan db:seed
- php artisan optimize:clear

Optional Spatie enablement:
- composer require spatie/laravel-permission
- php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
- php artisan migrate
- php artisan db:seed

### QA Checklist
- Login as barman and verify Bar Management navigation appears.
- Confirm bar inventory list contains only Beverage items.
- Confirm kitchen-only ingredients are not visible.
- Create procurement as barman and upload receipt.
- Verify receipt link is visible on listing.
- Verify stock increment and log entry.
- Confirm procurement scope remains beverage-only for barman.

## Resolved Incident: Procurement User Login Loop

### What happened
- Procurement user login repeatedly redirected/spun after credential submit.

### What logs showed
- `Array to string conversion` from `app/Models/User.php` during Filament navigation resolution.
- Stack trace passed through Spatie HasRoles checks while evaluating resource visibility.

### Why it happened
- Custom `hasRole` / `hasAnyRole` bridge mixed legacy `users.role` and Spatie checks, but one path passed non-scalar role payloads unsafely.

### Fix delivered
- Refactored role bridge logic in `app/Models/User.php`:
  - robust role normalization,
  - array/traversable-safe flattening,
  - only valid normalized role names passed to Spatie checks.
- Retained `canAccessPanel()` role whitelist for operational users including `procurement_officer`.

### Validation completed
- `php artisan optimize:clear`
- Tinker check:
  - procurement account exists and role is `procurement_officer`
  - `hasAnyRole(['procurement_officer','admin'])` returns `true`
- Procurement login confirmed working.
