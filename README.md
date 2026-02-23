<p align="center">
  <img src="public/images/oceanova.png" width="160" alt="Oceanova Logo">
</p>

# Oceanova Restaurant (Laravel)

Laravel integration of the Taste.it Bootstrap template with booking email notifications.

## Features
- Blade layout + pages wired from the template
- Centralized assets under public/assets/template
- Booking form that sends admin notifications and user confirmations
- Responsive design preserved from the original template

## Pages
- Home: /
- About: /about
- Chef: /chef
- Menu: /menu
- Reservation: /reservation
- Blog: /blog
- Blog Single: /blog-single
- Contact: /contact

## Booking Flow
POST /booking
- Fields: service_id (optional), room_id (optional), full_name, email, tel, noofv, signin, signout
- Admin notification → configured recipient list
- User confirmation → the customer’s email

### Email Templates
- Layout: resources/views/emails/layout.blade.php
- Admin: resources/views/emails/admin-booking.blade.php
- User: resources/views/emails/user-booking.blade.php

## Setup
1. Install PHP dependencies:
	- composer install
2. Copy env:
	- cp .env.example .env
3. Generate key:
	- php artisan key:generate
4. Configure mail (see below), then clear config cache.

## Mail Configuration (SMTP 465/SSL)
Use placeholders in .env (don’t commit real credentials):

MAIL_MAILER=smtp
MAIL_SCHEME=null
MAIL_ENCRYPTION=ssl
MAIL_HOST=smtp.hostinger.com
MAIL_PORT=465
MAIL_USERNAME=your_email@domain.com
MAIL_PASSWORD="your_password"
MAIL_FROM_ADDRESS=your_email@domain.com
MAIL_FROM_NAME="Oceanova"

## Local Testing
- Set MAIL_MAILER=log
- Submit the reservation form
- Check storage/logs/laravel.log

## Notes
- If the app is hosted with the project root as the document root, .htaccess routes traffic into public/ so assets load correctly.

## Inventory & Procurement Module (ERP Foundation)

This project now includes an inventory and procurement backend module designed for restaurant operations with three role personas:

- Procurement Officer
- Kitchen Manager
- General Order Person

### New Data Model

The following tables were added:

- users (added role column)
	- role values:
		- procurement_officer
		- kitchen_manager
		- general_order_person
- ingredients
	- id, name, unit (kg|gram|pcs|liter), current_stock, min_stock_alert_level
- procurements
	- id, ingredient_id, quantity_received, unit_cost, supplier_name, received_at
- recipes
	- id, menu_item_id (meals.id), ingredient_id, quantity_required
- inventory_logs
	- id, ingredient_id, user_id, type (in|out|waste), quantity, reason

### Role Logic Implemented

- Procurement Officer:
	- Can access procurement stock-in endpoints only.
	- Stock-in increases ingredients.current_stock.
	- Stock-in writes inventory_logs type=in.

- Kitchen Manager:
	- Can fetch stock levels.
	- Can fetch low-stock list (current_stock <= min_stock_alert_level).
	- Can log waste; waste decrements stock and writes inventory_logs type=waste.

- General Order Person:
	- Order creation/editing triggers automatic ingredient deduction from recipes.
	- All deductions are transactional and logged as inventory_logs type=out.
	- On order edit, stock is adjusted by delta (extra outflow or restock).

### Core Backend Files Added

- Models:
	- app/Models/Ingredient.php
	- app/Models/Procurement.php
	- app/Models/Recipe.php
	- app/Models/InventoryLog.php
- Service:
	- app/Services/InventoryService.php
- Controllers:
	- app/Http/Controllers/ProcurementController.php
	- app/Http/Controllers/KitchenInventoryController.php
- Middleware:
	- app/Http/Middleware/EnsureUserRole.php

### Existing Files Extended

- app/Models/User.php
	- role fillable + role helpers + inventoryLogs relationship
- app/Models/Meal.php
	- recipes relationship
- routes/web.php
	- role-protected inventory/procurement routes
- bootstrap/app.php
	- middleware alias: role
- app/Filament/Resources/OrderResource/Pages/CreateOrder.php
	- stock-out integration during order creation
- app/Filament/Resources/OrderResource/Pages/EditOrder.php
	- stock adjustment integration during order updates

### Seeder Added

- database/seeders/IngredientSeeder.php
	- Seeds the full provided ingredient list
	- Removes empty entries and duplicate names
	- Defaults unit to pcs and min_stock_alert_level to 10

### Seeder Registration

- database/seeders/DatabaseSeeder.php now calls:
	- MenuCatalogSeeder
	- IngredientSeeder

It also creates role-specific default users:

- test@example.com (general_order_person)
- procurement@example.com (procurement_officer)
- kitchen@example.com (kitchen_manager)

### Inventory Endpoints

All endpoints are currently in web routes and protected by auth + role middleware.

- Procurement Officer routes:
	- GET /admin/procurements
	- POST /admin/procurements

- Kitchen Manager routes:
	- GET /admin/inventory/stock-levels
	- GET /admin/inventory/low-stock
	- POST /admin/inventory/waste

### Operational Commands

Run these after pulling latest changes:

1) Install/update dependencies
- composer install

2) Refresh autoload files
- composer dump-autoload

3) Run migrations
- php artisan migrate

