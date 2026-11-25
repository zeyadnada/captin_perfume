# Project Status

## Current Position

- Roadmap phase: Phase 5 in progress (Customer accounts + Product catalog complete)
- Current modules: Admin (CoolAdmin) + Localization + Kalles storefront (home-classic chrome + catalog)
- Status: Home Classic full Blade port (demo sections kept for styling review)
- Next pending: Phase 5 – Shopping cart (after optional home-classic cleanup)

## Completed Work

### Phase 1 - Project Foundation

- Laravel 13 project inspected and configured.
- PHP 8.3 application and MySQL application database configured.
- CoolAdmin template assets integrated under `public/cooladmin`.
- Admin and authentication Blade layout foundations created.
- Admin dashboard route and reusable admin layout added.
- Root dashboard route added.
- Application name and timezone configured.
- Session-based admin authentication (login, logout, remember me).
- CoolAdmin login page using the auth layout.
- `auth` / `guest` middleware protecting admin routes and the login form.
- Default admin seeder: `admin@example.com` / `password`.
- Authentication feature tests; existing admin feature tests authenticate via `actingAs`.
- Spatie Laravel Permission installed (`spatie/laravel-permission`).
- Permission tables migrated; `HasRoles` on the User model.
- `Permission` and `Role` enums plus `RolePermissionSeeder` (admin role with full catalog).
- Default admin user assigned the `admin` role.
- Spatie middleware aliases registered; admin routes guarded with `auth` + `staff` (user must have at least one role).
- Role/permission feature tests; `actingAsAdmin()` assigns the admin role.
- Admin Policies for Brand, Category, Product, ProductVariant, ProductImage, stock, and stock movements.
- Form Request `authorize()` and controller `$this->authorize()` wired to Policies / Spatie permissions.
- Sidebar nav items gated with `@can` / permission checks.
- `actingAsAdminWithout()` helper and admin authorization feature tests.
- Administration section (Users + Roles CRUD) above Master Data in the sidebar.
- User and Role policies, Form Requests, CoolAdmin views, EN/AR strings.
- Dual roles: `super_admin` (all permissions) and `admin` (all except Users/Roles).
- Seeded `admin@example.com` is `super_admin`; system-role safety protects `super_admin`.
- Nullable `phone` on users (admin create/edit/index).
- Users/Roles feature tests.

Phase 1 authentication, authorization, and staff administration are complete.

### Phase 2 - Brands Management

- Added the `brands` migration with:
  - Name
  - Unique slug
  - Description
  - Logo
  - Active status
  - Soft deletion
- Added the `Brand` model and factory.
- Added create and update Form Requests.
- Added brand CRUD controller logic.
- Added search, status filtering, and pagination.
- Added logo upload and replacement handling.
- Added admin index, create, and edit Blade views.
- Added brand resource routes and sidebar navigation.
- Added Brand feature tests.

Verification:

- Brands and homepage feature tests pass: 5 tests, 18 assertions.
- Laravel Pint formatting passes.

### Phase 2 - Categories Management

- Added hierarchical category data, validation, CRUD, search, status filtering, and pagination.
- Added parent relationships and prevention of descendant cycles.
- Added category admin views and sidebar navigation.
- Added Category feature tests.

Verification:

- Category management tests pass: 5 tests, 14 assertions.
- Laravel Pint formatting passes.

### Phase 2 - Product Management

- Added the `products` migration with brand and category relationships, naming/slug/SKU fields, descriptions, active status, and soft deletion.
- Added the `Product` model, factory, and Brand/Category product relationships.
- Added create and update Form Requests with slug/SKU normalization and unique constraints.
- Added product CRUD, search, brand/category/status filtering, and pagination.
- Added CoolAdmin product index, show, create, and edit Blade views and sidebar navigation.
- Added Product feature tests.

### Phase 2 - Product Variants

- Added the `product_variants` migration with structured volume/unit, unique SKU, price, compare-at price, active status, and soft deletion.
- Added the `ProductVariant` model, factory, and Product relationship.
- Added nested product-variant routes and admin CRUD controller.
- Added Store/Update Form Requests with duplicate volume/unit and compare-at-price validation.
- Added CoolAdmin variant index, create, edit, and Product show-page preview views.
- Added variant counts to the Product index and Product feature integration.
- Added Product Variant feature tests.

Verification status:

- Runtime test and Pint results remain pending terminal verification.

