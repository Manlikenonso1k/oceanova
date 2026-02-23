# Prompts, Results, Problems, Fixes

## Prompts Used
- Integrate the Taste.it template into a fresh Laravel install.
- Move assets into public/assets/template/.
- Create a master Blade layout and home view.
- Update asset paths using the Laravel asset helper.
- Create routes for all template pages.
- Provide an information summary file.
- Fix 403/denied asset loading by adjusting hosting rewrites.
- Add Laravel booking mail notifications with Hostinger SMTP.
- Troubleshoot mailer configuration errors and form validation.
- Replace placeholder copy (about, testimonials, chefs, footer) with real content.
- Add newsletter subscribe + scheduled email every 3 days.
- Improve SEO metadata, heading hierarchy, canonical, robots/sitemap, and JSON-LD.
- Rebuild menu page into a premium mobile-first digital menu UI using Tailwind and a reusable Blade component.
- Add image support to each menu card with fallback behavior.
- Troubleshoot menu-page watermark image not visible on black background.

## Results
- Assets moved into public/assets/template/.
- Master layout created at resources/views/layouts/app.blade.php.
- Home page created at resources/views/home.blade.php.
- Template pages created: about, chef, menu, reservation, blog, blog-single, contact.
- Routes added in routes/web.php with named routes and updated navbar links.
- INFORMATION.md added with a concise integration summary.
- Root .htaccess updated to route requests into /public.
- JS asset base set and map marker path fixed.
- Booking mail flow added (controller, mailables, email templates, and routes).
- Booking forms wired to POST /booking with CSRF protection.
- Reservation page displays success and validation errors.
- .env.example aligned with SMTP 465/SSL.
- Perfect Ingredients copy updated across home/about/reservation.
- Home testimonials and chef bios rewritten; about testimonials mirrored.
- Footer brand description and newsletter blurb updated.
- Newsletter system added (subscribe form, model, migration, mailable, command, schedule).
- Layout SEO updated with title fallback, canonical URL, description, social image tags, and JSON-LD.
- robots.txt updated and sitemap.xml created at public/sitemap.xml.
- Google verification file added to public web root.
- Menu rebuilt with reusable `x-menu-item` component and responsive 1/2/3-column grid.
- Full provided menu data mapped into structured sections and cards.
- Menu cards now support images (explicit or deterministic fallback).

## Problems Encountered
- Static assets (CSS/JS/images) returned 403/denied when the site was served from the project root.
- Mailer error: "Mailer [smtps] is not defined."
- SMTP authentication failed (535).
- Validation error: "The noofv field is required."
- Server CLI used PHP 8.1.32 while composer requires PHP >= 8.3.0.
- Menu Blade file became corrupted from mixed legacy and new markup.
- Blade parse errors encountered (unexpected token, raw directives shown in output).
- Menu watermark image configured but not visibly rendering on the black menu section.

## PHP Error Prompts & Fixes
- Prompt: `Composer detected issues in your platform: Your Composer dependencies require a PHP version ">= 8.3.0". You are running 8.1.32.`
	- Fix: switched shell/runtime to PHP 8.3 via profile PATH + alias and ran composer with that binary.
- Prompt: `ParseError ... resources/views/layouts/app.blade.php:188 ... expecting "elseif" or "else" or "endif"`
	- Fix: escaped JSON-LD keys in layout Blade (`@context`/`@type` to `@@context`/`@@type`) so Blade no longer parses them as directives.
	- Post-fix ops: cleared and rebuilt compiled views (`php artisan optimize:clear`, `php artisan view:cache`).

## Fixes Applied
- Added rewrite rules in .htaccess to forward requests into /public.
- Set `window.TEMPLATE_ASSET_BASE` and updated the Google Maps marker icon path.
- Changed mailer to `smtp` and used `MAIL_ENCRYPTION=ssl` for port 465.
- Instructed quoting passwords with special characters in .env.
- Required guest count and set a default value in reservation form.
- Ran Artisan with Hostinger’s PHP 8.3 binary: /opt/alt/php83/usr/bin/php.
- Replaced corrupted menu view content with clean Blade structure and corrected loop directives.
- Standardized menu rendering through a reusable component (`resources/views/components/menu-item.blade.php`).
- Fixed Blade parse error on homepage layout by escaping JSON-LD keys in `resources/views/layouts/app.blade.php`:
	- `"@context"` → `"@@context"`
	- `"@type"` → `"@@type"`
- Cleared and rebuilt compiled views after deploy (`php artisan optimize:clear`, `php artisan view:cache`).
- Added shell-profile PHP override for servers where `php` defaults below project requirement:
	- `export PATH=/opt/alt/php83/usr/bin:$PATH`
	- `alias php='/opt/alt/php83/usr/bin/php'`
	- `alias composer='php /usr/local/bin/composer'`
- Updated menu watermark layer in `resources/views/menu.blade.php` for dark-background visibility:
	- switched image source to `asset('images/oceanova.png')`
	- increased watermark opacity (`opacity-20`)
	- applied blend mode (`mix-blend-screen`)
	- increased tile size (`background-size: 260px auto`)

## Inventory & Procurement ERP Prompt

### Prompt Used
- Build a robust Inventory and Procurement module for Restaurant Admin with three roles:
	- Procurement Officer
	- Kitchen Manager
	- General Order Person
- Include:
	- Migrations with FK constraints
	- Eloquent relationships
	- Stock-out logic using Recipe table on order placement
	- Seeder from provided ingredient list

### Results
- Added migrations for users role, ingredients, procurements, recipes, and inventory_logs.
- Added models and relationships:
	- Ingredient, Procurement, Recipe, InventoryLog
	- Meal and User extended with needed relations/helpers
- Added InventoryService for all stock mutation flows:
	- stock-in
	- waste
	- stock-out on order create
	- stock adjustment on order edit
- Added role middleware and registered alias in bootstrap app config.
- Added role-protected procurement and inventory routes.
- Added ProcurementController and KitchenInventoryController.
- Integrated inventory deductions into Filament order create/edit pages.
- Added IngredientSeeder with provided inventory list cleanup and defaults.
- Updated DatabaseSeeder to call IngredientSeeder and create role users.

### Problems Encountered
- Local terminal environment lacked PHP binary (`php: command not found`), so runtime Artisan verification could not be executed in-agent.
- Existing app had no role middleware or role column in users table, requiring foundational role scaffolding before route protection.

### Fixes Applied
- Added users role migration with default value and role helper methods in User model.
- Added EnsureUserRole middleware and registered alias (`role`) in bootstrap/app.php.
- Used transactional stock operations and row locking to avoid race conditions.
- Added defensive stock checks to prevent negative inventory.
- Added inventory logs for complete movement auditability.