4) Seed base data (menu + ingredients + role users)
- php artisan db:seed

5) Confirm route registration
- php artisan route:list --path=admin

6) Optional cache clear during deployment/debug
- php artisan optimize:clear

### Important Runtime Behavior

- Inventory calculations are wrapped in DB transactions.
- Ingredient rows are lockForUpdate during stock mutation to prevent race conditions.
- Insufficient stock throws a RuntimeException and the transaction rolls back.

### Recommended Next Step

For production ergonomics, add dedicated Filament resources for Ingredient, Procurement, Recipe, and InventoryLog so each role can operate via UI instead of JSON endpoints.

## Filament Frontend Operations

The admin panel now supports role-based frontend operations for inventory and procurement through Filament resources.

### Admin URL

- /admin

### Navigation Group

- Inventory & Procurement

### Frontend Screens

#### 1) Ingredients (Kitchen Manager/Admin)

Purpose:
- View live stock levels
- View low-stock alerts
- Log waste operations from UI

Capabilities:
- Table columns: ingredient name, unit, current stock, min alert, low-stock status
- Filter: Low Stock Alerts
- Row action: Log Waste
	- Inputs: quantity, reason
	- Effect: decrements stock and writes inventory_logs(type=waste)

Access:
- kitchen_manager
- admin
- super_admin

#### 2) Procurements (Procurement Officer/Admin)

Purpose:
- Perform Stock In from frontend form
- Review procurement history

Capabilities:
- Create form fields:
	- ingredient
	- quantity_received
	- unit_cost
	- supplier_name
	- received_at
- Effect on save:
	- creates procurement record
	- increments ingredient stock
	- writes inventory_logs(type=in)

Access:
- procurement_officer
- admin
- super_admin

#### 3) Recipes (Admin)

Purpose:
- Define ingredient consumption per menu item
- Drive automatic stock deduction when orders are created/edited

Capabilities:
- CRUD for menu_item + ingredient + quantity_required

Access:
- admin
- super_admin

#### 4) Inventory Logs (Kitchen Manager/Admin, Read-Only)

Purpose:
- Audit every inventory movement from the frontend

Capabilities:
- Read-only table of all movements
- Filters:
	- movement type (`in`, `out`, `waste`)
	- date range (`from_date`, `until_date`)
	- user/actor (`user_id`)
- Header action: `Export CSV` (exports the currently filtered result set)
- Useful columns:
	- ingredient
	- type badge
	- quantity
	- actor
	- reason
	- logged timestamp

Access:
- kitchen_manager
- admin
- super_admin

#### 5) Users (Admin/Super Admin)

Purpose:
- Manage user accounts and assign operational roles from frontend

Capabilities:
- View user list
- Create users
- Edit user role and profile details
- Update user password
- Delete users (admin-level only)

Supported roles:
- admin
- super_admin
- procurement_officer
- kitchen_manager
- general_order_person

Access:
- admin
- super_admin

### Dashboard Widget

`Low Stock Alerts` widget is now available on `/admin` dashboard for:

- kitchen_manager
- admin
- super_admin

Widget highlights:
- Shows only low-stock ingredients
- Displays current stock, minimum threshold, and shortfall
- Prioritizes largest shortfall first

### Automatic Stock Out Behavior in Frontend Order Processing

When orders are created or updated from Filament Order pages:

- Create:
	- order + order items saved in transaction
	- ingredients deducted based on recipes
	- inventory_logs(type=out) created

- Edit:
	- old/new recipe requirements compared
	- additional usage deducted OR excess restocked
	- inventory logs written for each movement

### Admin Override Behavior

- Admin/super_admin can pass role middleware checks.
- Admin can access role-restricted operations for supervision.

### Frontend Role Assignment Example

Use tinker to assign a specific account role:

- `php artisan tinker`
- `App\\Models\\User::where('email','victorynonso9@gmail.com')->update(['role' => 'admin']);`

### Frontend Availability Checklist

After deployment:

- `php artisan migrate`
- `php artisan db:seed`
- `php artisan optimize:clear`
- login to `/admin`
- verify Inventory & Procurement navigation group appears for authorized users
- verify Administration > Users appears for admin/super_admin
- verify dashboard shows Low Stock Alerts widget for manager/admin roles

## Troubleshooting (Production)
- Blade parse error near layout end (`expecting elseif/else/endif`) was caused by JSON-LD keys using `@context`/`@type` directly in Blade.
- Fix applied in `resources/views/layouts/app.blade.php`: escaped JSON-LD keys as `@@context` and `@@type` so Blade outputs valid `@...` keys instead of parsing directives.
- After deploy, clear/rebuild view cache:
	- `php artisan optimize:clear`
	- `php artisan view:cache`

### Server PHP Profile Override (SSH)
When host default CLI PHP is older than project requirements, force PHP 8.3 in shell profile:

```bash
echo 'export PATH=/opt/alt/php83/usr/bin:$PATH' >> ~/.bash_profile
echo 'alias php="/opt/alt/php83/usr/bin/php"' >> ~/.bash_profile
echo 'alias composer="php /usr/local/bin/composer"' >> ~/.bash_profile
source ~/.bash_profile
hash -r
php -v
composer install --no-dev -o
```

If `~/.bash_profile` is not loaded by the host shell, add the same lines to `~/.bashrc`.