### Phase 2 - Product Images

- Added the `product_images` migration with product relationship, public file path, and sort order.
- Added the `ProductImage` model, factory, and product gallery relationship.
- Added nested image routes and the admin gallery controller.
- Added upload and metadata Form Request validation.
- Added multi-image upload, ordering, deletion, and physical file cleanup.
- Added gallery preview and gallery management access to the Product show page.

Verification status:

- Product Image feature tests have been added.
- Runtime test and Pint results remain pending terminal verification.

### Phase 3 - Stock Management

- Added a one-to-one stock record for each product variant.
- Added physical quantity, reserved quantity, available quantity, and low-stock threshold handling.
- Added the inventory index and stock update pages using the CoolAdmin layout.
- Added inventory sidebar navigation.
- Added stock feature tests and route integrity validation.

### Phase 3 - Stock Movements

- Added an append-only movement ledger with incoming/outgoing type, quantity, reason, resulting balance, and optional source references.
- Existing nonzero stock balances are recorded as opening-balance movements when the ledger migration runs.
- Added admin movement history and movement recording pages.
- Stock balance changes and movement records are saved atomically; outgoing movements cannot use reserved stock.
- Removed direct physical quantity editing from inventory settings so on-hand changes must be recorded as movements.
- Added stock movement feature tests.

Verification status:

- Runtime test and Pint results remain pending terminal verification.

### Phase 4 - Localization (EN/AR)

- Added session-based locale switching for English (default) and Arabic.
- Added `SetLocale` middleware, locale switch route/controller, and `available_locales` config.
- Added `lang/en` and `lang/ar` translation files for admin UI, flash messages, pagination, and validation.
- Wired admin Blade views, controllers, and custom validation messages to `__()` helpers.
- Added RTL support for Arabic (`dir="rtl"`, Bootstrap RTL CSS, Cairo font, `rtl.css` overlay).
- Added header language switcher (English / العربية).
- Added locale feature tests.

Verification:

- Locale feature tests pass: 5 tests, 23 assertions.
- Related Category and Inventory tests pass with localization: 17 tests, 52 assertions.
- Laravel Pint formatting passes.

### Phase 5 - Customer Accounts (MaleFashion)

- Copied MaleFashion runtime assets to `public/malefashion` (css, js, img, fonts).
- Added storefront Blade layout and partials (header, footer, offcanvas, preloader, search).
- Public storefront home at `GET /` (placeholder hero shell).
- Split authentication:
  - Customer `/login`, `/register`, `/logout`, `/account` (MaleFashion-styled).
  - Admin `/admin/login` and `/admin/logout` (CoolAdmin).
- Customers use the shared `users` table with no Spatie roles; `User::isStaff()` gates admin access.
- Guest redirects: admin routes → `admin.login`; storefront → `login`.
- EN/AR `storefront` translation files for nav, auth, account, and flash messages.
- Feature tests: admin auth updated; customer auth coverage added.

### Phase 5 - Product Catalog (Kalles)

- Public shop listing at `GET /shop` and product detail at `GET /shop/{product:slug}`.
- Catalog visibility: active product + active brand/category + at least one active variant (`Product::forCatalog()`).
- Shop filters: search, brand slug, category slug, sort (latest / price / name); pagination (12).
- Storefront UI cut over from MaleFashion to **Kalles**: curated assets in `public/kalles`, Blade layout/partials, shop index (left sidebar), PDP (Swiper gallery + volume swatches).
- **Home Classic full port:** near-literal `dist/home-classic.html` → `storefront/home.blade.php` with shared chrome from `header2`, classic footer, top banner, popup, and full `card-model` (quick view / cart / search / account offcanvas).
- Demo sections (trending, blog, Instagram, shipping, mega menus) kept temporarily for full styling experience; cleanup deferred.
- Laravel session locale drives Kalles built-in RTL/LTR (`bootstrap-rtl` / `app-rtl`, `html dir`); demo LTR/RTL footer toggle removed.
- Add-to-cart UI disabled as coming soon (cart deferred to next Phase 5 task).
- Auth/account inherit home-classic chrome; `public/malefashion` removed.
- EN/AR storefront `shop` and `product` translation keys retained; hero uses `storefront.home.*`.
- Feature tests: `StorefrontCatalogTest` + customer auth coverage; Pint formatting passes.

## Known Pending Items

- Phase 5 – Shopping cart, checkout, customer addresses.